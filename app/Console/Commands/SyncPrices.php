<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\CloudflareCacheService;
use App\Services\PricingService;
use Illuminate\Console\Command;

class SyncPrices extends Command
{
    protected $signature = 'invicta:sync-prices
                            {--dry-run : Solo mostrar los cambios propuestos sin aplicarlos}
                            {--force : Recalcular también productos con manual_override = true}';

    protected $description = 'Recalcula precios (costo, mínimo, competitivo, final) con PricingService';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        if ($force) {
            $this->warn('--force: se recalcularán también productos con manual_override = true, sobrescribiendo precios fijados manualmente.');
        }

        $service = app(PricingService::class);

        $query = Product::query()
            ->where('activo', true)
            ->where('bloqueado', false)
            ->where('proximo', false)
            ->whereNotNull('precio_original')
            ->where('precio_original', '>', 0)
            ->whereNotNull('precio_costo');

        if (! $force) {
            $query->where('manual_override', false);
        }

        $products = $query->get();

        $this->info($dry ? '--- MODO DRY-RUN (sin cambios) ---' : 'Recalculando precios...');

        $dryRows = [];
        $changed = [];
        $unchanged = 0;
        $skipped = 0;

        foreach ($products as $product) {
            try {
                $pricing = $service->calculate((float) $product->precio_original);
            } catch (\InvalidArgumentException $e) {
                $this->warn("Modelo {$product->modelo}: {$e->getMessage()} (se omite)");
                $skipped++;
                continue;
            }

            $newVenta = $pricing['precio_final'];
            $newCosto = $pricing['precio_costo'];

            $ventaChanged = (float) $product->precio_venta !== (float) $newVenta;
            $costoChanged = (float) $product->precio_costo !== (float) $newCosto;

            if (! $ventaChanged && ! $costoChanged) {
                $unchanged++;
                continue;
            }

            if ($dry) {
                $dryRows[] = [
                    $product->modelo,
                    number_format((float) $product->precio_original, 0),
                    number_format((float) $pricing['precio_costo'], 2),
                    number_format((float) $pricing['precio_minimo'], 0),
                    number_format((float) $pricing['precio_objetivo'], 0),
                    number_format((float) $pricing['precio_base'], 0),
                    number_format((float) $pricing['precio_final'], 0),
                    number_format((float) $product->precio_venta, 0),
                    number_format((float) $pricing['margen_bruto_pct'], 2),
                    $product->manual_override ? 'SÍ' : '—',
                ];

                $changed[] = $product;
                continue;
            }

            $oldVenta = (float) $product->precio_venta;

            $product->precio_venta = $newVenta;
            $product->precio_costo = $newCosto;
            $product->save();
            Product::forgetAllCache($product->id);

            $changed[] = $product;

            $this->line(sprintf(
                '  <fg=cyan>%s</>  <fg=gray>%s</> → <fg=green>%s</>',
                $product->modelo,
                number_format($oldVenta, 0),
                number_format((float) $newVenta, 0),
            ));
        }

        if ($dry && ! empty($dryRows)) {
            $this->table(
                ['Modelo', 'Proveedor', 'Costo', 'Mínimo', 'Objetivo', 'Base', 'Final', 'Actual', 'Margen%', 'Override'],
                $dryRows,
            );
        }

        $this->newLine();
        $this->info(sprintf(
            '%d cambiado(s), %d sin cambios, %d omitido(s).',
            count($changed),
            $unchanged,
            $skipped,
        ));

        if (! $dry && ! empty($changed)) {
            $this->purgeCloudflareCache(collect($changed));
        }

        return 0;
    }

    private function purgeCloudflareCache($products): void
    {
        try {
            $baseUrl = config('app.url', 'https://invictacostarica.com');
            $urls = array_merge(
                ["{$baseUrl}/", "{$baseUrl}/relojes"],
                $products->map(fn ($p) => "{$baseUrl}/relojes/{$p->slug}")->toArray(),
            );

            app(CloudflareCacheService::class)->purgeUrls(array_values(array_unique($urls)));
        } catch (\Exception $e) {
            $this->warn('No se pudo purgar la caché de Cloudflare: ' . $e->getMessage());
        }
    }
}
