<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class OgImageController extends Controller
{
    private const W = 1080;
    private const H = 1080;
    private const FONT_BOLD = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
    private const FONT_REGULAR = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';

    public function brand(): Response
    {
        $png = Cache::remember('og_brand', now()->addDays(30), function () {
            return $this->renderBrand();
        });

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=604800, immutable',
        ]);
    }

    private function renderBrand(): string
    {
        $img = imagecreatetruecolor(self::W, self::H);

        $navy = imagecolorallocate($img, 0x0a, 0x0f, 0x1c);
        $cyan = imagecolorallocate($img, 0x00, 0xc4, 0xff);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);
        $muted = imagecolorallocate($img, 0x9c, 0xa3, 0xb0);

        imagefill($img, 0, 0, $navy);

        // Franja cian inferior (acento de marca)
        imagefilledrectangle($img, 0, self::H - 12, self::W, self::H, $cyan);

        // Trazo de reloj minimalista
        $this->drawBrandClock($img, $white, $cyan);

        // Texto principal
        $this->drawCentered($img, 'RELÓJES INVICTA', 64, 540, $white, self::FONT_BOLD);
        $this->drawCentered($img, 'COSTA RICA', 46, 620, $cyan, self::FONT_BOLD);
        $this->drawCentered($img, '100% Originales · Envío Gratis · Garantía 6 Meses', 26, 700, $muted, self::FONT_REGULAR);

        ob_start();
        imagepng($img, null, 8);
        $data = ob_get_clean();
        imagedestroy($img);

        return $data;
    }

    private function drawBrandClock($img, $white, $cyan): void
    {
        $cx = self::W / 2;
        $cy = 280;
        $r = 130;
        $w = 6;

        // Círculo exterior
        imagefilledellipse($img, (int) $cx, $cy, $r * 2, $r * 2, $cyan);
        imagefilledellipse($img, (int) $cx, $cy, $r * 2 - $w * 2, $r * 2 - $w * 2, imagecolorallocate($img, 0x0a, 0x0f, 0x1c));

        // Manecillas
        imagefilledrectangle($img, (int) $cx - 4, (int) $cy - 74, (int) $cx + 4, (int) $cy + 2, $white);
        imagefilledrectangle($img, (int) $cx, (int) $cy - 4, (int) $cx + 62, (int) $cy + 4, $white);
        imagefilledellipse($img, (int) $cx, (int) $cy, 18, 18, $white);

        // Marcas de hora (12, 3, 6, 9)
        foreach ([0, 90, 180, 270] as $deg) {
            $rad = deg2rad($deg);
            $x = (int) ($cx + cos($rad) * ($r - 16));
            $y = (int) ($cy + sin($rad) * ($r - 16));
            imagefilledellipse($img, $x, $y, 10, 10, $cyan);
        }
    }

    public function product(string $slug): Response
    {
        $cacheKey = 'og_product_' . $slug;

        $png = Cache::remember($cacheKey, now()->addDays(7), function () use ($slug) {
            $product = Product::where('slug', $slug)->where('activo', true)->first();
            return $this->render($product);
        });

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=604800, immutable',
        ]);
    }

    private function render(?Product $product): string
    {
        $img = imagecreatetruecolor(self::W, self::H);

        $goldDark = imagecolorallocate($img, 0x8a, 0x5a, 0x00);
        $goldLight = imagecolorallocate($img, 0xe6, 0xb8, 0x00);
        $cream = imagecolorallocate($img, 0xfd, 0xf6, 0xe3);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);
        $muted = imagecolorallocate($img, 0x88, 0x88, 0x88);
        $darkText = imagecolorallocate($img, 0x1c, 0x1c, 0x1e);

        // Fondo blanco
        imagefill($img, 0, 0, $white);

        if ($product) {
            // Imagen del producto centrada, encajando en un cuadrado con padding
            $productImage = $this->loadProductImage($product);
            if ($productImage !== null) {
                $boxSize = 1000;
                $boxX = (self::W - $boxSize) / 2;
                $boxY = (self::H - $boxSize) / 2;

                // Dibujar borde sutil del cuadrado
                $borderColor = imagecolorallocate($img, 0xe0, 0xe0, 0xe0);
                imagerectangle($img, (int) $boxX, (int) $boxY, (int) ($boxX + $boxSize), (int) ($boxY + $boxSize), $borderColor);

                // Redimensionar imagen para que encaje (contain)
                $sw = imagesx($productImage);
                $sh = imagesy($productImage);
                $scale = min($boxSize / $sw, $boxSize / $sh);
                $dw = (int) ($sw * $scale);
                $dh = (int) ($sh * $scale);
                $dx = (int) ($boxX + ($boxSize - $dw) / 2);
                $dy = (int) ($boxY + ($boxSize - $dh) / 2);

                imagecopyresampled($img, $productImage, $dx, $dy, 0, 0, $dw, $dh, $sw, $sh);
                imagedestroy($productImage);
            }
        } else {
            $this->drawCentered($img, 'Relojes originales', 56, 500, $darkText, self::FONT_BOLD);
            $this->drawCentered($img, 'InvictaCostaRica.com', 28, 580, $muted, self::FONT_REGULAR);
        }

        ob_start();
        imagepng($img, null, 8);
        $data = ob_get_clean();
        imagedestroy($img);

        return $data;
    }

    private function loadProductImage(?Product $product)
    {
        if (!$product) return null;

        $urls = [$product->getRawOriginal('imagen')];
        foreach ($product->images ?? [] as $img) {
            $urls[] = $img->url;
        }
        // Fallback final: caja del producto (consistente con la página de producto)
        $urls[] = 'caja.webp';

        foreach ($urls as $url) {
            if (!$url) continue;
            $src = $this->loadImageFromUrl($url, $product);
            if ($src) return $src;
        }
        return null;
    }

    private function loadImageFromUrl(string $url, ?Product $product)
    {
        if (str_starts_with($url, 'https://cdn.invictacostarica.com')) {
            $url = str_replace('https://cdn.invictacostarica.com', '', $url);
        } elseif (str_starts_with($url, 'http')) {
            $tmp = @file_get_contents($url);
            if ($tmp !== false) {
                $src = @imagecreatefromstring($tmp);
                return $src ?: null;
            }
            return null;
        }

        $r2 = Storage::disk('r2');

        $clean = ltrim($url, '/');
        if ($r2->exists($clean)) {
            $body = $r2->get($clean);
            $src = @imagecreatefromstring($body);
            if ($src) return $src;
        }

        // Buscar las versiones optimizadas del modelo
        $modelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
        foreach (["relojes/large/{$modelo}.webp", "relojes/medium/{$modelo}.webp", "relojes/{$modelo}.webp"] as $path) {
            if (!$path) continue;
            $p = ltrim($path, '/');
            if (!$r2->exists($p)) continue;
            $body = $r2->get($p);
            $src = @imagecreatefromstring($body);
            if ($src) return $src;
        }
        return null;
    }

    private function copyIntoCircle($source, int $size)
    {
        $sw = imagesx($source);
        $sh = imagesy($source);
        if ($sw <= 0 || $sh <= 0) return null;

        $scale = max($size / $sw, $size / $sh);
        $dw = (int) ($sw * $scale);
        $dh = (int) ($sh * $scale);
        $resized = imagecreatetruecolor($dw, $dh);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $dw, $dh, $sw, $sh);

        $canvas = imagecreatetruecolor($size, $size);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $transparent);

        $dx = (int) (($size - $dw) / 2);
        $dy = (int) (($size - $dh) / 2);
        imagecopy($canvas, $resized, $dx, $dy, 0, 0, $dw, $dh);
        imagedestroy($resized);

        $cx = $size / 2;
        $cy = $size / 2;
        $r = $size / 2;
        $r2 = $r * $r;
        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                $dx2 = ($x + 0.5) - $cx;
                $dy2 = ($y + 0.5) - $cy;
                if ($dx2 * $dx2 + $dy2 * $dy2 > $r2) {
                    imagesetpixel($canvas, $x, $y, $transparent);
                }
            }
        }

        return $canvas;
    }

    private function buildTitle(Product $product): string
    {
        $parts = [];
        $col = $product->coleccion;
        if ($col && strtolower($col) !== 'otros') {
            $parts[] = strtoupper($col);
        }
        $parts[] = $product->modelo;
        if ($product->size) {
            $size = preg_replace('/\s*mm$/i', '', $product->size);
            $parts[] = $size . ' mm';
        }
        return implode(' · ', $parts);
    }

    private function linesFor(string $text, int $maxWidth, int $fontSize): int
    {
        $box = imagettfbbox($fontSize, 0, self::FONT_BOLD, $text);
        $w = $box[2] - $box[0];
        if ($w <= $maxWidth) return 1;
        $words = explode(' ', $text);
        $lines = 1;
        $current = '';
        foreach ($words as $word) {
            $candidate = $current === '' ? $word : $current . ' ' . $word;
            $box = imagettfbbox($fontSize, 0, self::FONT_BOLD, $candidate);
            if ($box[2] - $box[0] > $maxWidth && $current !== '') {
                $lines++;
                $current = $word;
            } else {
                $current = $candidate;
            }
        }
        return $lines;
    }

    private function drawText($img, string $text, int $size, int $y, int $color, string $font, bool $shadow = false): void
    {
        if ($shadow) {
            $shadowColor = imagecolorallocate($img, 0, 0, 0);
            imagettftext($img, $size, 0, 82, $y + 3, $shadowColor, $font, $text);
        }
        imagettftext($img, $size, 0, 80, $y, $color, $font, $text);
    }

    private function drawCentered($img, string $text, int $size, int $y, int $color, string $font): void
    {
        $box = imagettfbbox($size, 0, $font, $text);
        $textWidth = $box[2] - $box[0];
        $x = (int) ((self::W - $textWidth) / 2);
        $shadowColor = imagecolorallocate($img, 0, 0, 0);
        imagettftext($img, $size, 0, $x + 3, $y + 3, $shadowColor, $font, $text);
        imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
    }

    private function drawWrappedText($img, string $text, int $x, int $y, int $maxWidth, int $fontSize, int $color, string $font): void
    {
        $words = explode(' ', $text);
        $line = '';
        $cy = $y;
        foreach ($words as $word) {
            $candidate = $line === '' ? $word : $line . ' ' . $word;
            $box = imagettfbbox($fontSize, 0, $font, $candidate);
            if ($box[2] - $box[0] > $maxWidth && $line !== '') {
                imagettftext($img, $fontSize, 0, $x + 2, $cy + 3, imagecolorallocate($img, 0, 0, 0), $font, $line);
                imagettftext($img, $fontSize, 0, $x, $cy, $color, $font, $line);
                $line = $word;
                $cy += $fontSize + 14;
            } else {
                $line = $candidate;
            }
        }
        if ($line !== '') {
            imagettftext($img, $fontSize, 0, $x + 2, $cy + 3, imagecolorallocate($img, 0, 0, 0), $font, $line);
            imagettftext($img, $fontSize, 0, $x, $cy, $color, $font, $line);
        }
    }
}
