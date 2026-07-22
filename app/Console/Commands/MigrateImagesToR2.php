<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MigrateImagesToR2 extends Command
{
    protected $signature = 'images:migrate-r2 {--dry-run : Solo muestra lo que haría sin ejecutar}';
    protected $description = 'Sube imágenes de productos a R2 (Cloudflare) sin afectar las URLs actuales';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('MODO DRY-RUN: No se realizarán cambios');
        }

        try {
            $disk = Storage::disk('r2');
            $disk->exists('test');
        } catch (\Throwable $e) {
            $this->error('Error configurando R2: ' . $e->getMessage());
            $this->info('Verificá que las variables AWS_* estén en .env');
            return 1;
        }

        $products = DB::table('products')
            ->whereNotNull('imagen')
            ->where('imagen', '!=', '')
            ->get();

        $this->info("Encontrados {$products->count()} productos con imagen");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $uploaded = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($products as $product) {
            $currentPath = $product->imagen;
            $filename = basename($currentPath);
            $key = "relojes/{$filename}";

            if ($dryRun) {
                $this->newLine();
                $this->info("Subiría: {$currentPath} → relojes/{$filename}");
                $bar->advance();
                continue;
            }

            try {
                if ($disk->exists($key)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $downloadUrl = null;

                if (str_starts_with($currentPath, 'http')) {
                    $downloadUrl = $currentPath;
                } elseif (str_starts_with($currentPath, '/storage/')) {
                    $downloadUrl = env('APP_URL') . $currentPath;
                } elseif (str_starts_with($currentPath, '/images/')) {
                    $downloadUrl = env('APP_URL') . $currentPath;
                }

                if (!$downloadUrl) {
                    $errors++;
                    $bar->advance();
                    continue;
                }

                $response = Http::timeout(30)->get($downloadUrl);

                if (!$response->successful()) {
                    $errors++;
                    $bar->advance();
                    continue;
                }

                $disk->put($key, $response->body(), 'public');
                $uploaded++;
            } catch (\Throwable $e) {
                $errors++;
                $this->newLine();
                $this->error("Error con producto {$product->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info('Dry-run completado. Ejecutá sin --dry-run para subir.');
        } else {
            $this->info("Subidas: {$uploaded} | Saltadas (ya en R2): {$skipped} | Errores: {$errors}");
            $this->info('Las URLs en la DB no fueron modificadas. Las imágenes siguen sirviéndose localmente.');
        }
    }
}
