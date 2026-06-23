<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('activo', true)->where('precio_venta', '>', 0);

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }
        if ($request->filled('brazalete')) {
            $query->where('brazalete', $request->brazalete);
        }
        if ($request->filled('coleccion')) {
            $query->where('coleccion', $request->coleccion);
        }
        if ($request->filled('tipo_movimiento')) {
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }
        if ($request->filled('precio_min')) {
            $query->where('precio_venta', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio_venta', '<=', $request->precio_max);
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('modelo', 'like', '%' . $request->q . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->q . '%');
            });
        }

        $sortField = match ($request->sort) {
            'price_asc' => ['precio_venta', 'asc'],
            'price_desc' => ['precio_venta', 'desc'],
            'name_asc' => ['title', 'asc'],
            'name_desc' => ['title', 'desc'],
            'newest' => ['created_at', 'desc'],
            default => ['created_at', 'desc'],
        };
        $query->orderBy($sortField[0], $sortField[1]);

        $products = $query->paginate(48)->withQueryString();

        $filters = [
            'colors' => Product::where('activo', true)->whereNotNull('color')->distinct()->pluck('color'),
            'brazaletes' => Product::where('activo', true)->whereNotNull('brazalete')->distinct()->pluck('brazalete'),
            'colecciones' => Product::where('activo', true)->whereNotNull('coleccion')->distinct()->pluck('coleccion'),
            'movimientos' => Product::where('activo', true)->whereNotNull('tipo_movimiento')->distinct()->pluck('tipo_movimiento'),
            'sizes' => ['35-39', '40-44', '45-49', '50+'],
        ];

        return view('pages.catalog', compact('products', 'filters') + ['gender' => null]);
    }

    public function byGender(Request $request, string $gender)
    {
        $query = Product::where('activo', true)
            ->where('precio_venta', '>', 0)
            ->where('genero', $gender);

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }
        if ($request->filled('brazalete')) {
            $query->where('brazalete', $request->brazalete);
        }
        if ($request->filled('coleccion')) {
            $query->where('coleccion', $request->coleccion);
        }
        if ($request->filled('tipo_movimiento')) {
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }

        $sortField = match ($request->sort) {
            'price_asc' => ['precio_venta', 'asc'],
            'price_desc' => ['precio_venta', 'desc'],
            'name_asc' => ['title', 'asc'],
            'name_desc' => ['title', 'desc'],
            default => ['created_at', 'desc'],
        };
        $query->orderBy($sortField[0], $sortField[1]);

        $products = $query->paginate(48)->withQueryString();

        $filters = [
            'colors' => Product::where('activo', true)->where('genero', $gender)->whereNotNull('color')->distinct()->pluck('color'),
            'brazaletes' => Product::where('activo', true)->where('genero', $gender)->whereNotNull('brazalete')->distinct()->pluck('brazalete'),
            'colecciones' => Product::where('activo', true)->where('genero', $gender)->whereNotNull('coleccion')->distinct()->pluck('coleccion'),
            'movimientos' => Product::where('activo', true)->where('genero', $gender)->whereNotNull('tipo_movimiento')->distinct()->pluck('tipo_movimiento'),
        ];

        return view('pages.catalog', compact('products', 'filters', 'gender'));
    }

    public function show(string $gender, string $slug)
    {
        $product = Product::where('slug', $slug)->where('activo', true)->firstOrFail();

        $product->increment('vistas');

        $images = collect([$product->imagen]);
        for ($i = 1; $i <= 5; $i++) {
            $extraImage = str_replace('.jpg', "_{$i}.jpg", $product->imagen);
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

        $relatedProducts = Product::where('activo', true)
            ->where('precio_venta', '>', 0)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('coleccion', $product->coleccion)
                  ->orWhere('color', $product->color)
                  ->orWhere('genero', $product->genero);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('pages.product-detail', compact('product', 'images', 'relatedProducts'));
    }
}
