<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('activo', true)
            ->where('precio_venta', '>', 0)
            ->with('images')
            ->where(function ($q) use ($query) {
                Product::applyTextSearch($q, $query ?? '');
            })
            ->paginate(48);

        return view('pages.search', compact('products', 'query'));
    }
}
