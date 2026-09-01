<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SearchLog;
use App\Services\CatalogService;
use App\Services\SearchService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $aiResponse = null;
        $aiRawResponse = null;
        $usedAi = false;
        $aiSkippedReason = null;
        $parsedFilters = [];
        $originalQuery = $request->input("q");
        $suggestions = collect();

        if ($request->filled("genero") && !$request->filled("gender")) {
            $request->merge(["gender" => $request->genero]);
        }

        if ($request->filled("q")) {
            $search = app(SearchService::class);
            $parsed = $search->parse($request->q);
            $parsedFilters = $parsed;

            $textQuery = $parsedFilters["q"] ?? null;

            $parsedFilters = array_filter($parsedFilters, fn($k) => $k !== "q", ARRAY_FILTER_USE_KEY);

            $this->applyParsedFilters($request, $parsedFilters);

            if ($textQuery) {
                $request->merge(["q" => $textQuery]);
            } else {
                $request->merge(["q" => ""]);
            }

            $products = $this->runSearchQuery($request);

            if ($products->isEmpty() && $originalQuery) {
                $aiFilters = $search->parseWithClaude($originalQuery);

                $usedAi = $search->usedAi;
                $aiResponse = $search->aiResponse;
                $aiRawResponse = $search->aiRawResponse;
                $aiSkippedReason = $search->aiSkippedReason;

                if (!empty($aiFilters)) {
                    $aiQ = $aiFilters["q"] ?? null;

                    $request->merge(["q" => $aiQ ?? ""]);
                    foreach (["gender", "color", "coleccion", "brazalete", "tipo_movimiento", "caja", "resistencia_agua"] as $f) {
                        $request->merge([$f => ""]);
                    }

                    $filterFields = array_filter($aiFilters, fn($k) => $k !== "q", ARRAY_FILTER_USE_KEY);
                    $parsedFilters = $filterFields;

                    $this->applyParsedFilters($request, $filterFields);

                    $products = $this->runSearchQuery($request);
                }

                if ($products->isEmpty()) {
                    $request->merge(["q" => $originalQuery]);
                    foreach (["gender", "color", "coleccion", "brazalete", "tipo_movimiento", "caja", "resistencia_agua"] as $f) {
                        $request->merge([$f => ""]);
                    }
                    $products = $this->runSearchQuery($request);
                }
            }

            // Generar sugerencias cuando sigue sin haber resultados
            if ($products->isEmpty()) {
                $suggestions = $this->buildSuggestions($originalQuery);
            }
        } else {
            $products = $this->runSearchQuery($request);
        }

        if ($originalQuery) {
            $ua = $request->userAgent();
            $deviceType = match (true) {
                preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile|wpdesktop/i', $ua) => 'mobile',
                preg_match('/tablet|ipad|playbook|silk/i', $ua) => 'tablet',
                default => 'desktop',
            };

            SearchLog::create([
                "query" => $originalQuery,
                "parsed_filters" => $parsedFilters,
                "used_ai" => $usedAi,
                "ai_response" => $aiResponse,
                "ai_raw_response" => $aiRawResponse,
                "ai_skipped_reason" => $aiSkippedReason,
                "user_id" => $request->user()?->id,
                "ip_address" => $request->ip(),
                "real_ip" => $request->header('CF-Connecting-IP') ?: $request->ip(),
                "user_agent" => $ua,
                "device_type" => $deviceType,
                "results_count" => $products->count(),
                "suggestions" => $suggestions->isNotEmpty() ? $suggestions->toArray() : null,
            ]);
        }

        $gender = $request->filled("gender") ? $request->gender : null;

        $filters = $this->buildFilters($gender);

        $searchQuery = $originalQuery;

        return view("pages.catalog", compact("products", "filters", "gender", "searchQuery", "suggestions"));
    }

    private function applyParsedFilters(Request $request, array $parsed): void
    {
        foreach ($parsed as $key => $value) {
            if ($key === "gender") {
                $request->merge(["gender" => $value]);
            } elseif ($key === "color") {
                $request->merge(["color" => $value]);
            } elseif ($key === "coleccion") {
                $request->merge(["coleccion" => $value]);
            } elseif ($key === "brazalete") {
                $request->merge(["brazalete" => $value]);
            } elseif ($key === "tipo_movimiento") {
                $request->merge(["tipo_movimiento" => $value]);
            } elseif ($key === "caja") {
                $request->merge(["caja" => $value]);
            } elseif ($key === "resistencia_agua") {
                $request->merge(["resistencia_agua" => $value]);
            }
        }
    }

    private function runSearchQuery(Request $request): \Illuminate\Support\Collection
    {
        return app(CatalogService::class)->filteredFromRequest($request);
    }

    /**
     * Construye sugerencias cuando una busqueda no produce resultados.
     * Estrategia:
     *  1. Si la query parece un numero de modelo, buscar modelos con numero
     *     similar (distancia de edicion) en el catalogo activo.
     *  2. Buscar productos cuyo modelo/title coleccion/color contengan
     *     algun token significativo de la query.
     *  3. Relajar los filtros extraidos (ej: quitar gender si la combinacion
     *     coleccion+gender no existe).
     */
    private function buildSuggestions(?string $query): \Illuminate\Support\Collection
    {
        if (! $query) {
            return collect();
        }

        $suggestions = collect();
        $queryLower = mb_strtolower(trim($query));

        // 1. Numero de modelo: fuzzy match por distancia de edicion
        if (preg_match('/\d{3,}/', $query, $numMatch)) {
            $needle = $numMatch[0];
            $candidates = Product::where('activo', true)
                ->where('stock', '>', 0)
                ->where('precio_venta', '>', 0)
                ->select(['id', 'modelo', 'title', 'slug', 'imagen', 'precio_venta', 'descuento', 'coleccion', 'size', 'tipo_movimiento', 'genero', 'proximo', 'stock'])
                ->get();

            $scored = $candidates->map(function ($p) use ($needle) {
                if (! preg_match('/\d{3,}/', $p->modelo, $m)) {
                    return null;
                }
                $dist = levenshtein($needle, $m[0]);

                return ['product' => $p, 'dist' => $dist];
            })->filter()->sortBy('dist')->take(4);

            foreach ($scored as $item) {
                if ($item['dist'] <= max(2, (int) (strlen($needle) * 0.3))) {
                    $suggestions->push($item['product']);
                }
            }
        }

        if ($suggestions->isNotEmpty()) {
            return $suggestions->unique('id')->values();
        }

        // 2. Buscar por tokens significativos de la query
        $stopWords = ['reloj', 'relojes', 'invicta', 'de', 'para', 'con', 'el', 'la', 'los', 'las', 'un', 'una', 'modelo', 'ver', 'buscar'];
        $tokens = array_values(array_filter(
            preg_split('/\s+/', $queryLower),
            fn ($w) => strlen($w) >= 3 && ! in_array($w, $stopWords)
        ));

        if (! empty($tokens)) {
            $tokenQuery = Product::where('activo', true)
                ->where('stock', '>', 0)
                ->where('precio_venta', '>', 0)
                ->where(function ($q) use ($tokens) {
                    foreach ($tokens as $t) {
                        $q->orWhere('modelo', 'like', "%{$t}%")
                          ->orWhere('title', 'like', "%{$t}%")
                          ->orWhere('coleccion', 'like', "%{$t}%")
                          ->orWhere('color', 'like', "%{$t}%");
                    }
                })
                ->select(['id', 'modelo', 'title', 'slug', 'imagen', 'precio_venta', 'descuento', 'coleccion', 'size', 'tipo_movimiento', 'genero', 'proximo', 'stock'])
                ->take(4)
                ->get();

            $suggestions = $suggestions->merge($tokenQuery);
        }

        // 3. Fallback: productos mas vistos
        if ($suggestions->isEmpty()) {
            $suggestions = Product::where('activo', true)
                ->where('stock', '>', 0)
                ->where('precio_venta', '>', 0)
                ->orderByDesc('vistas')
                ->select(['id', 'modelo', 'title', 'slug', 'imagen', 'precio_venta', 'descuento', 'coleccion', 'size', 'tipo_movimiento', 'genero', 'proximo', 'stock'])
                ->take(4)
                ->get();
        }

        return $suggestions->unique('id')->values();
    }

    public function byGender(Request $request, string $gender)
    {
        $allowed = ["hombre", "mujer", "unisex"];
        if (!in_array($gender, $allowed)) {
            abort(404);
        }

        $query = $request->except("gender");
        $query["gender"] = $gender;
        $url = route("products.index") . "?" . http_build_query($query);

        return redirect($url, 301);
    }

    /**
     * Build the available filter options, optionally scoped by gender.
     * Se cachea 1 día (los refinamientos de filtros dependen de stock/precio).
     */
    private function buildFilters(?string $gender = null): array
    {
        $raw = cache()->remember("product:filters:" . ($gender ?? 'all'), now()->addDay(), function () use ($gender) {
            $base = Product::where("activo", true)->where("precio_venta", ">", 0)->where("stock", ">", 0);
            if ($gender) {
                $base->where("genero", $gender);
            }

            $resistencias = (clone $base)
                ->whereNotNull("resistencia_agua")
                ->distinct()
                ->pluck("resistencia_agua")
                ->map(fn($v) => preg_replace('/[^0-9]/', '', $v))
                ->filter()
                ->unique()
                ->sort(fn($a, $b) => (int)$a - (int)$b)
                ->values();

            return [
                "colors" => (clone $base)->whereNotNull("color")->distinct()->pluck("color")->sortBy(fn($v) => mb_strtolower($v), SORT_NATURAL)->values()->toArray(),
                "brazaletes" => (clone $base)->whereNotNull("brazalete")->distinct()->pluck("brazalete")->values()->toArray(),
                "colecciones" => (clone $base)->whereNotNull("coleccion")->distinct()->pluck("coleccion")->sortBy(fn($v) => mb_strtolower($v), SORT_NATURAL)->values()->toArray(),
                "movimientos" => (clone $base)->whereNotNull("tipo_movimiento")->distinct()->pluck("tipo_movimiento")->values()->toArray(),
                "cajas" => (clone $base)->whereNotNull("caja")->where("caja", "!=", "")->distinct()->pluck("caja")->values()->toArray(),
                "resistencias" => $resistencias->values()->toArray(),
                "sizes" => ["35-39", "40-44", "45-49", "50+"],
            ];
        });

        return [
            "colors" => collect($raw["colors"]),
            "brazaletes" => collect($raw["brazaletes"]),
            "colecciones" => collect($raw["colecciones"]),
            "movimientos" => collect($raw["movimientos"]),
            "cajas" => collect($raw["cajas"]),
            "resistencias" => collect($raw["resistencias"]),
            "sizes" => collect($raw["sizes"]),
        ];
    }

    public function show(string $slug)
    {
        $product = Product::where("slug", $slug)->where("activo", true)->firstOrFail();

        $product->increment("vistas");

        $product->loadMissing('images');

        [$images, $galleryImages, $galleryItems] = cache()->remember("product:gallery:{$product->id}", now()->addDay(), function () use ($product) {
            $cdnBase = 'https://cdn.invictacostarica.com';
            $r2Available = false;
            try {
                $r2 = \Illuminate\Support\Facades\Storage::disk('r2');
                $r2Available = $r2->exists('relojes') || true; // disk exists if it didn't throw
            } catch (\Exception $e) {
                $r2 = null;
            }

            $images = collect([$product->imagen]);
            foreach ($product->images as $img) {
                $images->push($img->url);
            }
            $images->push("{$cdnBase}/caja.webp");
            $images = $images->filter()->unique()->values();

            $galleryImages = $images->map(function ($img) use ($cdnBase, $r2, $r2Available) {
                // Si es URL CDN, extraer ruta relativa para verificar
                $checkImg = $img;
                if (str_starts_with($img, 'https://cdn.invictacostarica.com')) {
                    $checkImg = str_replace('https://cdn.invictacostarica.com', '', $img);
                }
                // Normalizar: quitar /storage del inicio si existe
                $checkImg = preg_replace('#^/storage#', '', $checkImg);

                if (str_starts_with($checkImg, '/relojes/') && !str_contains($checkImg, '/large/') && !str_contains($checkImg, '/medium/') && !str_contains($checkImg, '/thumbs/')) {
                    $basename = basename($checkImg);
                    $modelo = pathinfo($basename, PATHINFO_FILENAME);
                    if ($modelo !== 'caja' && $r2Available && $r2 && $r2->exists("relojes/large/{$modelo}.webp")) {
                        return "{$cdnBase}/relojes/large/{$modelo}.webp";
                    }
                }
                return $img;
            });

            $galleryItems = collect();
            foreach ($images as $i => $img) {
                $galleryItems->push([
                    'type' => 'image',
                    'url' => $galleryImages[$i] ?? $img,
                    'zoomUrl' => $galleryImages[$i] ?? $img,
                ]);
            }
            if ($product->video_uid) {
                $videoThumb = "https://" . config('services.cloudflare.stream_customer_subdomain') . ".cloudflarestream.com/{$product->video_uid}/thumbnails/thumbnail.jpg?width=480";
                $galleryItems->push([
                    'type' => 'video',
                    'videoUid' => $product->video_uid,
                    'thumbnail' => $videoThumb,
                    'url' => $videoThumb,
                ]);
            }

            return [$images->values()->toArray(), $galleryImages->values()->toArray(), $galleryItems->values()->toArray()];
        });

        $images = collect($images);
        $galleryImages = collect($galleryImages);
        $galleryItems = collect($galleryItems);

        $relatedIds = cache()->remember("product:related:{$product->id}", now()->addDay(), function () use ($product) {
            return Product::relatedTo($product)
                ->take(8)
                ->pluck("id")
                ->toArray();
        });

        $relatedProducts = collect();
        if (!empty($relatedIds)) {
            $orderSegments = [];
            foreach ($relatedIds as $index => $id) {
                $orderSegments[] = "WHEN " . intval($id) . " THEN " . $index;
            }
            $orderByCase = "CASE id " . implode(" ", $orderSegments) . " END ASC";

            $relatedProducts = Product::whereIn("id", $relatedIds)
                ->orderByRaw($orderByCase)
                ->get();
        }

        $recentlyViewed = collect();
        $visitor = \App\Models\Visitor::currentFromRequest(request());
        if ($visitor) {
            $recentlyViewedIds = \App\Models\VisitorEvent::where('visitor_id', $visitor->id)
                ->where('type', 'product_view')
                ->whereNotNull('product_id')
                ->where('product_id', '!=', $product->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->pluck('product_id')
                ->unique()
                ->take(10)
                ->toArray();

            if (!empty($recentlyViewedIds)) {
                $recentlyViewed = Product::whereIn('id', $recentlyViewedIds)
                    ->whereIn('id', $recentlyViewedIds)
                    ->get()
                    ->sortBy(function ($p) use ($recentlyViewedIds) {
                        return array_search($p->id, $recentlyViewedIds);
                    });
            }
        }

        $deviceType = \App\Models\Visitor::parseDeviceType((string) request()->userAgent());
        $isMobile = $deviceType === 'mobile' || $deviceType === 'tablet';

        return view(
            "pages.product-detail",
            compact("product", "images", "galleryImages", "relatedProducts", "galleryItems", "recentlyViewed", "isMobile"),
        );
    }

    public function markAgotado(string $slug)
    {
        $product = Product::where("slug", $slug)->firstOrFail();

        $product->update([
            "stock" => 0,
            "disponibilidad" => "agotado",
        ]);

        Product::forgetAllCache($product->id);

        try {
            $baseUrl = config('app.url', 'https://invictacostarica.com');
            app(\App\Services\CloudflareCacheService::class)->purgeUrls([
                "{$baseUrl}/relojes/" . $product->slug,
            ]);
        } catch (\Exception $e) {
            //
        }

        return redirect()->route("products.show", $product->slug)->with("status", "Producto marcado como agotado.");
    }

}
