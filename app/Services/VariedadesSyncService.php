<?php

namespace App\Services;

use App\Models\Product;
use App\Models\SyncLog;
use App\Services\InvictaWatchScraper;
use Illuminate\Support\Facades\Http;

class VariedadesSyncService
{
    private const STOCK_API_URL = "https://variedadescr.com/api/productos/stock?marca=67&genero=0&descuento=0";
    private const AGOTADOS_API_URL = "https://variedadescr.com/api/productos/agotados?marca=67";
    private const API_HEADERS = [
        "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
        "Accept" => "application/json, text/plain, */*",
        "Accept-Language" => "es-CR,es;q=0.9,en;q=0.8",
        "Referer" => "https://variedadescr.com/",
        "Origin" => "https://variedadescr.com",
    ];
    private const CDN_BASE_URL = "https://cdn.invictawatch.com/www/img/products";

    public function execute(): array
    {
        $log = SyncLog::create(["type" => "stock", "status" => "running"]);

        try {
            $stockData = $this->fetchData(self::STOCK_API_URL);

            $agotadosData = [];
            try {
                $agotadosData = $this->fetchData(self::AGOTADOS_API_URL);
            } catch (\Exception $e) {
                SyncLog::create(["type" => "stock", "status" => "warning", "message" => "No se pudieron obtener agotados: {$e->getMessage()}"]);
            }

            $createdCount = 0;
            $activatedCount = 0;
            $stockChangedCount = 0;
            $priceRecalculatedCount = 0;
            $referenceUpdatedCount = 0;
            $markedAgotadoCount = 0;

            foreach ($stockData as $item) {
                $modelKey = $this->extractModel($item["slug"] ?? "");
                if (!$modelKey) {
                    continue;
                }

                $stockVal = (int) ($item["stock"] ?? 0);
                $priceVal = (int) ($item["precio_venta"] ?? 0);
                $generoApi = $this->mapGender((int) ($item["genero"] ?? 0));

                $product = Product::where("modelo", $modelKey)->first();

                if ($product) {
                    if ($product->bloqueado) {
                        continue;
                    }

                    if ($product->proximo) {
                        $increase = (int) ($product->variedades_increase ?? 0);
                        $increase = $increase > 0 ? $increase : random_int(4000, 9000);
                        $roundedPrice = $this->roundUpToThousand($priceVal + $increase);

                        $iwData = null;
                        try {
                            $scraper = app(InvictaWatchScraper::class);
                            $iwData = $scraper->scrape($modelKey);
                        } catch (\Exception $e) {
                        }

                        $product->update([
                            'proximo' => false,
                            'precio_venta' => $roundedPrice,
                            'precio_original' => $iwData['msrp'] ?? $priceVal,
                            'variedades_price' => $priceVal,
                            'variedades_increase' => $increase,
                            'stock' => max(1, $stockVal),
                            'genero' => $iwData['genero'] ?? $product->genero,
                            'coleccion' => $iwData['coleccion'] ?? $product->coleccion,
                            'size' => $iwData['size'] ?? $product->size,
                            'caja' => $iwData['caja'] ?? $product->caja,
                            'brazalete' => $iwData['brazalete'] ?? $product->brazalete,
                            'tipo_movimiento' => $iwData['tipo_movimiento'] ?? $product->tipo_movimiento,
                            'resistencia_agua' => $iwData['resistencia_agua'] ?? $product->resistencia_agua,
                            'imagen' => $iwData['imagen_local'] ?? $product->imagen,
                            'descripcion' => $iwData['descripcion'] ?? $product->descripcion,
                        ]);
                        $activatedCount++;
                        continue;
                    }

                    $prevStock = (int) ($product->stock ?? 0);
                    $prevVariedadesPrice = (int) ($product->variedades_price ?? 0);

                    $updates = [];
                    $didChange = false;

                    if ($prevStock !== $stockVal) {
                        $updates["stock"] = $stockVal;
                        $stockChangedCount++;
                        $didChange = true;
                    }

                    $isComingBackToStock = $prevStock === 0 && $stockVal > 0;

                    if ($isComingBackToStock) {
                        $existingIncrease = (int) ($product->variedades_increase ?? 0);
                        $increase = $existingIncrease > 0 ? $existingIncrease : random_int(4000, 9000);
                        $newPrice = $this->roundUpToThousand($priceVal + $increase);

                        $updates["precio_venta"] = $newPrice;
                        $updates["variedades_increase"] = $increase;
                        $updates["variedades_price"] = $priceVal;
                        $updates["precio_original"] = $priceVal;
                        $priceRecalculatedCount++;
                        $didChange = true;
                    } elseif ($prevVariedadesPrice !== $priceVal) {
                        $updates["variedades_price"] = $priceVal;
                        $updates["precio_original"] = $priceVal;
                        $referenceUpdatedCount++;
                        $didChange = true;
                    }

                    if ($didChange) {
                        $product->update($updates);
                    }
                } else {
                    $increase = random_int(4000, 9000);
                    $roundedPrice = $this->roundUpToThousand($priceVal + $increase);

                    $iwData = null;
                    try {
                        $scraper = app(InvictaWatchScraper::class);
                        $iwData = $scraper->scrape($modelKey);
                    } catch (\Exception $e) {
                        // fallback to basic data
                    }

                    $title = "Invicta {$modelKey}";
                    if ($iwData && !empty($iwData['title'])) {
                        $scrapedTitle = $iwData['title'];
                        if (preg_match('/^\d+\s*-\s*(.+)$/', $scrapedTitle, $m)) {
                            $scrapedTitle = trim($m[1]);
                        }
                        $title = 'Invicta ' . $scrapedTitle;
                    }

                    Product::create([
                        "modelo" => $modelKey,
                        "title" => $title,
                        "slug" => "invicta-" . strtolower($modelKey),
                        "descripcion" => $iwData['descripcion'] ?? null,
                        "precio_venta" => $roundedPrice,
                        "precio_original" => $iwData['msrp'] ?? $priceVal,
                        "variedades_price" => $priceVal,
                        "variedades_increase" => $increase,
                        "descuento" => 0,
                        "genero" => $iwData['genero'] ?? $generoApi,
                        "stock" => $stockVal,
                        "coleccion" => $iwData['coleccion'] ?? null,
                        "color" => null,
                        "brazalete" => $iwData['brazalete'] ?? null,
                        "caja" => $iwData['caja'] ?? null,
                        "size" => $iwData['size'] ?? null,
                        "tipo_movimiento" => $iwData['tipo_movimiento'] ?? null,
                        "resistencia_agua" => $iwData['resistencia_agua'] ?? null,
                        "bloqueado" => false,
                        "vistas" => 0,
                        "activo" => true,
                        "imagen" => $iwData['imagen_local'] ?? self::CDN_BASE_URL . "/{$modelKey}/{$modelKey}_1.jpg",
                    ]);
                    $createdCount++;
                }
            }

            foreach ($agotadosData as $item) {
                $modelKey = $this->extractModel($item["slug"] ?? "");
                if (!$modelKey) {
                    continue;
                }

                $product = Product::where("modelo", $modelKey)->first();
                if ($product && !$product->bloqueado && (int) $product->precio_venta > 0 && (int) $product->stock !== 0) {
                    $product->update(["stock" => 0]);
                    $markedAgotadoCount++;
                }
            }

            $details = [
                "creados" => $createdCount,
                "activados" => $activatedCount,
                "stock_actualizado" => $stockChangedCount,
                "precio_recalculado" => $priceRecalculatedCount,
                "referencia_actualizada" => $referenceUpdatedCount,
                "marcados_agotados" => $markedAgotadoCount,
            ];

            $parts = [];
            if ($createdCount > 0) $parts[] = "{$createdCount} creados";
            if ($activatedCount > 0) $parts[] = "{$activatedCount} próximos activados";
            if ($stockChangedCount > 0) $parts[] = "{$stockChangedCount} stock actualizado";
            if ($priceRecalculatedCount > 0) $parts[] = "{$priceRecalculatedCount} precios recalculados";
            if ($referenceUpdatedCount > 0) $parts[] = "{$referenceUpdatedCount} precios referencia actualizados";
            if ($markedAgotadoCount > 0) $parts[] = "{$markedAgotadoCount} marcados agotados";
            $msg = implode(", ", $parts) ?: "Sin cambios";

            $log->update(["status" => "completed", "message" => $msg, "details" => $details]);

            return [
                "success" => true,
                "created" => $createdCount,
                "activated" => $activatedCount,
                "stock_changed" => $stockChangedCount,
                "price_recalculated" => $priceRecalculatedCount,
                "reference_updated" => $referenceUpdatedCount,
                "marked_agotado" => $markedAgotadoCount,
                "message" => $msg,
                "details" => $details,
            ];
        } catch (\Exception $e) {
            $log->update(["status" => "failed", "message" => $e->getMessage()]);

            return [
                "success" => false,
                "error" => $e->getMessage(),
            ];
        }
    }

    private function roundUpToThousand(int $value): int
    {
        return (int) (ceil($value / 1000) * 1000);
    }

    private function mapGender(int $code): string
    {
        return match ($code) {
            1 => "hombre",
            2 => "mujer",
            3 => "unisex",
            default => "unisex",
        };
    }

    private function extractModel(string $slug): ?string
    {
        if (preg_match("/invicta-([a-z0-9]+)/i", $slug, $m)) {
            $model = preg_replace("/-/", "", $m[1]);
            return $model ?: null;
        }
        $clean = preg_replace("/^invicta-/i", "", $slug);
        $clean = preg_replace("/-/", "", $clean);
        return trim($clean) ?: null;
    }

    private function fetchData(string $url): array
    {
        $response = Http::withHeaders(self::API_HEADERS)
            ->timeout(60)
            ->withOptions(app()->environment("local") ? ["verify" => false] : [])
            ->get($url);

        if (!$response->ok()) {
            throw new \Exception("Error HTTP {$response->status()} al consultar {$url}");
        }

        $body = $response->json();
        if (!($body["success"] ?? false) || !isset($body["data"])) {
            throw new \Exception("Formato de respuesta inválido de la API");
        }

        return $body["data"];
    }
}
