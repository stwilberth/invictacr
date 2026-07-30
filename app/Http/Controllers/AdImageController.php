<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AdImageController extends Controller
{
    private const W = 1080;
    private const H = 1350;
    private const FONT_BOLD = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
    private const FONT_REGULAR = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';

    public function generate(Product $product): string
    {
        return $this->render($product);
    }

    private function render(Product $product): string
    {
        $img = imagecreatetruecolor(self::W, self::H);

        $goldDark = imagecolorallocate($img, 0x8a, 0x5a, 0x00);
        $goldLight = imagecolorallocate($img, 0xe6, 0xb8, 0x00);
        $cream = imagecolorallocate($img, 0xfd, 0xf6, 0xe3);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);
        $darkText = imagecolorallocate($img, 0x1c, 0x1c, 0x1e);
        $badgeRed = imagecolorallocate($img, 0xc0, 0x21, 0x2b);

        // Fondo: lado izquierdo gradiente dorado, lado derecho crema (split diagonal)
        $splitX = self::W * 0.5;
        $offsetTop = 120;
        $offsetBottom = -120;

        // Relleno base con crema (lado derecho)
        imagefilledrectangle($img, 0, 0, self::W, self::H, $cream);

        // Lado izquierdo: gradiente dorado con borde diagonal
        for ($y = 0; $y < self::H; $y++) {
            $t = $y / self::H;
            $r = (int) (0x8a + (0xe6 - 0x8a) * $t);
            $g = (int) (0x5a + (0xb8 - 0x5a) * $t);
            $b = 0;
            $color = imagecolorallocate($img, $r, $g, $b);

            // Calcular el borde diagonal en este Y
            $splitAtY = $splitX + $offsetTop + (($offsetBottom - $offsetTop) * $t);
            for ($x = 0; $x < (int) $splitAtY; $x++) {
                if ($x < self::W) {
                    imagesetpixel($img, $x, $y, $color);
                }
            }
        }

        // Círculo central con fondo blanco
        $cx = self::W / 2;
        $cy = self::H * 0.48;
        $r = 450;
        imagefilledellipse($img, (int) $cx, (int) $cy, $r * 2, $r * 2, $white);
        imageellipse($img, (int) $cx, (int) $cy, $r * 2, $r * 2, $goldDark);

        // Cargar imagen del producto
        $productImage = $this->loadProductImage($product);
        if ($productImage !== null) {
            $inner = (int) ($r * 1.8);
            $circle = $this->copyIntoCircle($productImage, $inner);
            if ($circle !== null) {
                $dx = (int) ($cx - $inner / 2);
                $dy = (int) ($cy - $inner / 2);
                imagecopy($img, $circle, $dx, $dy, 0, 0, $inner, $inner);
                imagedestroy($circle);
            }
            imagedestroy($productImage);
        }

        // Título (auto-reducir si no cabe)
        $title = 'INVICTA ' . strtoupper($product->coleccion ?? $product->modelo ?? '');
        $titleSize = 72;
        $maxTitleWidth = self::W - 500; // dejar espacio para el WhatsApp
        $titleBox = imagettfbbox($titleSize, 0, self::FONT_BOLD, $title);
        $titleWidth = $titleBox[2] - $titleBox[0];
        while ($titleWidth > $maxTitleWidth && $titleSize > 36) {
            $titleSize -= 4;
            $titleBox = imagettfbbox($titleSize, 0, self::FONT_BOLD, $title);
            $titleWidth = $titleBox[2] - $titleBox[0];
        }
        $this->drawText($img, $title, $titleSize, 70, $white, self::FONT_BOLD);

        // Modelo
        $modelCode = $product->codigo_comercial ?? $product->modelo;
        $this->drawText($img, $modelCode, 30, 70 + $titleSize + 24, $white, self::FONT_REGULAR);

        // Especificaciones (lado derecho, más grandes)
        $specs = [];
        if ($product->size) {
            $size = preg_replace('/\s*mm$/i', '', $product->size);
            $specs[] = $size . ' mm';
        }
        if ($product->resistencia_agua) {
            $specs[] = $product->resistencia_agua . ' m';
        }
        if ($product->tipo_movimiento) {
            $specs[] = ucfirst($product->tipo_movimiento);
        }
        if ($product->brazalete) {
            $specs[] = $product->brazalete;
        }

        $specY = $cy - ((count($specs) - 1) * 35) - 30 + 500;
        foreach ($specs as $i => $spec) {
            $this->drawRightText($img, $spec, 32, (int) ($specY + $i * 70), $darkText, self::FONT_REGULAR);
        }

        // Badge de precio (más compacto)
        $badgePadX = 40;
        $badgeY = self::H - 260;
        $badgeW = 480;
        $badgeH = 150;
        $badgeRadius = 75;
        $this->roundRect($img, $badgePadX, $badgeY, $badgeW, $badgeH, $badgeRadius, $badgeRed);

        // Badge de envío gratis (más grande y centrado)
        $tagH = 56;
        $tagW = 360;
        $this->roundRect($img, $badgePadX + 30, $badgeY - $tagH / 2, $tagW, $tagH, 28, $goldLight);
        $this->drawCenteredText($img, 'ENVÍO GRATIS', 26, (int) ($badgeY), $darkText, self::FONT_BOLD, $badgePadX + 30 + $tagW / 2);

        // Precio (auto-reducir si no cabe)
        $price = '₡' . number_format((float) $product->price_after_discount, 0);
        $priceSize = 64;
        $priceBox = imagettfbbox($priceSize, 0, self::FONT_BOLD, $price);
        $priceWidth = $priceBox[2] - $priceBox[0];
        while ($priceWidth > $badgeW - 60 && $priceSize > 40) {
            $priceSize -= 4;
            $priceBox = imagettfbbox($priceSize, 0, self::FONT_BOLD, $price);
            $priceWidth = $priceBox[2] - $priceBox[0];
        }
        $this->drawCenteredText($img, $price, $priceSize, (int) ($badgeY + $badgeH / 2 + 20), $white, self::FONT_BOLD, $badgePadX + $badgeW / 2);

        // WhatsApp (más pequeño y con icono)
        $this->drawRightText($img, '8671-1422', 32, 70, $darkText, self::FONT_BOLD);

        // Website
        $this->drawRightText($img, 'INVICTACR.COM', 38, self::H - 40, $darkText, self::FONT_BOLD);

        ob_start();
        imagepng($img, null, 8);
        $data = ob_get_clean();
        imagedestroy($img);

        return $data;
    }

    private function loadProductImage(Product $product)
    {
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

    private function roundRect($img, int $x, int $y, int $w, int $h, int $r, $color): void
    {
        imagefilledellipse($img, $x + $r, $y + $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x + $w - $r, $y + $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x + $r, $y + $h - $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x + $w - $r, $y + $h - $r, $r * 2, $r * 2, $color);
        imagefilledrectangle($img, $x + $r, $y, $x + $w - $r, $y + $h, $color);
        imagefilledrectangle($img, $x, $y + $r, $x + $w, $y + $h - $r, $color);
    }

    private function drawText($img, string $text, int $size, int $y, $color, string $font): void
    {
        $shadowColor = imagecolorallocate($img, 0, 0, 0);
        imagettftext($img, $size, 0, 62, $y + 3, $shadowColor, $font, $text);
        imagettftext($img, $size, 0, 60, $y, $color, $font, $text);
    }

    private function drawRightText($img, string $text, int $size, int $y, $color, string $font): void
    {
        $box = imagettfbbox($size, 0, $font, $text);
        $textWidth = $box[2] - $box[0];
        $x = self::W - 35 - $textWidth;
        $shadowColor = imagecolorallocate($img, 0, 0, 0);
        imagettftext($img, $size, 0, $x + 3, $y + 3, $shadowColor, $font, $text);
        imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
    }

    private function drawCenteredText($img, string $text, int $size, int $y, $color, string $font, ?int $centerX = null): void
    {
        $box = imagettfbbox($size, 0, $font, $text);
        $textWidth = $box[2] - $box[0];
        $x = $centerX !== null ? $centerX - $textWidth / 2 : (self::W - $textWidth) / 2;
        $shadowColor = imagecolorallocate($img, 0, 0, 0);
        imagettftext($img, $size, 0, (int) ($x + 3), $y + 3, $shadowColor, $font, $text);
        imagettftext($img, $size, 0, (int) $x, $y, $color, $font, $text);
    }
}
