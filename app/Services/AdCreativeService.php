<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Mejora los canvases publicitarios de GD con Gemini (image editing nativo)
 * antes de publicarlos en Facebook/Instagram.
 *
 * Flujo: canva GD + foto real del producto -> Gemini rediseña con estética
 * premium manteniendo composición y textos exactos -> verificación automática
 * (OCR + presencia de badges) -> PNG final al tamaño del formato.
 *
 * Cualquier fallo devuelve null y el llamador debe usar el canva original.
 */
class AdCreativeService
{
    public const PHONE = '8671-1422';
    public const WEBSITE = 'invictaCostaRica.com';

    public function isConfigured(): bool
    {
        return !empty(config('services.gemini.key'));
    }

    /**
     * @param 'story'|'feed' $format
     * @return string|null PNG final listo para publicar, o null si algo falla.
     */
    public function enhance(Product $product, string $canvasPng, string $format = 'story'): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $texts = $this->textsFor($product);
            $photo = $this->realProductPhoto($product);
            $prompt = $this->buildPrompt($texts, $photo !== null);
            $maxAttempts = max(1, (int) config('services.gemini.max_attempts', 3));

            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                $imgData = $this->callGemini($prompt, $canvasPng, $photo, $format);
                if (!$imgData) {
                    Log::warning("AdCreative: Gemini sin imagen para {$product->modelo} ({$format}) intento {$attempt}.");
                    continue;
                }

