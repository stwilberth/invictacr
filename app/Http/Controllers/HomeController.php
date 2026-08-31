<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SearchLog;
use Illuminate\Support\Facades\DB;

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

        return view(
            "pages.home",
            compact(
                "featuredProducts",
                "discountProducts",
                "categories",
                "heroProduct",
                "activeProducts",
                "topSearches",
            ),
        );
    }
}
