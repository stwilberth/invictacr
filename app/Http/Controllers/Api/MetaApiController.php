<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MetaApiController extends Controller
{
    public function catalog()
    {
        $products = Product::where('activo', true)
            ->where('precio_venta', '>', 0)
            ->where('stock', '>', 0)
            ->where('disponibilidad', '!=', 'agotado')
            ->get();

        $xml = '<?xml version="1.0"?>';
        $xml .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"><channel>';
        $xml .= '<title>Invicta Costa Rica</title><link>' . config('app.url') . '</link>';

        foreach ($products as $product) {
            $price = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);
            $xml .= '<item>';
            $xml .= "<g:id>{$product->modelo}</g:id>";
            $xml .= "<g:title>Reloj Invicta {$product->modelo}</g:title>";
            $xml .= "<g:description>" . e($product->descripcion ?? 'Reloj Invicta original') . "</g:description>";
            $xml .= "<g:link>" . route('products.show', ['slug' => $product->slug]) . "</g:link>";
            $xml .= "<g:image_link>{$product->imagen}</g:image_link>";
            $xml .= "<g:price>{$price} CRC</g:price>";
            $xml .= "<g:condition>new</g:condition>";
            $xml .= "<g:availability>" . ($product->stock > 0 ? 'in_stock' : 'out_of_stock') . "</g:availability>";
            $xml .= "<g:brand>Invicta</g:brand>";
            $xml .= '</item>';
        }

        $xml .= '</channel></rss>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function token(Request $request)
    {
        $token = Setting::where('key', 'meta_token')->first();
        return response()->json(['token' => $token?->value]);
    }

    public function storeToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        Setting::updateOrCreate(['key' => 'meta_token'], ['value' => $request->token]);
        return response()->json(['success' => true]);
    }
}
