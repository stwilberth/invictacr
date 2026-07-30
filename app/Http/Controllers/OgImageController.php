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
        $muted = imagecolorallocate($img, 0xcc, 0xcc, 0xcc);

        for ($y = 0; $y < self::H; $y++) {
            $t = $y / self::H;
            $r = (int) (0x8a + (0xe6 - 0x8a) * $t);
            $g = (int) (0x5a + (0xb8 - 0x5a) * $t);
            $b = 0;
            $color = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, self::W, $y, $color);
        }

        $this->drawCentered($img, 'INVICTA COSTA RICA', 44, 90, $white, self::FONT_BOLD);

        if ($product) {
            $coleccion = $product->coleccion && strtolower($product->coleccion) !== 'otros' ? strtoupper($product->coleccion) : 'RELOJ';
            $this->drawCentered($img, $coleccion, 30, 150, $goldLight, self::FONT_BOLD);

            $circleSize = 560;
            $circleCy = (int) (self::H * 0.44);
            imagefilledellipse($img, (int) (self::W / 2), $circleCy, $circleSize, $circleSize, $cream);
            imageellipse($img, (int) (self::W / 2), $circleCy, $circleSize, $circleSize, $goldDark);

            $productImage = $this->loadProductImage($product);
            if ($productImage !== null) {
                $inner = 500;
                $circle = $this->copyIntoCircle($productImage, $inner);
                if ($circle !== null) {
                    $dx = (int) (self::W / 2 - $inner / 2);
                    $dy = (int) ($circleCy - $inner / 2);
                    imagecopy($img, $circle, $dx, $dy, 0, 0, $inner, $inner);
                    imagedestroy($circle);
                }
                imagedestroy($productImage);
            }

            $titleSize = 62;
            $title = $product->modelo;
            if ($product->size) {
                $size = preg_replace('/\s*mm$/i', '', $product->size);
                $title .= ' · ' . $size . ' mm';
            }
            $this->drawCentered($img, $title, $titleSize, 790, $white, self::FONT_BOLD);

            $price = '₡' . number_format((float) $product->price_after_discount, 0);
            $this->drawCentered($img, $price, 72, 880, $white, self::FONT_BOLD);

            $this->drawCentered($img, 'Envío gratis en GAM · WhatsApp 8671-1422', 24, 950, $muted, self::FONT_REGULAR);
            $this->drawCentered($img, 'InvictaCostaRica.com', 24, 990, $muted, self::FONT_REGULAR);
        } else {
            $this->drawCentered($img, 'Relojes originales', 62, 500, $white, self::FONT_BOLD);
            $this->drawCentered($img, 'InvictaCostaRica.com', 30, 580, $muted, self::FONT_BOLD);
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
        $url = $product->getRawOriginal('imagen');
        if (!$url) return null;

        if (str_starts_with($url, 'https://cdn.invictacostarica.com')) {
            $url = str_replace('https://cdn.invictacostarica.com', '', $url);
        } elseif (str_starts_with($url, 'http')) {
            $tmp = @file_get_contents($url);
            if ($tmp !== false) {
                $src = @imagecreatefromstring($tmp);
                return $src ?: null;
            }
        }

        $r2 = Storage::disk('r2');
        $modelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
        foreach (["relojes/large/{$modelo}.webp", "relojes/medium/{$modelo}.webp", "relojes/{$modelo}.webp", $url] as $path) {
            if (!$path) continue;
            $clean = ltrim($path, '/');
            if (!$r2->exists($clean)) continue;
            $body = $r2->get($clean);
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
        $x = (self::W - $textWidth) / 2;
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
