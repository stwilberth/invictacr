<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\SyncLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncStock extends Command
{
    protected $signature = "stock:sync";
    protected $description = "Sync products stock from variedadescr.com API";

    private const STOCK_API_URL = "https://variedadescr.com/api/productos/stock?marca=67&genero=0&descuento=0";
    private const AGOTADOS_API_URL = "https://variedadescr.com/api/productos/agotados?marca=67";
    private const API_HEADERS = [
        "User-Agent" =>
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
        "Accept" => "application/json, text/plain, */*",
        "Accept-Language" => "es-CR,es;q=0.9,en;q=0.8",
        "Referer" => "https://variedadescr.com/",
        "Origin" => "https://variedadescr.com",
    ];

    private const CDN_BASE_URL = "https://cdn.invictawatch.com/www/img/products";

    private function roundUpToThousand(int $value): int
    {
        return (int) (ceil($value / 1000) * 1000);
    }

    private function mapGender(int $generoCode): string
    {
        return match ($generoCode) {
            1 => "hombre",
            2 => "mujer",
            3 => "unisex",
            default => "unisex",
        };
    }

    private function extractModel(string $slug): ?string
    {
        if (preg_match("/invicta-([a-z0-9]+)/i", $slug, $m)) {
            return preg_replace("/-/", "", $m[1]);
        }
        $clean = preg_replace("/^invicta-/i", "", $slug);
        $clean = preg_replace("/-/", "", $clean);
        return trim($clean) ?: null;
    }

    private function fetchData(string $url): array
    {
        $this->info("Fetching: {$url}");
        $response = Http::withHeaders(self::API_HEADERS)
            ->timeout(60)
            ->withOptions(
                app()->environment("local") ? ["verify" => false] : [],
            )
            ->get($url);
        if (!$response->ok()) {
            throw new \Exception("HTTP error {$response->status()}");
        }
        $body = $response->json();
        if (!($body["success"] ?? false) || !isset($body["data"])) {
            throw new \Exception("Invalid API response format");
        }
        return $body["data"];
    }

    public function handle()
    {
        $this->info("=== VariedadesCR Stock Sync ===");
        $log = SyncLog::create(["type" => "stock", "status" => "running"]);

        try {
            // 1. Fetch both APIs in parallel
            $stockData = $this->fetchData(self::STOCK_API_URL);
            $this->info("Fetched " . count($stockData) . " active products.");

            $agotadosData = [];
            try {
                $agotadosData = $this->fetchData(self::AGOTADOS_API_URL);
                $this->info(
                    "Fetched " . count($agotadosData) . " sold-out products.",
                );
            } catch (\Exception $e) {
                $this->warn("Could not fetch agotados: {$e->getMessage()}");
            }

            $updatedCount = 0;
            $createdCount = 0;
            $skippedCount = 0;

            // 2. Process active stock
            foreach ($stockData as $item) {
                $slug = $item["slug"] ?? "";
                $modelKey = $this->extractModel($slug);
                if (!$modelKey) {
                    continue;
                }

                $stockVal = (int) ($item["stock"] ?? 0);
                $priceVal = (int) ($item["precio_venta"] ?? 0);
                $generoApi = $this->mapGender((int) ($item["genero"] ?? 0));

                $product = Product::where("modelo", $modelKey)->first();

                if ($product) {
                    // --- UPDATE ---
                    // Skip blocked products
                    if ($product->bloqueado ?? false) {
                        $skippedCount++;
                        continue;
                    }

                    // Skip upcoming products (precio_venta === 0)
                    if ((int) $product->precio_venta === 0) {
                        $skippedCount++;
                        continue;
                    }

                    $prevStock = (int) ($product->stock ?? 0);
                    $prevPrice = (int) ($product->precio_venta ?? 0);
                    $variedadesPriceChanged =
                        (int) ($product->variedades_price ?? 0) !== $priceVal;

                    $updates = [
                        "stock" => $stockVal,
                    ];

                    // Price rules:
                    // 1. If back in stock (0 -> >0): recalc increase
                    // 2. If our price <= VariedadesCR price: recalc increase
                    $isComingBackToStock = $prevStock === 0 && $stockVal > 0;
                    $priceIsTooLow = $prevPrice <= $priceVal;

                    if ($isComingBackToStock || $priceIsTooLow) {
                        $existingIncrease =
                            (int) ($product->variedades_increase ?? 0);
                        $increase =
                            $existingIncrease > 0
                                ? $existingIncrease
                                : random_int(4000, 9000);
                        $targetPrice = $priceVal + $increase;
                        $roundedPrice = $this->roundUpToThousand($targetPrice);

                        $updates["precio_venta"] = $roundedPrice;
                        $updates["variedades_increase"] = $increase;
                        $updates["variedades_price"] = $priceVal;
                        $updates["precio_original"] = $priceVal;

                        $this->line(
                            "  Recalc {$modelKey}: {$prevPrice} -> {$roundedPrice} (increase {$increase})",
                        );
                    } elseif ($variedadesPriceChanged) {
                        // Only update reference price
                        $updates["variedades_price"] = $priceVal;
                        $updates["precio_original"] = $priceVal;
                    }

                    $product->update($updates);
                    $updatedCount++;
                } else {
                    // --- CREATE ---
                    $increase = random_int(4000, 9000);
                    $roundedPrice = $this->roundUpToThousand(
                        $priceVal + $increase,
                    );

                    Product::create([
                        "modelo" => $modelKey,
                        "title" => "Invicta {$modelKey}",
                        "slug" => "invicta-" . strtolower($modelKey),
                        "precio_venta" => $roundedPrice,
                        "precio_original" => $priceVal,
                        "variedades_price" => $priceVal,
                        "variedades_increase" => $increase,
                        "descuento" => 0,
                        "genero" => $generoApi,
                        "stock" => $stockVal,
                        "coleccion" => null,
                        "color" => null,
                        "brazalete" => null,
                        "caja" => null,
                        "size" => null,
                        "tipo_movimiento" => null,
                        "resistencia_agua" => null,
                        "bloqueado" => false,
                        "vistas" => 0,
                        "activo" => true,
                        "imagen" =>
                            self::CDN_BASE_URL .
                            "/{$modelKey}/{$modelKey}_1.jpg",
                    ]);
                    $this->line(
                        "  Created: {$modelKey} ({$generoApi}) -> {$roundedPrice}",
                    );
                    $createdCount++;
                }
            }

            // 3. Process sold-out (set stock to 0)
            foreach ($agotadosData as $item) {
                $slug = $item["slug"] ?? "";
                $modelKey = $this->extractModel($slug);
                if (!$modelKey) {
                    continue;
                }

                $product = Product::where("modelo", $modelKey)->first();
                if (
                    $product &&
                    !($product->bloqueado ?? false) &&
                    (int) $product->precio_venta > 0
                ) {
                    $product->update(["stock" => 0]);
                    $updatedCount++;
                }
            }

            $msg = "Sync: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped";
            $log->update(["status" => "completed", "message" => $msg]);
            $this->info("\n=== Sync Complete ===");
            $this->info($msg);
        } catch (\Exception $e) {
            $log->update(["status" => "failed", "message" => $e->getMessage()]);
            $this->error("Sync failed: {$e->getMessage()}");
        }
    }
}
