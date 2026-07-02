<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $terms = [$query];
        if (str_ends_with($query, 's')) {
            $terms[] = rtrim($query, 's');
        }

        $products = Product::where('activo', true)
            ->where('precio_venta', '>', 0)
            ->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->orWhere('title', 'like', "%{$term}%")
                      ->orWhere('modelo', 'like', "%{$term}%")
                      ->orWhere('descripcion', 'like', "%{$term}%")
                      ->orWhere('coleccion', 'like', "%{$term}%")
                      ->orWhere('color', 'like', "%{$term}%")
                      ->orWhere('genero', 'like', "%{$term}%")
                      ->orWhere('brazalete', 'like', "%{$term}%")
                      ->orWhere('tipo_movimiento', 'like', "%{$term}%");
                }
            })
            ->paginate(48);

        return view('pages.search', compact('products', 'query'));
    }
}
