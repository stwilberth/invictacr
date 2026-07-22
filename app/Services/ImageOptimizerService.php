<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ImageOptimizerService
{
    const int THUMB_WIDTH = 200;
    const int MEDIUM_WIDTH = 600;
    const int LARGE_WIDTH = 1200;
    const int THUMB_QUALITY = 80;
    const int MEDIUM_QUALITY = 80;
    const int LARGE_QUALITY = 85;

    public function needsOptimization(Product $product): bool
    {
        if (!$product->imagen) {
            return false;
        }

        $modelo = $this->getModelo($product);
        if (!$modelo) {
            return false;
        }

        $r2 = Storage::disk('r2');

        return !$r2->exists("relojes/thumbs/{$modelo}.webp")
            || !$r2->exists("relojes/medium/{$modelo}.webp")
            || !$r2->exists("relojes/large/{$modelo}.webp");
    }

    public function getStats(): array
    {
        $products = Product::whereNotNull('imagen')->get();

        $total = 0;
        $optimized = 0;
        $unoptimized = 0;

        foreach ($products as $product) {
            if (!$product->imagen) {
                continue;
            }
            $total++;
            if (!$this->needsOptimization($product)) {
                $optimized++;
            } else {
                $unoptimized++;
            }
        }

        return [
            'total' => $total,
            'optimized' => $optimized,
            'unoptimized' => $unoptimized,
        ];
    }

    public function getUnoptimizedProducts(): array
    {
        $products = Product::whereNotNull('imagen')->get();
        $result = [];

        foreach ($products as $product) {
            if ($this->needsOptimization($product)) {
                $result[] = [
                    'id' => $product->id,
                    'modelo' => $product->modelo,
                    'title' => $product->title,
                    'imagen' => $product->imagen,
                ];
            }
        }

        return $result;
    }

    public function optimizeProduct(Product $product): array
    {
        $result = [
            'success' => false,
            'modelo' => $product->modelo,
            'error' => null,
            'thumb' => false,
            'medium' => false,
            'large' => false,
            'thumb_size' => 0,
            'medium_size' => 0,
            'large_size' => 0,
            'original_size' => 0,
        ];

        try {
            $r2 = Storage::disk('r2');
            $sourcePath = $this->getSourcePath($product);
            if (!$sourcePath) {
                $result['error'] = 'No se encontró la imagen en R2';
                return $result;
            }

            // Descargar imagen de R2 a temporal
            $tempPath = storage_path("app/temp/" . basename($sourcePath));
            $tempDir = dirname($tempPath);
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            
            file_put_contents($tempPath, $r2->get($sourcePath));

            if (!file_exists($tempPath)) {
                $result['error'] = 'No se pudo descargar la imagen de R2';
                return $result;
            }

            [$width, $height] = @getimagesize($tempPath);
            if (!$width || !$height) {
                @unlink($tempPath);
                $result['error'] = 'No se pudo leer la imagen';
                return $result;
            }

            $modelo = $this->getModelo($product);
            if (!$modelo) {
                @unlink($tempPath);
                $result['error'] = 'No se pudo determinar el modelo';
                return $result;
            }

            $originalSize = filesize($tempPath);
            $result['original_size'] = $originalSize;

            $sourceImage = $this->createImageFromFile($tempPath);
            if (!$sourceImage) {
                @unlink($tempPath);
                $result['error'] = 'No se pudo decodificar la imagen';
                return $result;
            }

            $sizes = [
                'thumb' => ['width' => self::THUMB_WIDTH, 'quality' => self::THUMB_QUALITY, 'dir' => 'thumbs'],
                'medium' => ['width' => self::MEDIUM_WIDTH, 'quality' => self::MEDIUM_QUALITY, 'dir' => 'medium'],
                'large' => ['width' => self::LARGE_WIDTH, 'quality' => self::LARGE_QUALITY, 'dir' => 'large'],
            ];

            foreach ($sizes as $key => $cfg) {
                $tempTarget = storage_path("app/temp/{$modelo}_{$key}.webp");
                $webpSize = $this->resizeToWebP($sourceImage, $tempPath, $tempTarget, $cfg['width'], $cfg['quality']);
                
                if ($webpSize) {
                    $r2->put("relojes/{$cfg['dir']}/{$modelo}.webp", file_get_contents($tempTarget), 'public');
                    $result[$key] = true;
                    $result["{$key}_size"] = $webpSize;
                    @unlink($tempTarget);
                }
            }

            imagedestroy($sourceImage);
            @unlink($tempPath);

            $result['success'] = $result['thumb'] || $result['medium'] || $result['large'];
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function optimizeAll(?callable $onProgress = null): array
    {
        set_time_limit(0);
        $products = Product::whereNotNull('imagen')->get();
        $results = [];
        $total = count($products);
        $processed = 0;
        $successCount = 0;
        $failCount = 0;

        foreach ($products as $product) {
            $result = $this->optimizeProduct($product);
            $results[] = $result;

            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
            }

            $processed++;

            if ($onProgress) {
                $onProgress($processed, $total, $result);
            }
        }

        return [
            'results' => $results,
            'total' => $total,
            'success' => $successCount,
            'failed' => $failCount,
            'errors' => collect($results)->where('success', false)->pluck('error', 'modelo')->toArray(),
        ];
    }

    public function getProductImageInfo(Product $product): array
    {
        $modelo = $this->getModelo($product);
        $r2 = Storage::disk('r2');

        $originalPath = $product->getRawOriginal('imagen');
        $originalInfo = null;
        if ($originalPath) {
            $r2Path = str_starts_with($originalPath, '/storage/') ? substr($originalPath, 9) : $originalPath;
            if ($r2->exists($r2Path)) {
                $originalInfo = [
                    'exists' => true,
                    'size' => $r2->size($r2Path),
                    'width' => null,
                    'height' => null,
                ];
            }
        }

        $sizeDirs = ['large' => 'large', 'medium' => 'medium', 'thumb' => 'thumbs'];
        $sizes = ['large' => null, 'medium' => null, 'thumb' => null];
        foreach ($sizes as $size => &$info) {
            $dir = $sizeDirs[$size];
            if ($modelo && $r2->exists("relojes/{$dir}/{$modelo}.webp")) {
                $info = [
                    'exists' => true,
                    'size' => $r2->size("relojes/{$dir}/{$modelo}.webp"),
                    'width' => null,
                    'height' => null,
                ];
            } else {
                $info = ['exists' => false, 'size' => null, 'width' => null, 'height' => null];
            }
        }
        unset($info);

        return [
            'id' => $product->id,
            'modelo' => $product->modelo,
            'title' => $product->title,
            'imagen' => $product->imagen,
            'needs_optimization' => $this->needsOptimization($product),
            'original' => $originalInfo,
            'large' => $sizes['large'],
            'medium' => $sizes['medium'],
            'thumb' => $sizes['thumb'],
        ];
    }

    public function getImageUrls(Product $product): array
    {
        $modelo = $this->getModelo($product);
        if (!$modelo) {
            return [
                'original' => $product->imagen,
                'large' => $product->imagen,
                'medium' => $product->imagen,
                'thumb' => $product->imagen,
            ];
        }

        $r2 = Storage::disk('r2');
        $cdnBase = 'https://cdn.invictacostarica.com';
        $original = $product->imagen;
        $large = $r2->exists("relojes/large/{$modelo}.webp")
            ? "{$cdnBase}/relojes/large/{$modelo}.webp"
            : $original;
        $medium = $r2->exists("relojes/medium/{$modelo}.webp")
            ? "{$cdnBase}/relojes/medium/{$modelo}.webp"
            : $original;
        $thumb = $r2->exists("relojes/thumbs/{$modelo}.webp")
            ? "{$cdnBase}/relojes/thumbs/{$modelo}.webp"
            : $original;

        return [
            'original' => $original,
            'large' => $large,
            'medium' => $medium,
            'thumb' => $thumb,
        ];
    }

    private function getModelo(Product $product): ?string
    {
        $imagen = $product->getRawOriginal('imagen');

        if (!$imagen) {
            return preg_replace('/^invicta-/i', '', $product->modelo ?? '');
        }

        // Si es URL CDN, extraer la ruta relativa
        if (str_starts_with($imagen, 'https://cdn.invictacostarica.com')) {
            $imagen = str_replace('https://cdn.invictacostarica.com', '', $imagen);
        }

        if (str_starts_with($imagen, '/storage/relojes/')) {
            $filename = basename($imagen);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            return $ext ? substr($filename, 0, -(strlen($ext) + 1)) : $filename;
        }

        return preg_replace('/^invicta-/i', '', $product->modelo ?? '');
    }

    private function getSourcePath(Product $product): ?string
    {
        $imagen = $product->imagen;
        if (!$imagen) {
            return null;
        }

        // Si es URL CDN, extraer la ruta relativa
        if (str_starts_with($imagen, 'https://cdn.invictacostarica.com')) {
            $imagen = str_replace('https://cdn.invictacostarica.com', '', $imagen);
        }

        if (str_starts_with($imagen, '/storage/')) {
            return substr($imagen, 9); // Quitar /storage/
        }

        // Si es URL externa (invictawatch CDN), no tiene fuente local
        if (str_starts_with($imagen, 'http')) {
            return null;
        }

        $modelo = $this->getModelo($product);
        if (!$modelo) {
            return null;
        }

        $r2 = Storage::disk('r2');
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            if ($r2->exists("relojes/{$modelo}.{$ext}")) {
                return "relojes/{$modelo}.{$ext}";
            }
        }

        return null;
    }

    private function createImageFromFile(string $path): ?\GdImage
    {
        $info = @getimagesize($path);
        if (!$info) {
            return null;
        }

        return match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default => null,
        };
    }

    private function resizeToWebP(\GdImage $source, string $sourcePath, string $targetPath, int $maxWidth, int $quality = 80): ?int
    {
        [$origWidth, $origHeight] = getimagesize($sourcePath);
        if (!$origWidth || !$origHeight) {
            return null;
        }

        if ($origWidth <= $maxWidth) {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        } else {
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) round($origHeight * $ratio);
        }

        $resampled = imagecreatetruecolor($newWidth, $newHeight);
        if (!$resampled) {
            return null;
        }

        imagealphablending($resampled, false);
        imagesavealpha($resampled, true);

        imagecopyresampled($resampled, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        imagewebp($resampled, $targetPath, $quality);
        imagedestroy($resampled);

        if (!file_exists($targetPath)) {
            return null;
        }

        return filesize($targetPath);
    }
}
