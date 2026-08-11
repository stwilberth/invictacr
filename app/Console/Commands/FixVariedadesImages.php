<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ImageOptimizerService;
use App\Services\InvictaWatchScraper;
use Illuminate\Console\Command;

class FixVariedadesImages extends Command
{
    protected $signature = "invicta:fix-variedades-images {--dry-run : Mostrar los cambios sin escribir}";

    protected $description = "Re-escrapea productos con imagen rota del CDN antiguo de invictawatch y rellena imagen y datos faltantes";

    public function handle(): int
    {
        $dry = (bool) $this->option("dry-run");

        $products = Product::where("imagen", "like", "https://cdn.invictawatch.com/%")
            ->orWhere("imagen", "like", "https://www.invictawatch.com/%")
            ->get();

        if ($products->isEmpty()) {
            $this->info("No hay productos con imágenes del CDN antiguo de invictawatch.");
            return 0;
        }

        $this->info("Productos a reparar: {$products->count()}");
        $this->newLine();

        $scraper = app(InvictaWatchScraper::class);
        $optimizer = app(ImageOptimizerService::class);

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $updated = 0;
        $failed = [];

        foreach ($products as $product) {
            try {
                $data = $scraper->scrape($product->modelo);

                if (!$data || empty($data["imagen_local"])) {
                    $failed[] = "{$product->modelo}: sin imagen obtenida";
                    $bar->advance();
                    continue;
                }

                if (!$dry) {
                    $updates = [
                        "imagen" => $data["imagen_local"],
                    ];

                    $genericTitle = 'Invicta ' . $product->modelo;
                    if ($product->title === $genericTitle && !empty($data["title"])) {
                        $updates["title"] = 'Invicta ' . $data["title"];
                    }
                    if (empty($product->descripcion) && !empty($data["descripcion"])) {
                        $updates["descripcion"] = $data["descripcion"];
                    }
                    if (empty($product->coleccion) && !empty($data["coleccion"])) {
                        $updates["coleccion"] = Product::normalizeColeccion($data["coleccion"]);
                    }
                    foreach (["genero", "color", "size", "caja", "brazalete", "tipo_movimiento", "resistencia_agua"] as $field) {
                        if (empty($product->{$field}) && !empty($data[$field])) {
                            $updates[$field] = $data[$field];
                        }
                    }

                    $product->update($updates);

                    $optimizer->optimizeProduct($product);
                }

                $updated++;
            } catch (\Throwable $e) {
                $failed[] = "{$product->modelo}: {$e->getMessage()}";
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Imágenes reparadas: {$updated}");

        if (!empty($failed)) {
            $this->newLine();
            $this->error("Con errores:");
            foreach ($failed as $f) {
                $this->line("  - {$f}");
            }
        }

        return empty($failed) ? 0 : 1;
    }
}
