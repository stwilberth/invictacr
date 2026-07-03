<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepseekTranslationService
{
    public function translateDescription(array $data): ?string
    {
        $apiKey = config('services.deepseek.key');
        if (!$apiKey) {
            return null;
        }

        $parts = [];
        if (!empty($data['title'])) {
            $parts[] = "Título: {$data['title']}";
        }
        if (!empty($data['descripcion'])) {
            $parts[] = "Descripción original: {$data['descripcion']}";
        }
        if (!empty($data['coleccion'])) {
            $parts[] = "Colección: {$data['coleccion']}";
        }
        if (!empty($data['size'])) {
            $parts[] = "Tamaño: {$data['size']}mm";
        }
        if (!empty($data['caja'])) {
            $parts[] = "Material de la caja: {$data['caja']}";
        }
        if (!empty($data['brazalete'])) {
            $parts[] = "Material de la correa: {$data['brazalete']}";
        }
        if (!empty($data['genero'])) {
            $parts[] = "Género: {$data['genero']}";
        }
        if (!empty($data['resistencia_agua'])) {
            $parts[] = "Resistencia al agua: {$data['resistencia_agua']}";
        }
        if (!empty($data['movimiento_raw'])) {
            $parts[] = "Movimiento: {$data['movimiento_raw']}";
        }

        if (empty($parts)) {
            return null;
        }

        $input = implode("\n", $parts);

        $systemPrompt = "Eres un experto en relojería. Recibes datos técnicos de un reloj Invicta en inglés y debes generar una descripción comercial atractiva en español (para Costa Rica) de 2-3 oraciones.

REGLAS:
1. Traduce todo a español costarricense.
2. La descripción debe ser fluida, persuasiva y lista para usar en una tienda online.
3. Menciona las características más importantes: tipo de movimiento, material de la caja, material de la correa, resistencia al agua y tamaño.
4. NO incluyas el precio.
5. NO uses emojis.
6. Responde SOLO con la descripción, sin prefijos ni explicaciones.";

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $input],
                ],
                'temperature' => 0.3,
                'max_tokens' => 500,
            ]);

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if ($content) {
                return trim($content);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
