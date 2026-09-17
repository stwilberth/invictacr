<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\CloudflareCacheService;
use App\Services\ImageOptimizerService;
use App\Services\InvictaWatchScraper;
use Illuminate\Console\Command;

class UpgradeImagesToLarge extends Command
{
    protected $signature = "invicta:upgrade-images-to-large
        {--modelo= : Actualizar solo un modelo específico}
        {--limit= : Máximo de productos a procesar}
        {--dry-run : Solo mostrar qué se haría, sin escribir}";

    protected $description = "Re-descarga la imagen grande (l) de invictawatch y regenera large/medium/thumbs en R2";

    private const CDN_BASE = "https://cdn.invictacostarica.com";

    public function handle(): int
    {
        $dry = (bool) $this->option("dry-run");
        $specificModelo = $this->option("modelo");
        $limit = (int) ($this->option("limit") ?: 0);

        $query = Product::where("activo", true)->whereNotNull("imagen")->orderBy("id");
        if ($specificModelo) {
            $query->where("modelo", $specificModelo);
        }
        if ($limit > 0) {
            $query->limit($limit);
        }
        $products = $query->get();

        if ($products->isEmpty()) {
            $this->info("No hay productos para procesar.");
            return 0;
        }

        $this->info("Productos a procesar: {$products->count()}");

        if ($dry) {
            foreach ($products as $p) {
                $this->line("  - {$p->modelo}: {$p->imagen}");
            }
            return 0;
        }

        $scraper = app(InvictaWatchScraper::class);
        $optimizer = app(ImageOptimizerService::class);

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $updated = 0;
        $skipped = 0;
        $failed = [];
        $purgeUrls = [];

        foreach ($products as $product) {
            try {
                $data = $scraper->scrape($product->modelo);

                if (!$data || empty($data["imagen_local"]) || empty($data["imagen_contents"])) {
                    $failed[] = "{$product->modelo}: sin imagen grande obtenida";
                    $bar->advance();
                    continue;
                }

                // Verificar que la fuente descargada sea realmente mejor (>= 500px de ancho).
                // Se mide sobre los bytes frescos (R2 es eventualmente consistente en sobrescrituras).
                $size = @getimagesizefromstring($data["imagen_contents"]);
                if ($size !== false && (int) $size[0] < 500) {
                    $skipped[] = "{$product->modelo}: fuente de solo {$size[0]}px, se conserva la actual";
                    $bar->advance();
                    continue;
                }

                $product->update(["imagen" => $data["imagen_local"]]);
                Product::forgetAllCache($product->id);

                $result = $optimizer->optimizeProductFromContents($product->fresh(), $data["imagen_contents"]);
                if (!$result["success"]) {
                    $failed[] = "{$product->modelo}: optimización falló ({$result['error']})";
                    $bar->advance();
                    continue;
                }

                $model = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
                foreach (["large", "medium", "thumbs"] as $dir) {
                    $purgeUrls[] = self::CDN_BASE . "/relojes/{$dir}/{$model}.webp";
                }

                $updated++;
            } catch (\Throwable $e) {
                $failed[] = "{$product->modelo}: {$e->getMessage()}";
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Purgar el CDN en lotes de 30 (límite de la API gratuita)
        if (!empty($purgeUrls)) {
            $cf = app(CloudflareCacheService::class);
            $chunks = array_chunk(array_unique($purgeUrls), 30);
            $purged = 0;
            foreach ($chunks as $chunk) {
                try {
                    if ($cf->purgeUrls($chunk)) {
                        $purged += count($chunk);
                    }
                } catch (\Throwable $e) {
                    $this->warn("  Purga CDN falló: {$e->getMessage()}");
                }
            }
            $this->info("URLs purgadas del CDN: {$purged}");
        }

        $this->info("Imágenes mejoradas: {$updated}");

        if (!empty($skipped)) {
            $this->newLine();
            $this->line("Omitidos (" . count($skipped) . "):");
            foreach (array_slice($skipped, 0, 20) as $s) {
                $this->line("  - {$s}");
            }
        }

        if (!empty($failed)) {
            $this->newLine();
            $this->error("Con errores (" . count($failed) . "):");
            foreach (array_slice($failed, 0, 30) as $f) {
                $this->line("  - {$f}");
            }
        }

        return empty($failed) ? 0 : 1;
    }
}
