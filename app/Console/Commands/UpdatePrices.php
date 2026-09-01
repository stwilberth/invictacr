<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\CloudflareCacheService;
use Illuminate\Console\Command;

class UpdatePrices extends Command
{
    protected $signature = 'invicta:update-prices
                            {prices* : Pares modelo=precio, ej: 50638=55000 50641=55000}
                            {--dry-run : Solo mostrar los cambios sin aplicarlos}';

    protected $description = 'Actualiza precio_venta de productos por modelo';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $pairs = $this->argument('prices');

        if (empty($pairs)) {
            $this->error('Debe proporcionar al menos un par modelo=precio.');
            return 1;
        }

        $updates = [];
        foreach ($pairs as $pair) {
            $parts = explode('=', $pair, 2);
            if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
                $this->error("Formato inválido: \"$pair\". Use MODELO=PRECIO (ej: 50638=55000).");
                return 1;
            }
            $updates[$parts[0]] = (float) $parts[1];
        }

        $this->info($dry ? '--- MODO DRY-RUN (sin cambios) ---' : 'Actualizando precios...');
        $this->table(['Modelo', 'Precio actual', 'Nuevo precio', 'Título'], []);

        $models = array_keys($updates);
        $products = Product::whereIn('modelo', $models)->get();

        $found = [];

        foreach ($products as $product) {
            $newPrice = $updates[$product->modelo];
            $found[] = $product->modelo;

            $this->line(sprintf(
                '  <fg=cyan>%s</>  %s%7.0f  →  <fg=green>%7.0f</>  <fg=gray>%s</>',
                $product->modelo,
                (float) $product->precio_venta === $newPrice ? '<fg=yellow>=</>' : '',
                $product->precio_venta,
                $newPrice,
                $product->title,
            ));

            if (! $dry) {
                $product->precio_venta = $newPrice;
                $product->save();
            }
        }

        $missing = array_diff($models, $found);
        foreach ($missing as $m) {
            $this->warn("Modelo $m no encontrado en la base de datos.");
        }

        if (! $dry) {
            $this->call('cache:clear');
            $this->purgeCloudflareCache($products);
            $this->newLine();
            $this->info(count($found) . ' precio(s) actualizado(s). Caché limpiada.');
        }

        return empty($missing) ? 0 : 1;
    }

    private function purgeCloudflareCache($products): void
    {
        if ($products->isEmpty()) {
            return;
        }

        try {
            $baseUrl = config('app.url', 'https://invictacostarica.com');
            $urls = array_merge(
                ["{$baseUrl}/", "{$baseUrl}/relojes"],
                $products->map(fn($p) => "{$baseUrl}/relojes/{$p->slug}")->toArray()
            );

            app(CloudflareCacheService::class)->purgeUrls(array_values(array_unique($urls)));
        } catch (\Exception $e) {
            $this->warn('No se pudo purgar la caché de Cloudflare: ' . $e->getMessage());
        }
    }
}
