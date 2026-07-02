<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index()
    {
        return Product::where("activo", true)
            ->where("precio_venta", ">", 0)
            ->orderBy("modelo")
            ->get([
                "id",
                "modelo",
                "title",
                "slug",
                "genero",
                "precio_venta",
                "descuento",
                "imagen",
                "stock",
                "coleccion",
                "size",
                "tipo_movimiento",
                "color",
                "brazalete",
            ]);
    }

    public function show($modelo)
    {
        return Product::where("modelo", $modelo)->firstOrFail();
    }

    public function search(Request $request)
    {
        $q = $request->input("q");
        $terms = [$q];
        if (str_ends_with($q, 's')) {
            $terms[] = rtrim($q, 's');
        }
        return Product::where("activo", true)
            ->where("precio_venta", ">", 0)
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query
                        ->orWhere("modelo", "like", "%{$term}%")
                        ->orWhere("title", "like", "%{$term}%")
                        ->orWhere("coleccion", "like", "%{$term}%")
                        ->orWhere("color", "like", "%{$term}%")
                        ->orWhere("genero", "like", "%{$term}%")
                        ->orWhere("brazalete", "like", "%{$term}%")
                        ->orWhere("tipo_movimiento", "like", "%{$term}%");
                }
            })
            ->orderBy("modelo")
            ->take(10)
            ->get([
                "id",
                "modelo",
                "title",
                "slug",
                "genero",
                "imagen",
                "precio_venta",
                "coleccion",
            ]);
    }

    public function trackView(Request $request)
    {
        $request->validate(["modelo" => "required|string"]);
        Product::where("modelo", $request->modelo)->increment("vistas");
        return response()->json(["success" => true]);
    }
}
