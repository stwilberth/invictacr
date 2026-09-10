<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SearchLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    const PRIORITY_MODELS = [
        "49821",
        "49573",
        "48948",
        "50638",
        "50642",
        "49895",
        "50413",
    ];

    public function index()
    {
        $topSearches = SearchLog::select("query", DB::raw("COUNT(*) as count"))
            ->groupBy("query")
            ->orderByDesc("count")
            ->take(4)
            ->pluck("query")
            ->toArray();

        $activeProducts = Product::where("activo", true)
            ->where("precio_venta", ">", 0)
            ->get();

        $priorityProducts = $activeProducts
            ->filter(function ($product) {
                return in_array($product->modelo, self::PRIORITY_MODELS);
            })
            ->shuffle()
            ->take(10);

        if ($priorityProducts->count() < 10) {
            $existingIds = $priorityProducts->pluck("id");
            $fillers = $activeProducts
                ->reject(fn($p) => $existingIds->contains($p->id))
                ->shuffle()
                ->take(10 - $priorityProducts->count());
            $featuredProducts = $priorityProducts->concat($fillers);
        } else {
            $featuredProducts = $priorityProducts;
        }

        $discountProducts = $activeProducts
            ->where("descuento", ">", 0)
            ->sortByDesc("descuento")
            ->take(4);

        $categories = [
            [
                "name" => "Hombre",
                "slug" => "hombre",
                "image" => asset("images/banners/hombre.webp"),
                "accent" => "text-amber-500",
            ],
            [
                "name" => "Mujer",
                "slug" => "mujer",
                "image" => asset("images/banners/mujer.webp"),
                "accent" => "text-rose-400",
            ],
            [
                "name" => "Unisex",
                "slug" => "unisex",
                "image" => asset("images/banners/unisex.webp"),
                "accent" => "text-emerald-400",
            ],
        ];

        // Hero product: try Speedway 50413 first
        $heroProduct =
            $activeProducts->first(function ($p) {
                return str_contains($p->modelo, "50413");
            }) ?? $activeProducts->first();

        // Reseñas (R2 resennas/) para sección home
        $resenaVideos = Cache::remember('resenas_r2_home', 3600, function () {
            try {
                $files = Storage::disk('r2')->files('resennas');
            } catch (\Exception $e) {
                return [];
            }
            $mp4 = array_filter($files, fn($f) => str_ends_with(strtolower($f), '.mp4'));
            sort($mp4, SORT_NATURAL | SORT_FLAG_CASE);
            $mp4 = array_slice(array_values($mp4), 0, 6);
            return array_map(function ($path) {
                $encoded = implode('/', array_map('rawurlencode', explode('/', $path)));
                return [
                    'path' => $path,
                    'url' => "https://cdn.invictacostarica.com/{$encoded}",
                    'nombre' => pathinfo($path, PATHINFO_FILENAME),
                ];
            }, $mp4);
        });

        return view(
            "pages.home",
            compact(
                "featuredProducts",
                "discountProducts",
                "categories",
                "heroProduct",
                "activeProducts",
                "topSearches",
                "resenaVideos",
            ),
        );
    }
}
