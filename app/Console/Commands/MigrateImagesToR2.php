<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MigrateImagesToR2 extends Command
{
    protected $signature = 'images:migrate-r2 {--dry-run : Solo muestra lo que haría sin ejecutar}';
    protected $description = 'Migra imágenes de productos desde el servidor local a R2 (Cloudflare)';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('MODO DRY-RUN: No se realizarán cambios');
        }

        // Verificar que el disk R2 esté configurado
        try {
            $disk = Storage::disk('r2');
            $disk->exists('test');
        } catch (\Throwable $e) {
            $this->error('Error configurando R2: ' . $e->getMessage());
            $this->info('Verificá que las variables AWS_* estén en .env');
            return 1;
        }

        // Obtener todas las imágenes de la DB
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
        $r2BaseUrl = 'https://pub-fef68f2ef09a1b432764edcf35b21cc5.r2.dev';

        foreach ($products as $product) {
            $currentPath = $product->imagen;

            // Si ya está en R2, saltar
            if (str_starts_with($currentPath, $r2BaseUrl)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Construir URL completa para descargar
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

            if ($dryRun) {
                $this->newLine();
                $this->info("Subiría: {$downloadUrl} → {$r2BaseUrl}/relojes/" . basename($currentPath));
                $bar->advance();
                continue;
            }

            try {
                // Descargar imagen
                $response = Http::timeout(30)->get($downloadUrl);

                if (!$response->successful()) {
                    $errors++;
                    $bar->advance();
                    continue;
                }

                $contents = $response->body();
                $filename = basename($currentPath);
                $key = "relojes/{$filename}";

                // Subir a R2
                Storage::disk('r2')->put($key, $contents, 'public');

                // Actualizar DB
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['imagen' => "{$r2BaseUrl}/relojes/{$filename}"]);

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
            $this->info('Dry-run completado. Ejecutá sin --dry-run para migrar.');
        } else {
            $this->info("Subidas: {$uploaded} | Saltadas (ya en R2): {$skipped} | Errores: {$errors}");
            $this->info('¡Migración completada!');
        }
    }
}
