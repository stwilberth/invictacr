<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Genera narraciones de audio (TTS) para los textos de campaña usando
 * ElevenLabs a través del AI Gateway de Cloudflare (BYOK: la key vive
 * en el gateway, aquí no se guarda ninguna credencial de proveedor).
 */
class NarrationService
{
    public function gatewayBase(): string
    {
        $cfg = config('services.ai_gateway');

        return 'https://gateway.ai.cloudflare.com/v1/'
            . $cfg['account_id'] . '/' . $cfg['gateway_id'] . '/elevenlabs/v1';
    }

    /**
     * Convierte el texto del anuncio en guion hablable (sin emojis ni CTAs de texto).
     */
    public function buildScript(array $content): string
    {
        $parts = array_filter([
            $content['headline'] ?? '',
            $content['body'] ?? '',
        ]);

        $text = implode('. ', $parts);
        // Quita emojis y símbolos no hablables
        $text = (string) preg_replace('/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}]/u', '', $text);
        $text = str_replace(['✅', '✨', '💰', '🚚', '📲', '🔥', '–', '—'], ['.', '.', '.', '.', '.', '.', ',', ','], $text);
        $text = (string) preg_replace('/\s+/', ' ', trim($text));

        return mb_substr($text, 0, 900);
    }

    /**
     * Llama al TTS y guarda el MP3 en el disco público. Retorna la ruta pública (/storage/...).
     *
     * @throws \RuntimeException
     */
    public function narrate(string $script, ?string $voiceId = null, ?string $filename = null): string
    {
        $cfg = config('services.ai_gateway');

        if (empty($cfg['token'])) {
            throw new \RuntimeException('Falta AI_GATEWAY_TOKEN en el .env');
        }

        $voice = $voiceId ?: $cfg['voice'];

        $response = Http::withHeaders([
            'cf-aig-authorization' => 'Bearer ' . $cfg['token'],
            'Content-Type' => 'application/json',
        ])->timeout($cfg['timeout'])->post(
            $this->gatewayBase() . '/text-to-speech/' . $voice,
            [
                'text' => $script,
                'model_id' => 'eleven_multilingual_v2',
                'output_format' => 'mp3_44100_128',
            ]
        );

        if (! $response->successful()) {
            throw new \RuntimeException('TTS falló (' . $response->status() . '): ' . mb_substr($response->body(), 0, 200));
        }

        $name = $filename ?: 'narracion-' . now()->format('Ymd-His') . '-' . uniqid() . '.mp3';
        $path = 'narraciones/' . $name;

        Storage::disk('public')->put($path, $response->body());

        return '/storage/' . $path;
    }
}
