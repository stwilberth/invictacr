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

    private const THEMES = [
        // Mismo azul del canva manual (default en el JS).
        'blue' => [
            'dark' => [0x0b, 0x24, 0x47],
            'light' => [0x1a, 0x5f, 0xb4],
            'cream' => [0xea, 0xf2, 0xfb],
            'text' => [0x2b, 0x2b, 0x2b],
        ],
        'gold' => [
            'dark' => [0x8a, 0x5a, 0x00],
            'light' => [0xe6, 0xb8, 0x00],
            'cream' => [0xfd, 0xf6, 0xe3],
            'text' => [0x2b, 0x2b, 0x2b],
        ],
    ];

    public function generate(Product $product, string $theme = 'blue'): string
    {
        return $this->render($product, self::THEMES[$theme] ?? self::THEMES['blue']);
    }

    private function render(Product $product, array $t): string
    {
        $img = imagecreatetruecolor(self::W, self::H);

        $themeDark = imagecolorallocate($img, $t['dark'][0], $t['dark'][1], $t['dark'][2]);
        $themeLight = imagecolorallocate($img, $t['light'][0], $t['light'][1], $t['light'][2]);
        $cream = imagecolorallocate($img, $t['cream'][0], $t['cream'][1], $t['cream'][2]);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);
        $darkText = imagecolorallocate($img, 0x1c, 0x1c, 0x1e);
        $tagGold = imagecolorallocate($img, 0xe6, 0xb8, 0x00);
        $specText = imagecolorallocate($img, $t['text'][0], $t['text'][1], $t['text'][2]);
        $badgeRed = imagecolorallocate($img, 0xc0, 0x21, 0x2b);

        // Fondo: lado izquierdo gradiente del tema, lado derecho crema (split diagonal)
        $splitX = self::W * 0.5;
        $offsetTop = 120;
        $offsetBottom = -120;

        // Relleno base con crema (lado derecho)
        imagefilledrectangle($img, 0, 0, self::W, self::H, $cream);

        // Lado izquierdo: gradiente con borde diagonal
        for ($y = 0; $y < self::H; $y++) {
            $tY = $y / self::H;
            $r = (int) ($t['dark'][0] + ($t['light'][0] - $t['dark'][0]) * $tY);
            $g = (int) ($t['dark'][1] + ($t['light'][1] - $t['dark'][1]) * $tY);
            $b = (int) ($t['dark'][2] + ($t['light'][2] - $t['dark'][2]) * $tY);
            $color = imagecolorallocate($img, $r, $g, $b);

            // Calcular el borde diagonal en este Y
            $splitAtY = $splitX + $offsetTop + (($offsetBottom - $offsetTop) * $tY);
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
        imageellipse($img, (int) $cx, (int) $cy, $r * 2, $r * 2, $themeDark);

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
            $this->drawRightText($img, $spec, 32, (int) ($specY + $i * 70), $specText, self::FONT_REGULAR);
        }

        // Bloque de precio: badge rojo con pestaña dorada sobre el borde superior
        $badgeX = 40;
        $badgeW = 520;
        $badgeBottom = self::H - 100;

        $badgeH = 160;
        $badgeY = $badgeBottom - $badgeH;

        $this->roundRect($img, $badgeX, $badgeY, $badgeW, $badgeH, (int) ($badgeH / 2), $badgeRed);

        $tagW = 300;
        $tagH = 44;
        $tagX = (int) ($badgeX + ($badgeW - $tagW) / 2);
        $tagY = (int) ($badgeY - $tagH / 2);

        $this->roundRect($img, $tagX, $tagY, $tagW, $tagH, (int) ($tagH / 2), $tagGold);
        $this->drawCenteredText($img, 'ENVÍO GRATIS', 20, $this->vCenterY($tagY, $tagH, 20, self::FONT_BOLD, 'ENVÍO GRATIS'), $darkText, self::FONT_BOLD, $tagX + $tagW / 2);

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
        $this->drawCenteredText($img, $price, $priceSize, $this->vCenterY($badgeY, $badgeH, $priceSize, self::FONT_BOLD, $price), $white, self::FONT_BOLD, $badgeX + $badgeW / 2);

        // WhatsApp con icono (igual que el canva manual: círculo verde + número)
        $this->drawWhatsApp($img, '8671-1422', $darkText);

        // Website
        $this->drawRightText($img, 'invictaCostaRica.com', 38, self::H - 40, $darkText, self::FONT_BOLD);

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

        // Contain: el reloj completo debe verse dentro del círculo, sin recortes.
        $scale = min($size / $sw, $size / $sh);
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
        imagettftext($img, $size, 0, 60, $y, $color, $font, $text);
    }

    private function drawRightText($img, string $text, int $size, int $y, $color, string $font): void
    {
        $box = imagettfbbox($size, 0, $font, $text);
        $textWidth = $box[2] - $box[0];
        $x = self::W - 35 - $textWidth;
        imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
    }

    /**
     * Número de WhatsApp con el logo real (public/images/whatsapp-icon.png,
     * rasterizado desde whatsapp.svg que usa el canva manual), recortado en
     * círculo igual que el canva manual de campañas. Si el PNG falta, usa un
     * círculo verde dibujado como respaldo.
     */
    private function drawWhatsApp($img, string $text, $color): void
    {
        $size = 32;
        $box = imagettfbbox($size, 0, self::FONT_BOLD, $text);
        $textWidth = $box[2] - $box[0];
        $rightX = self::W - 35;
        $baseline = 70;
        $x = (int) ($rightX - $textWidth);
        imagettftext($img, $size, 0, $x, $baseline, $color, self::FONT_BOLD, $text);

        $gap = 20;
        $r = 28;
        $cx = (int) ($x - $gap - $r);
        // Centro óptico del texto (baseline menos ~mitad de altura de mayúsculas).
        $cy = $baseline - 11;

        if ($this->drawWhatsAppIcon($img, $cx, $cy, $r)) {
            return;
        }

        // Respaldo sin asset: círculo verde + auricular.
        $green = imagecolorallocate($img, 0x25, 0xD3, 0x66);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);
        imagefilledellipse($img, $cx, $cy, $r * 2, $r * 2, $green);

        $glyph = '✆';
        $gSize = 30;
        $gbox = imagettfbbox($gSize, 0, self::FONT_REGULAR, $glyph);
        $gw = $gbox[2] - $gbox[0];
        $gh = $gbox[1] - $gbox[7];
        $gx = (int) ($cx - $gw / 2 - $gbox[0]);
        $gy = (int) ($cy + $gh / 2);
        imagettftext($img, $gSize, 0, $gx, $gy, $white, self::FONT_REGULAR, $glyph);
    }

    /**
     * Pega el logo de WhatsApp recortado en círculo de radio $r centrado en
     * ($cx, $cy). Devuelve false si el asset no se pudo cargar.
     */
    private function drawWhatsAppIcon($img, int $cx, int $cy, int $r): bool
    {
        $src = @imagecreatefrompng(public_path('images/whatsapp-icon.png'));
        if ($src === false) {
            return false;
        }

        // Escalar de más y recortar al círculo (igual que el clip del canva
        // manual: el icono se dibuja a 2.5x dentro del círculo).
        $cell = $r * 2;
        $big = (int) ($cell * 1.25);
        $tmp = imagecreatetruecolor($big, $big);
        imagealphablending($tmp, false);
        imagesavealpha($tmp, true);
        $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $transparent);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $big, $big, imagesx($src), imagesy($src));
        imagedestroy($src);

        // Recorte circular.
        $c = $big / 2;
        $r2 = ($cell / 2) * ($cell / 2);
        for ($px = 0; $px < $big; $px++) {
            for ($py = 0; $py < $big; $py++) {
                $dx = ($px + 0.5) - $c;
                $dy = ($py + 0.5) - $c;
                if ($dx * $dx + $dy * $dy > $r2) {
                    imagesetpixel($tmp, $px, $py, $transparent);
                }
            }
        }

        imagealphablending($img, true);
        $dx = (int) ($cx - $c);
        $dy = (int) ($cy - $c);
        imagecopy($img, $tmp, $dx, $dy, 0, 0, $big, $big);
        imagedestroy($tmp);

        return true;
    }

    private function drawCenteredText($img, string $text, int $size, int $y, $color, string $font, ?int $centerX = null): void
    {
        $box = imagettfbbox($size, 0, $font, $text);
        $textWidth = $box[2] - $box[0];
        $x = $centerX !== null ? $centerX - $textWidth / 2 : (self::W - $textWidth) / 2;
        imagettftext($img, $size, 0, (int) $x, $y, $color, $font, $text);
    }

    /**
     * Baseline Y para centrar verticalmente un texto dentro de un bloque.
     */
    private function vCenterY(int $y, int $h, int $size, string $font, string $text): int
    {
        $box = imagettfbbox($size, 0, $font, $text);
        $textH = $box[1] - $box[7];
        return (int) ($y + ($h + $textH) / 2);
    }
}
