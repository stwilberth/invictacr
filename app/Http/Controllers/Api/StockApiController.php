<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SyncLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StockApiController extends Controller
{
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
            return preg_replace("/-/", "", $m[1]);
        }
        $clean = preg_replace("/^invicta-/i", "", $slug);
        $clean = preg_replace("/-/", "", $clean);
        return trim($clean) ?: null;
    }

    private function fetchData(string $url): array
    {
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

    public function sync()
    {
        $log = SyncLog::create(["type" => "stock", "status" => "running"]);

        try {
            $stockData = $this->fetchData(self::STOCK_API_URL);

            $agotadosData = [];
            try {
                $agotadosData = $this->fetchData(self::AGOTADOS_API_URL);
            } catch (\Exception $e) {
                // agotados is optional
            }

            $updatedCount = 0;
            $createdCount = 0;

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
                    if ($product->bloqueado ?? false) {
                        continue;
                    }
                    if ((int) $product->precio_venta === 0) {
                        continue;
                    }

                    $prevStock = (int) ($product->stock ?? 0);
                    $prevPrice = (int) ($product->precio_venta ?? 0);

                    $updates = ["stock" => $stockVal];

                    $isComingBackToStock = $prevStock === 0 && $stockVal > 0;
                    $priceIsTooLow = $prevPrice <= $priceVal;

                    if ($isComingBackToStock || $priceIsTooLow) {
                        $existingIncrease =
                            (int) ($product->variedades_increase ?? 0);
                        $increase =
                            $existingIncrease > 0
                                ? $existingIncrease
                                : random_int(4000, 9000);
                        $updates["precio_venta"] = $this->roundUpToThousand(
                            $priceVal + $increase,
                        );
                        $updates["variedades_increase"] = $increase;
                        $updates["variedades_price"] = $priceVal;
                        $updates["precio_original"] = $priceVal;
                    } elseif (
                        (int) ($product->variedades_price ?? 0) !== $priceVal
                    ) {
                        $updates["variedades_price"] = $priceVal;
                        $updates["precio_original"] = $priceVal;
                    }

                    $product->update($updates);
                    $updatedCount++;
                } else {
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
                        "bloqueado" => false,
                        "vistas" => 0,
                        "activo" => true,
                        "imagen" =>
                            self::CDN_BASE_URL .
                            "/{$modelKey}/{$modelKey}_1.jpg",
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
                if (
                    $product &&
                    !($product->bloqueado ?? false) &&
                    (int) $product->precio_venta > 0
                ) {
                    $product->update(["stock" => 0]);
                    $updatedCount++;
                }
            }

            $log->update([
                "status" => "completed",
                "message" => "{$createdCount} created, {$updatedCount} updated",
            ]);
            return response()->json([
                "success" => true,
                "created" => $createdCount,
                "updated" => $updatedCount,
            ]);
        } catch (\Exception $e) {
            $log->update(["status" => "failed", "message" => $e->getMessage()]);
            return response()->json(
                ["success" => false, "error" => $e->getMessage()],
                500,
            );
        }
    }
}
