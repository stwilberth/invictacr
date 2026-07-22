<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixImages extends Command
{
    protected $signature = "invicta:fix-images {--dry-run : Solo mostrar los cambios, no escribir}";

    protected $description = "Pobla el campo imagen y la tabla product_images desde archivos existentes en disco";

    public function handle(): int
    {
        $dry = (bool) $this->option("dry-run");

        $this->fixMainImages($dry);
        $this->newLine();
        $this->fixExtraImages($dry);

        return 0;
    }

    private function fixMainImages(bool $dry): void
    {
        $products = Product::whereNull("imagen")->get();
        $r2 = Storage::disk("r2");

        $this->info("=== Imágenes principales ===");
        $this->info("Productos sin imagen: {$products->count()}");
        $this->newLine();

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $updated = 0;
        $notFound = 0;
        $skipped = 0;

        foreach ($products as $product) {
            $modelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
            if (!$modelo) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $filename = null;
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                if ($r2->exists("relojes/{$modelo}.{$ext}")) {
                    $filename = "{$modelo}.{$ext}";
                    break;
                }
            }

            if (!$filename) {
                $notFound++;
                $bar->advance();
                continue;
            }

            $path = "/storage/relojes/{$filename}";

            if (!$dry) {
                $product->imagen = $path;
                $product->save();
            }

            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ["Estado", "Cantidad"],
            [
                ["Actualizadas", $updated],
                ["Sin archivo en R2", $notFound],
                ["Sin modelo", $skipped],
            ]
        );
    }

    private function fixExtraImages(bool $dry): void
    {
        $r2 = Storage::disk("r2");
        $files = $r2->files("relojes");
        $extraPattern = '/^relojes\/(\d+)_(\d+)\.(jpg|jpeg|png|webp)$/';

        $extraGroups = [];
        foreach ($files as $file) {
            if (!preg_match($extraPattern, $file, $m)) {
                continue;
            }
            $modelo = $m[1];
            $order = (int) $m[2];
            $ext = $m[3];
            $baseName = "{$modelo}_{$order}";

            if (!isset($extraGroups[$modelo]) || $order > $extraGroups[$modelo]['maxOrder']) {
                $extraGroups[$modelo]['maxOrder'] = $order;
            }
            if (!isset($extraGroups[$modelo]['files'][$order]) || $ext !== 'webp') {
                $extraGroups[$modelo]['files'][$order] = [
                    'path' => "/storage/relojes/{$baseName}.{$ext}",
                    'ext' => $ext,
                ];
            }
        }

        $inserted = 0;
        $bar = $this->output->createProgressBar(count($extraGroups));
        $bar->start();

        ksort($extraGroups);
        foreach ($extraGroups as $modelo => $group) {
            $product = Product::where("modelo", $modelo)
                ->orWhere("modelo", "invicta-{$modelo}")
                ->orWhere("modelo", "INVICTA-{$modelo}")
                ->first();

            if (!$product) {
                $bar->advance();
                continue;
            }

            if (!$dry) {
                $product->images()->delete();
            }

            ksort($group['files']);
            $order = 0;
            foreach ($group['files'] as $info) {
                if (!$dry) {
                    $product->images()->create([
                        'url' => $info['path'],
                        'order' => $order,
                        'type' => 'image',
                    ]);
                }
                $order++;
                $inserted++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ["Ítem", "Cantidad"],
            [
                ["Productos con imágenes extra", count($extraGroups)],
                ["Imágenes insertadas", $inserted],
            ]
        );
    }
}
