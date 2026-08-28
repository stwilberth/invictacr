<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class LiveSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $catalog = app(CatalogService::class);

        $filters = $catalog->extractFilters($request);

        $cacheKey = 'product:grid:v' . $catalog->version() . ':' . md5(json_encode($filters));

        $payload = cache()->remember($cacheKey, now()->addMinutes(60), function () use ($catalog, $filters) {
            $products = $catalog->filtered($filters);

            $html = '';
            foreach ($products as $product) {
                $html .= view('components.product-card', ['product' => $product])->render();
            }

            return [
                'html' => $html,
                'total' => $products->count(),
            ];
        });

        return response()->json($payload);
    }
}