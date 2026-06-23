<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\SyncLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncStock extends Command
{
    protected $signature = 'stock:sync';
    protected $description = 'Sync products stock from variedadescr.com API';

    public function handle()
    {
        $this->info('Starting stock sync...');
        $log = SyncLog::create(['type' => 'stock', 'status' => 'running']);

        try {
            $response = Http::timeout(60)->get('https://variedadescr.com/api/productos/stock?marca=67');
            $products = $response->json();

            if (!$products || !is_array($products)) {
                throw new \Exception('Invalid API response');
            }

            $count = 0;
            foreach ($products as $item) {
                $modelo = $item['modelo'] ?? $item['model'];
                if (!$modelo) continue;

                Product::updateOrCreate(
                    ['modelo' => $modelo],
                    [
                        'title' => $item['nombre'] ?? "Invicta {$modelo}",
                        'slug' => 'invicta-' . str($modelo)->slug(),
                        'stock' => $item['stock'] ?? 0,
                        'precio_venta' => ($item['precio'] ?? 0) * 1.15,
                        'precio_original' => $item['precio'] ?? null,
                        'imagen' => "https://cdn.invictawatch.com/www/img/products/{$modelo}/{$modelo}_1.jpg",
                    ]
                );
                $count++;
            }

            $log->update(['status' => 'completed', 'message' => "{$count} productos sincronizados"]);
            $this->info("Sync completed: {$count} products");
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'message' => $e->getMessage()]);
            $this->error("Sync failed: {$e->getMessage()}");
        }
    }
}
