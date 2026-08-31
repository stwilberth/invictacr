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

        $staticPages = [
            '/' => ['changefreq' => 'weekly', 'priority' => '1.0'],
            '/relojes' => ['changefreq' => 'daily', 'priority' => '0.9'],
            '/como-comprar' => ['changefreq' => 'monthly', 'priority' => '0.7'],
            '/formas-pago' => ['changefreq' => 'monthly', 'priority' => '0.7'],
            '/informacion-de-envio' => ['changefreq' => 'monthly', 'priority' => '0.7'],
            '/garantia' => ['changefreq' => 'monthly', 'priority' => '0.7'],
            '/resistencia-agua' => ['changefreq' => 'monthly', 'priority' => '0.7'],
            '/sobre-nosotros' => ['changefreq' => 'monthly', 'priority' => '0.6'],
        ];

        foreach ($staticPages as $page => $meta) {
            $xml .= '<url><loc>' . url($page) . '</loc><changefreq>' . $meta['changefreq'] . '</changefreq><priority>' . $meta['priority'] . '</priority></url>';
        }

        foreach ($products as $product) {
            $url = route('products.show', ['slug' => $product->slug]);
            $lastmod = $product->updated_at ? $product->updated_at->toW3cString() : '';
            $xml .= '<url><loc>' . $url . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>';
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
