<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UtilityApiController extends Controller
{
    public function sitemap()
    {
        $products = Product::where('activo', true)->where('precio_venta', '>', 0)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $staticPages = ['/', '/relojes', '/como-comprar', '/formas-pago', '/informacion-de-envio', '/garantia', '/resistencia-agua', '/resenas', '/sobre-nosotros'];
        foreach ($staticPages as $page) {
            $xml .= '<url><loc>' . url($page) . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
        }

        foreach ($products as $product) {
            $url = route('products.show', ['slug' => $product->slug]);
            $xml .= '<url><loc>' . $url . '</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>';
        }

        $xml .= '</urlset>';
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function clearCache()
    {
        Cache::flush();
        return response()->json(['success' => true, 'message' => 'Caché limpiado']);
    }
}
