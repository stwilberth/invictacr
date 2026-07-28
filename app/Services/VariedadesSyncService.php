<?php

namespace App\Services;

use App\Models\Product;
use App\Models\SyncLog;
use App\Models\SyncLogItem;
use App\Services\InvictaWatchScraper;
use App\Services\DeepseekTranslationService;
use App\Services\ImageOptimizerService;
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
            $referenceUpdatedCount = 0;
            $markedAgotadoCount = 0;

            $createdModels = [];
            $activatedModels = [];
            $stockChangedModels = [];
            $referenceUpdatedModels = [];
            $markedAgotadoModels = [];

            $items = [];

            foreach ($stockData as $item) {
                $modelKey = $this->extractModel($item["slug"] ?? "");
                if (!$modelKey) {
                    continue;
                }

                if ($this->isBlockedBySimilarModel($modelKey)) {
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

                    if ($product->proximo || (float) $product->precio_venta <= 0) {
                        $increase = random_int(4000, 9000);
                        $roundedPrice = $this->roundUpToThousand($priceVal + $increase);

                        $iwData = null;
                        try {
                            $scraper = app(InvictaWatchScraper::class);
                            $iwData = $scraper->scrape($modelKey);
                        } catch (\Exception $e) {
                        }

                        $descripcion = $iwData['descripcion'] ?? $product->descripcion;
                        if ($iwData && empty($descripcion)) {
                            $translator = app(DeepseekTranslationService::class);
                            $descripcion = $translator->translateDescription($iwData);
                        }

                        $product->update([
                            'proximo' => false,
                            'precio_venta' => $roundedPrice,
                            'precio_original' => $priceVal,
                            'stock' => max(1, $stockVal),
                            'genero' => $iwData['genero'] ?? $product->genero,
                            'coleccion' => Product::normalizeColeccion($iwData['coleccion'] ?? $product->coleccion),
                            'size' => $this->sanitizeNumeric($iwData['size'] ?? null) ?? $product->size,
                            'caja' => $iwData['caja'] ?? $product->caja,
                            'brazalete' => $iwData['brazalete'] ?? $product->brazalete,
                            'tipo_movimiento' => $iwData['tipo_movimiento'] ?? $product->tipo_movimiento,
                            'resistencia_agua' => $this->sanitizeNumeric($iwData['resistencia_agua'] ?? null) ?? $product->resistencia_agua,
                            'imagen' => $iwData['imagen_local'] ?? $product->imagen,
                            'descripcion' => $descripcion,
                        ]);

                        if (!empty($iwData['imagen_local'])) {
                            $this->optimizeProductImages($product);
                        }

                        $activatedCount++;
                        $activatedModels[] = $modelKey;
                        $items[] = ['sync_log_id' => $log->id, 'type' => 'activated', 'modelo' => $modelKey, 'product_id' => $product->id];
                        continue;
                    }

                    if (!$product->activo) {
                        $product->update(['activo' => true]);
                        $activatedCount++;
                        $activatedModels[] = $modelKey;
                        $items[] = ['sync_log_id' => $log->id, 'type' => 'activated', 'modelo' => $modelKey, 'product_id' => $product->id];
                    }

                    $prevStock = (int) ($product->stock ?? 0);

                    $updates = [];
                    $didChange = false;

                    if ($prevStock !== $stockVal) {
                        $updates["stock"] = $stockVal;
                        $stockChangedCount++;
                        $stockChangedModels[] = $modelKey;
                        $items[] = ['sync_log_id' => $log->id, 'type' => 'stock_updated', 'modelo' => $modelKey, 'product_id' => $product->id];
                        $didChange = true;
                    }

                    $prevPrecioOriginal = (int) ($product->precio_original ?? 0);
                    if ($prevPrecioOriginal !== $priceVal) {
                        $updates["precio_original"] = $priceVal;
                        $referenceUpdatedCount++;
                        $referenceUpdatedModels[] = $modelKey;
                        $items[] = ['sync_log_id' => $log->id, 'type' => 'reference_updated', 'modelo' => $modelKey, 'product_id' => $product->id];
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

                    $descripcion = $iwData['descripcion'] ?? null;
                    if ($iwData && empty($descripcion)) {
                        $translator = app(DeepseekTranslationService::class);
                        $descripcion = $translator->translateDescription($iwData);
                    }

                    $product = Product::create([
                        "modelo" => $modelKey,
                        "title" => $title,
                        "slug" => "invicta-" . strtolower($modelKey),
                        "descripcion" => $descripcion,
                        "precio_venta" => $roundedPrice,
                        "precio_original" => $priceVal,
                        "descuento" => 0,
                        "genero" => $iwData['genero'] ?? $generoApi,
                        "stock" => $stockVal,
                        "coleccion" => Product::normalizeColeccion($iwData['coleccion'] ?? null),
                        "color" => null,
                        "brazalete" => $iwData['brazalete'] ?? null,
                        "caja" => $iwData['caja'] ?? null,
                        "size" => $this->sanitizeNumeric($iwData['size'] ?? null),
                        "tipo_movimiento" => $iwData['tipo_movimiento'] ?? null,
                        "resistencia_agua" => $this->sanitizeNumeric($iwData['resistencia_agua'] ?? null),
                        "bloqueado" => false,
                        "vistas" => 0,
                        "activo" => true,
                        "imagen" => $iwData['imagen_local'] ?? self::CDN_BASE_URL . "/{$modelKey}/{$modelKey}_1.jpg",
                    ]);
                    $createdCount++;
                    $createdModels[] = $modelKey;
                    $items[] = ['sync_log_id' => $log->id, 'type' => 'created', 'modelo' => $modelKey, 'product_id' => $product->id];

                    if (!empty($iwData['imagen_local'])) {
                        $this->optimizeProductImages($product);
                    }
                }
            }

            foreach ($agotadosData as $item) {
                $modelKey = $this->extractModel($item["slug"] ?? "");
                if (!$modelKey) {
                    continue;
                }

                if ($this->isBlockedBySimilarModel($modelKey)) {
                    continue;
                }

                $product = Product::where("modelo", $modelKey)->first();
                if ($product && !$product->bloqueado && (int) $product->precio_venta > 0 && (int) $product->stock !== 0) {
                    $product->update(["stock" => 0, "disponibilidad" => "agotado"]);
                    $markedAgotadoCount++;
                    $markedAgotadoModels[] = $modelKey;
                    $items[] = ['sync_log_id' => $log->id, 'type' => 'marked_agotado', 'modelo' => $modelKey, 'product_id' => $product->id];
                }
            }

            $details = [
                "creados" => $createdCount,
                "creados_modelos" => $createdModels,
                "activados" => $activatedCount,
                "activados_modelos" => $activatedModels,
                "stock_actualizado" => $stockChangedCount,
                "stock_actualizado_modelos" => $stockChangedModels,
                "referencia_actualizada" => $referenceUpdatedCount,
                "referencia_actualizada_modelos" => $referenceUpdatedModels,
                "marcados_agotados" => $markedAgotadoCount,
                "marcados_agotados_modelos" => $markedAgotadoModels,
            ];

            if (!empty($items)) {
                SyncLogItem::insert($items);
            }

            $parts = [];
            if ($createdCount > 0) $parts[] = "{$createdCount} creados";
            if ($activatedCount > 0) $parts[] = "{$activatedCount} activados";
            if ($stockChangedCount > 0) $parts[] = "{$stockChangedCount} stock actualizado";
            if ($referenceUpdatedCount > 0) $parts[] = "{$referenceUpdatedCount} precios referencia actualizados";
            if ($markedAgotadoCount > 0) $parts[] = "{$markedAgotadoCount} marcados agotados";
            $msg = implode(", ", $parts) ?: "Sin cambios";

            $log->update(["status" => "completed", "message" => $msg, "details" => $details]);

            return [
                "success" => true,
                "created" => $createdCount,
                "activated" => $activatedCount,
                "stock_changed" => $stockChangedCount,
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

    private function isBlockedBySimilarModel(string $modelKey): bool
    {
        $numeric = preg_replace('/^[A-Za-z]+/', '', $modelKey);
        if ($numeric === $modelKey) {
            return false;
        }

        return Product::where('bloqueado', true)
            ->where('modelo', $numeric)
            ->exists();
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

    private function optimizeProductImages(Product $product): void
    {
        try {
            app(ImageOptimizerService::class)->optimizeProduct($product);
        } catch (\Throwable $e) {
            // Si falla la optimización no detenemos el sync;
            // el producto quedará como pendiente en admin/optimize-images
        }
    }

    private function sanitizeNumeric(mixed $value): ?string
    {
        if (is_null($value) || trim((string) $value) === '') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9.,]/', '', trim((string) $value));
        $cleaned = str_replace(',', '.', $cleaned);
        $cleaned = preg_replace('/\.(?=.*\.)/', '', $cleaned);
        if (!is_numeric($cleaned)) {
            return null;
        }
        $num = (float) $cleaned;
        return $num == intval($num) ? (string) intval($num) : rtrim(rtrim(sprintf('%.1f', $num), '0'), '.');
    }
}
