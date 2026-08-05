<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\InvictaWatchScraper;
use Illuminate\Console\Command;

class BackfillColors extends Command
{
    protected $signature = 'invicta:backfill-colors {--dry-run : Solo mostrar los cambios, no escribir} {--force : Reprocesar también productos que ya tienen color} {--limit= : Límite de productos a procesar}';

    protected $description = 'Rellena el campo color de productos sin color scrapeando invictawatch.com';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $limit = (int) $this->option('limit');

        $query = Product::whereNull('color')->orWhere('color', '');

        if ($force) {
            $query = Product::query();
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $products = $query->get(['id', 'modelo']);

        if ($products->isEmpty()) {
            $this->info('No hay productos sin color.');
            return 0;
        }

        $this->info("Procesando {$products->count()} producto(s) sin color...");

        $scraper = app(InvictaWatchScraper::class);
        $updated = 0;
        $notFound = 0;

        foreach ($products as $product) {
            $color = null;
            try {
                $iwData = $scraper->scrape($product->modelo);
                $color = $iwData['color'] ?? null;
            } catch (\Exception $e) {
                $this->warn("  {$product->modelo}: error de scrape ({$e->getMessage()})");
            }

            if (!$color) {
                $notFound++;
                $this->line("  {$product->modelo}: sin color detectado");
                continue;
            }

            $this->line("  {$product->modelo} => {$color}");
            if (!$dry) {
                Product::where('id', $product->id)->update(['color' => $color]);
            }
            $updated++;
        }

        $this->newLine();
        if ($dry) {
            $this->comment("Modo --dry-run: no se modificó la base de datos.");
        } else {
            $this->info("Completado: {$updated} actualizados, {$notFound} sin color detectado.");
        }

        return 0;
    }
}
