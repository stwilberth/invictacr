<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $hideProximo = $request->input('proximo') === '0';
        if ($hideProximo) {
            $query = Product::where("activo", true)->where("precio_venta", ">", 0)->where("stock", ">", 0)->where("proximo", false);
        } else {
            $query = Product::where("activo", true)->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where("precio_venta", ">", 0)->where("stock", ">", 0);
                })->orWhere("proximo", true);
            });
        }

        if ($request->filled("q")) {
            Product::applyTextSearch($query, $request->q);
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
            default => ["created_at", "desc"],
        };
        $query->orderBy($sortField[0], $sortField[1]);

        $products = $query->paginate(48);

        $html = '';
        foreach ($products as $product) {
            $html .= view("components.product-card", ["product" => $product])->render();
        }

        return response()->json([
            "html" => $html,
            "total" => $products->total(),
            "currentPage" => $products->currentPage(),
            "totalPages" => $products->lastPage(),
        ]);
    }
}
