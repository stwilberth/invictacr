<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SearchLog;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $aiResponse = null;
        $aiRawResponse = null;
        $usedAi = false;
        $parsedFilters = [];
        $originalQuery = $request->input("q");

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

            if ($products->total() === 0 && $originalQuery) {
                $aiFilters = $search->parseWithDeepSeek($originalQuery);

                $usedAi = $search->usedAi;
                $aiResponse = $search->aiResponse;
                $aiRawResponse = $search->aiRawResponse;

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

                if ($products->total() === 0) {
                    $request->merge(["q" => $originalQuery]);
                    foreach (["gender", "color", "coleccion", "brazalete", "tipo_movimiento", "caja", "resistencia_agua"] as $f) {
                        $request->merge([$f => ""]);
                    }
                    $products = $this->runSearchQuery($request);
                }
            }
        } else {
            $products = $this->runSearchQuery($request);
        }

        if ($originalQuery) {
            SearchLog::create([
                "query" => $originalQuery,
                "parsed_filters" => $parsedFilters,
                "used_ai" => $usedAi,
                "ai_response" => $aiResponse,
                "ai_raw_response" => $aiRawResponse,
                "user_id" => $request->user()?->id,
                "ip_address" => $request->ip(),
                "results_count" => $products->total(),
            ]);
        }

        $gender = $request->filled("gender") ? $request->gender : null;

        $filters = $this->buildFilters($gender);

        return view("pages.catalog", compact("products", "filters", "gender"));
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

    private function runSearchQuery(Request $request)
    {
        $query = Product::where("activo", true)
            ->where("precio_venta", ">", 0)
            ->where("stock", ">", 0);

        if ($request->filled("q")) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where("modelo", "like", "%{$term}%")
                  ->orWhere("title", "like", "%{$term}%")
                  ->orWhere("descripcion", "like", "%{$term}%")
                  ->orWhere("coleccion", "like", "%{$term}%")
                  ->orWhere("color", "like", "%{$term}%")
                  ->orWhere("genero", "like", "%{$term}%")
                  ->orWhere("brazalete", "like", "%{$term}%")
                  ->orWhere("tipo_movimiento", "like", "%{$term}%");
            });
        }

        if ($request->filled("gender")) {
            $query->whereRaw("LOWER(genero) = ?", [mb_strtolower($request->gender)]);
        }
        if ($request->filled("color")) {
            $query->whereRaw("LOWER(color) = ?", [mb_strtolower($request->color)]);
        }
        if ($request->filled("brazalete")) {
            $query->whereRaw("LOWER(brazalete) = ?", [mb_strtolower($request->brazalete)]);
        }
        if ($request->filled("coleccion")) {
            $query->whereRaw("LOWER(coleccion) = ?", [mb_strtolower($request->coleccion)]);
        }
        if ($request->filled("tipo_movimiento")) {
            $query->whereRaw("LOWER(tipo_movimiento) = ?", [mb_strtolower($request->tipo_movimiento)]);
        }
        if ($request->filled("caja")) {
            $query->whereRaw("LOWER(caja) = ?", [mb_strtolower($request->caja)]);
        }
        if ($request->filled("resistencia_agua")) {
            $query->whereRaw("CAST(resistencia_agua AS UNSIGNED) = ?", [(int) $request->resistencia_agua]);
        }
        if ($request->filled("size")) {
            $query->where("size", $request->size);
        }
        if ($request->filled("precio_min")) {
            $query->where("precio_venta", ">=", $request->precio_min);
        }
        if ($request->filled("precio_max")) {
            $query->where("precio_venta", "<=", $request->precio_max);
        }

        $sortField = match ($request->sort) {
            "price_asc" => ["precio_venta", "asc"],
            "price_desc" => ["precio_venta", "desc"],
            "name_asc" => ["title", "asc"],
            "name_desc" => ["title", "desc"],
            "newest" => ["created_at", "desc"],
            default => ["created_at", "desc"],
        };
        $query->orderBy($sortField[0], $sortField[1]);

        return $query->paginate(48)->withQueryString();
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
     */
    private function buildFilters(?string $gender = null): array
    {
        $base = Product::where("activo", true)->where("stock", ">", 0);
        if ($gender) {
            $base->where("genero", $gender);
        }

        return [
            "colors" => (clone $base)
                ->whereNotNull("color")
                ->distinct()
                ->pluck("color")
                ->sortBy(fn($v) => mb_strtolower($v), SORT_NATURAL)
                ->values(),
            "brazaletes" => (clone $base)
                ->whereNotNull("brazalete")
                ->distinct()
                ->pluck("brazalete"),
            "colecciones" => (clone $base)
                ->whereNotNull("coleccion")
                ->distinct()
                ->pluck("coleccion")
                ->sortBy(fn($v) => mb_strtolower($v), SORT_NATURAL)
                ->values(),
            "movimientos" => (clone $base)
                ->whereNotNull("tipo_movimiento")
                ->distinct()
                ->pluck("tipo_movimiento"),
            "cajas" => (clone $base)
                ->whereNotNull("caja")
                ->where("caja", "!=", "")
                ->distinct()
                ->pluck("caja"),
            "resistencias" => (clone $base)
                ->whereNotNull("resistencia_agua")
                ->distinct()
                ->pluck("resistencia_agua")
                ->map(fn($v) => preg_replace('/[^0-9]/', '', $v))
                ->filter()
                ->unique()
                ->sort(fn($a, $b) => (int)$a - (int)$b)
                ->values(),
            "sizes" => collect(["35-39", "40-44", "45-49", "50+"]),
        ];
    }

    public function show(string $gender, string $slug)
    {
        $product = Product::where("slug", $slug)
            ->where("activo", true)
            ->firstOrFail();

        $product->increment("vistas");

        $images = collect([$product->imagen]);
        for ($i = 1; $i <= 5; $i++) {
            $extraImage = str_replace(".jpg", "_{$i}.jpg", $product->imagen);
            if ($extraImage !== $product->imagen) {
                $images->push($extraImage);
            }
        }
        if ($product->imagenes_extra) {
            foreach ($product->imagenes_extra as $img) {
                $images->push($img);
            }
        }
        $images = $images->unique()->values();

        $relatedProducts = Product::where("activo", true)
            ->where("precio_venta", ">", 0)
            ->where("stock", ">", 0)
            ->where("id", "!=", $product->id)
            ->where(function ($q) use ($product) {
                $q->where("coleccion", $product->coleccion)
                    ->orWhere("color", $product->color)
                    ->orWhere("genero", $product->genero);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view(
            "pages.product-detail",
            compact("product", "images", "relatedProducts"),
        );
    }

}
