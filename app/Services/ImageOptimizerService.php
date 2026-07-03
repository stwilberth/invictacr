<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ImageOptimizerService
{
    const int THUMB_WIDTH = 200;
    const int MEDIUM_WIDTH = 600;

    public function needsOptimization(Product $product): bool
    {
        if (!$product->imagen) {
            return false;
        }

        $modelo = $this->getModelo($product);
        if (!$modelo) {
            return false;
        }

        $disk = Storage::disk('public');

        return !$disk->exists("relojes/thumbs/{$modelo}.webp")
            || !$disk->exists("relojes/medium/{$modelo}.webp");
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
            'thumb_size' => 0,
            'medium_size' => 0,
            'original_size' => 0,
        ];

        try {
            $sourcePath = $this->getSourcePath($product);
            if (!$sourcePath) {
                $result['error'] = 'No se encontró la imagen origen';
                return $result;
            }

            $disk = Storage::disk('public');
            $fullPath = storage_path("app/public/{$sourcePath}");

            if (!file_exists($fullPath)) {
                $result['error'] = 'Archivo no existe en disco';
                return $result;
            }

            [$width, $height] = @getimagesize($fullPath);
            if (!$width || !$height) {
                $result['error'] = 'No se pudo leer la imagen';
                return $result;
            }

            $modelo = $this->getModelo($product);
            if (!$modelo) {
                $result['error'] = 'No se pudo determinar el modelo';
                return $result;
            }

            $originalSize = filesize($fullPath);
            $result['original_size'] = $originalSize;

            $disk->makeDirectory('relojes/thumbs');
            $disk->makeDirectory('relojes/medium');

            $sourceImage = $this->createImageFromFile($fullPath);
            if (!$sourceImage) {
                $result['error'] = 'No se pudo decodificar la imagen';
                return $result;
            }

            $thumbPath = "relojes/thumbs/{$modelo}.webp";
            $thumbResult = $this->resizeToWebP($sourceImage, $fullPath, $thumbPath, self::THUMB_WIDTH);
            if ($thumbResult) {
                $result['thumb'] = true;
                $result['thumb_size'] = $thumbResult;
            }

            $mediumPath = "relojes/medium/{$modelo}.webp";
            $mediumResult = $this->resizeToWebP($sourceImage, $fullPath, $mediumPath, self::MEDIUM_WIDTH);
            if ($mediumResult) {
                $result['medium'] = true;
                $result['medium_size'] = $mediumResult;
            }

            imagedestroy($sourceImage);

            $result['success'] = $result['thumb'] || $result['medium'];
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function optimizeAll(?callable $onProgress = null): array
    {
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

    public function getImageUrls(Product $product): array
    {
        $modelo = $this->getModelo($product);
        if (!$modelo) {
            return [
                'original' => $product->imagen,
                'medium' => $product->imagen,
                'thumb' => $product->imagen,
            ];
        }

        $disk = Storage::disk('public');
        $original = $product->imagen;
        $medium = $disk->exists("relojes/medium/{$modelo}.webp")
            ? "/storage/relojes/medium/{$modelo}.webp"
            : $original;
        $thumb = $disk->exists("relojes/thumbs/{$modelo}.webp")
            ? "/storage/relojes/thumbs/{$modelo}.webp"
            : $original;

        return [
            'original' => $original,
            'medium' => $medium,
            'thumb' => $thumb,
        ];
    }

    private function getModelo(Product $product): ?string
    {
        $imagen = $product->imagen;
        if (!$imagen) {
            return null;
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

        if (str_starts_with($imagen, '/storage/')) {
            return substr($imagen, 9);
        }

        if (str_starts_with($imagen, '/assets/')) {
            $relative = ltrim($imagen, '/');
            $publicPath = public_path($relative);
            if (file_exists($publicPath)) {
                return null;
            }
        }

        $modelo = $this->getModelo($product);
        if (!$modelo) {
            return null;
        }

        $disk = Storage::disk('public');
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            if ($disk->exists("relojes/{$modelo}.{$ext}")) {
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

    private function resizeToWebP(\GdImage $source, string $sourcePath, string $targetPath, int $maxWidth): ?int
    {
        $disk = Storage::disk('public');
        $fullTarget = storage_path("app/public/{$targetPath}");

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

        imagewebp($resampled, $fullTarget, 80);
        imagedestroy($resampled);

        if (!file_exists($fullTarget)) {
            return null;
        }

        @chmod($fullTarget, 0775);

        return filesize($fullTarget);
    }
}