                $fails = $this->verify($imgData, $texts);
                if (empty($fails)) {
                    if ($attempt > 1) {
                        Log::info("AdCreative: {$product->modelo} ({$format}) pasó verificación en intento {$attempt}.");
                    }
                    return $this->resize($imgData, $format);
                }
                Log::warning("AdCreative: verificación falló para {$product->modelo} ({$format}) intento {$attempt}: " . implode(' | ', $fails));
            }

            return null;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /** @return array{title:string,model:string,phone:string,spec1:string,spec2:string,price:string,priceDigits:string} */
    private function textsFor(Product $product): array
    {
        $size = $product->size ? preg_replace('/\s*mm$/i', '', $product->size) . ' mm' : '';
        $agua = $product->resistencia_agua ? $product->resistencia_agua . ' m' : '';
        $mov = $product->tipo_movimiento ? ucfirst($product->tipo_movimiento) : '';

        return [
            'title' => 'INVICTA ' . strtoupper($product->coleccion ?? $product->modelo ?? ''),
            'model' => (string) ($product->codigo_comercial ?? $product->modelo),
            'phone' => self::PHONE,
            'spec1' => implode('  ·  ', array_filter([$size, $agua])),
            'spec2' => implode('  ·  ', array_filter([$mov, $product->brazalete])),
            'price' => '₡' . number_format((float) $product->precio_final, 0),
            'priceDigits' => number_format((float) $product->precio_final, 0),
        ];
    }

    /** Foto real del producto desde R2 (para fidelidad del reloj). Null si no hay. */
    private function realProductPhoto(Product $product): ?string
    {
        try {
            $modelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
            $r2 = Storage::disk('r2');
            foreach (["relojes/large/{$modelo}.webp", "relojes/medium/{$modelo}.webp", "relojes/{$modelo}.webp"] as $path) {
                if ($r2->exists($path)) {
                    return $r2->get($path);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("AdCreative: sin foto R2 para {$product->modelo}: " . $e->getMessage());
        }
        return null;
    }

    private function buildPrompt(array $t, bool $hasPhoto): string
    {
        $specs = implode("\n", array_filter([
            $t['spec1'] !== '' ? "- specs line: \"{$t['spec1']}\"" : null,
            $t['spec2'] !== '' ? "- specs line: \"{$t['spec2']}\"" : null,
        ]));

        $p = "Upgrade the attached watch advertisement (IMAGE 1) to a PREMIUM, professional, luxury look: "
            . "richer navy-and-gold background with depth, refined lighting on the watch, elegant premium typography. "
            . "Keep the exact same top-to-bottom composition and every element in its position.\n\n";

        if ($hasPhoto) {
            $p .= "IMAGE 2 is the REAL product photo: the ad must show THIS EXACT watch, faithful rendering, do NOT invent a different watch.\n\n";
        }

        $p .= "STRICT RULES (violating any of these ruins the ad):\n"
            . "1. UNTOUCHABLE ELEMENTS — reproduce them prominently and legibly, never restyle, shrink or drop them:\n"
            . "   - the LARGE RED rounded price badge containing \"{$t['price']}\"\n"
            . "   - the GOLD \"ENVÍO GRATIS\" tag (every letter readable)\n"
            . "   - the green WhatsApp pill with \"{$t['phone']}\" (all digits perfectly readable)\n"
            . "   - the white circle framing the watch\n"
            . "2. ALL text copied character by character, correctly spelled:\n"
            . "   - \"{$t['title']}\"\n"
            . "   - \"{$t['model']}\"\n"
            . "   - \"{$t['phone']}\"\n"
            . ($specs !== '' ? "   {$specs}\n" : '')
            . "   - \"ENVÍO GRATIS\"\n"
            . "   - \"{$t['price']}\"\n"
            . "   - \"" . self::WEBSITE . "\"\n"
            . "3. No invented text, no extra logos, no watermarks, no fake numbers.";

        return $p;
    }

    private function callGemini(string $prompt, string $canvas, ?string $photo, string $format): ?string
    {
        $parts = [
            ['text' => $prompt],
            ['inline_data' => ['mime_type' => 'image/png', 'data' => base64_encode($canvas)]],
        ];
        if ($photo !== null) {
            $parts[] = ['inline_data' => ['mime_type' => 'image/webp', 'data' => base64_encode($photo)]];
        }

        $resp = Http::withHeaders(['x-goog-api-key' => config('services.gemini.key')])
            ->timeout((int) config('services.gemini.timeout', 180))
            ->post('https://generativelanguage.googleapis.com/v1beta/models/' . config('services.gemini.model', 'gemini-2.5-flash-image') . ':generateContent', [
                'contents' => [['parts' => $parts]],
                'generationConfig' => [
                    'responseModalities' => ['IMAGE'],
                    'imageConfig' => ['aspectRatio' => $format === 'feed' ? '4:5' : '9:16'],
                ],
            ]);

        if ($resp->failed()) {
            Log::warning('AdCreative: Gemini HTTP ' . $resp->status() . ' ' . substr($resp->body(), 0, 200));
            return null;
        }

        foreach ($resp->json('candidates.0.content.parts', []) as $part) {
            if (isset($part['inlineData']['data'])) {
                return base64_decode($part['inlineData']['data']);
            }
        }
        return null;
    }

    /**
     * Verifica que el rediseño conserva lo crítico. Fallos duros abortan;
     * el tag dorado solo advierte (el OCR falla en tipografías pequeñas).
     *
     * @return string[] lista de fallos (vacía = ok)
     */
    public function verify(string $pngBytes, array $texts): array
    {
        $fails = [];
        $tmp = tempnam(sys_get_temp_dir(), 'adcr') . '.png';
        file_put_contents($tmp, $pngBytes);

        [$redPct, $goldPct] = $this->badgeCoverage($tmp);
        if ($redPct < 2.0) $fails[] = "badge rojo del precio ausente ({$redPct}%)";
        if ($goldPct < 0.3) {
            Log::warning("AdCreative: tag dorado bajo ({$goldPct}%), revisar visualmente.");
        }

        if ($this->tesseractAvailable()) {
            $top = strtolower($this->ocrRegion($tmp, 0, 0.35));
            $bot = strtolower($this->ocrRegion($tmp, 0.5, 1.0));

            if (!str_contains($top, 'invicta')) $fails[] = 'falta marca';
            if (!str_contains($top, strtolower($texts['model']))) $fails[] = "falta modelo {$texts['model']}";
            if (!str_contains(str_replace([' ', '-'], '', $top), str_replace('-', '', $texts['phone']))) {
                $fails[] = "falta teléfono {$texts['phone']}";
            }
            $pd = explode(',', $texts['priceDigits']);
            if (!(str_contains($bot, $pd[0]) && str_contains($bot, end($pd)))) {
                $fails[] = "falta precio {$texts['priceDigits']}";
            }
            if (!str_contains($bot, 'invicta')) $fails[] = 'falta sitio web';
        } else {
            Log::warning('AdCreative: tesseract no disponible, verificación solo por color.');
        }

        @unlink($tmp);
        return $fails;
    }

    private function tesseractAvailable(): bool
    {
        static $ok = null;
        if ($ok === null) {
            $out = shell_exec('command -v tesseract 2>/dev/null');
            $ok = !empty(trim((string) $out));
        }
        return $ok;
    }

    private function ocrRegion(string $pngPath, float $y0, float $y1): string
    {
        $crop = tempnam(sys_get_temp_dir(), 'adcr') . '.png';
        $big = tempnam(sys_get_temp_dir(), 'adcr') . '.png';
        try {
            $src = @imagecreatefromstring(file_get_contents($pngPath));
            if (!$src) return '';
            $w = imagesx($src);
            $h = imagesy($src);
            $cy = (int) ($h * $y0);
            $ch = (int) ($h * ($y1 - $y0));
            $part = imagecreatetruecolor($w, $ch);
            imagecopy($part, $src, 0, 0, 0, $cy, $w, $ch);
            imagepng($part, $crop);
            // Escala 2x + grises para el OCR.
            $g = imagecreatetruecolor($w * 2, $ch * 2);
            imagecopyresampled($g, $part, 0, 0, 0, 0, $w * 2, $ch * 2, $w, $ch);
            imagefilter($g, IMG_FILTER_GRAYSCALE);
            imagepng($g, $big);
            imagedestroy($src); imagedestroy($part); imagedestroy($g);
            return (string) shell_exec('tesseract ' . escapeshellarg($big) . ' stdout -l spa+eng --psm 6 2>/dev/null');
        } finally {
            @unlink($crop); @unlink($big);
        }
    }

    /** % de píxeles rojos (badge precio) y dorados (tag) en el 40% inferior. */
    private function badgeCoverage(string $pngPath): array
    {
        $src = @imagecreatefromstring(file_get_contents($pngPath));
        if (!$src) return [0.0, 0.0];
        $w = imagesx($src);
        $h = imagesy($src);
        $y0 = (int) ($h * 0.6);
        $red = $gold = $n = 0;
        for ($y = $y0; $y < $h; $y += 8) {
            for ($x = 0; $x < $w; $x += 8) {
                $c = imagecolorat($src, $x, $y);
                $r = ($c >> 16) & 0xFF; $g = ($c >> 8) & 0xFF; $b = $c & 0xFF;
                if ($r > 150 && $g < 110 && $b < 110) $red++;
                if ($r > 180 && $g > 140 && $b < 90) $gold++;
                $n++;
            }
        }
        imagedestroy($src);
        return $n > 0 ? [round($red / $n * 100, 2), round($gold / $n * 100, 2)] : [0.0, 0.0];
    }

    /** Reescala al tamaño exacto del formato. */
    private function resize(string $pngBytes, string $format): ?string
    {
        [$tw, $th] = $format === 'feed' ? [1080, 1350] : [1080, 1920];
        $src = @imagecreatefromstring($pngBytes);
        if (!$src) return null;
        $dst = imagecreatetruecolor($tw, $th);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $tw, $th, imagesx($src), imagesy($src));
        ob_start();
        imagepng($dst, null, 6);
        $out = ob_get_clean();
        imagedestroy($src); imagedestroy($dst);
        return $out !== false && $out !== '' ? $out : null;
    }
}
