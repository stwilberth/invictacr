<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ImageOptimizerService;
use Illuminate\Console\Command;

class OptimizeImages extends Command
{
    protected $signature = 'invicta:optimize-images
        {--modelo= : Optimizar solo un modelo específico}
        {--dry-run : Solo mostrar qué se va a optimizar, sin escribir}
        {--force : Optimizar incluso los que ya están optimizados}';

    protected $description = 'Genera thumbnails y versiones WebP de las imágenes de productos';

    public function handle(ImageOptimizerService $service): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $specificModelo = $this->option('modelo');

        if ($specificModelo) {
            $product = Product::where('modelo', $specificModelo)->first();
            if (!$product) {
                $this->error("Producto con modelo '{$specificModelo}' no encontrado.");
                return 1;
            }
            return $this->optimizeOne($service, $product, $dryRun);
        }

        return $this->optimizeAll($service, $dryRun, $force);
    }

    private function optimizeOne(ImageOptimizerService $service, Product $product, bool $dryRun): int
    {
        $this->line("Procesando: {$product->modelo}");

        if ($dryRun) {
            $needsOpt = $service->needsOptimization($product);
            $this->line($needsOpt ? '  -> Necesita optimización' : '  -> Ya optimizado');
            return 0;
        }

        $result = $service->optimizeProduct($product);

        if ($result['success']) {
            $this->info("  OK: thumb={$result['thumb_size']} bytes, medium={$result['medium_size']} bytes");
        } else {
            $this->warn("  Error: {$result['error']}");
        }

        return $result['success'] ? 0 : 1;
    }

    private function optimizeAll(ImageOptimizerService $service, bool $dryRun, bool $force): int
    {
        $query = Product::whereNotNull('imagen');

        if (!$force) {
            $all = $query->get();
            $unoptimized = $all->filter(fn($p) => $service->needsOptimization($p));
            $total = $all->count();
            $alreadyOpt = $total - $unoptimized->count();
        } else {
            $unoptimized = $query->get();
            $total = $unoptimized->count();
            $alreadyOpt = 0;
        }

        if ($dryRun) {
            $this->info("Total productos con imagen: {$total}");
            $this->info("Ya optimizados: {$alreadyOpt}");
            $this->info("Por optimizar: {$unoptimized->count()}");

            if ($unoptimized->isNotEmpty()) {
                $this->newLine();
                $this->line('Productos pendientes:');
                foreach ($unoptimized as $p) {
                    $this->line("  - {$p->modelo}");
                }
            }

            return 0;
        }

        if ($unoptimized->isEmpty()) {
            $this->info('Todos los productos ya están optimizados.');
            return 0;
        }

        $count = $unoptimized->count();
        $this->info("Optimizando {$count} producto(s)...");
        $this->output->progressStart($count);

        $successCount = 0;
        $failCount = 0;

        foreach ($unoptimized as $product) {
            $result = $service->optimizeProduct($product);

            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
                if ($result['error']) {
                    $this->output->progressAdvance();
                    $this->newLine();
                    $this->warn("  {$product->modelo}: {$result['error']}");
                }
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->newLine();
        $this->info("Optimización completada: {$successCount} exitosos, {$failCount} fallos.");

        return $failCount > 0 ? 1 : 0;
    }
}
