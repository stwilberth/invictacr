<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class LiveSearchController extends Controller
{
    /**
     * Devuelve la grilla de /relojes como HTML por bloques (scroll infinito).
     *
     * El HTML de cada tarjeta se cachea como array (una sola renderización por
     * combinación de filtros) y aquí solo se corta el bloque [offset, offset+limit).
     * Sin "limit" se devuelven todos los resultados (comportamiento anterior).
     */
    public function __invoke(Request $request)
    {
        $catalog = app(CatalogService::class);

        $filters = $catalog->extractFilters($request);

        $cacheKey = 'product:grid:html:v' . $catalog->version() . ':' . md5(json_encode($filters));

        $cards = cache()->remember($cacheKey, now()->addMinutes(30), function () use ($catalog, $filters) {
            $products = $catalog->attachImages($catalog->filtered($filters));

            $html = [];
            foreach ($products as $product) {
                $html[] = view('components.product-card', ['product' => $product])->render();
            }

            return $html;
        });

        $offset = max(0, (int) $request->input('offset', 0));
        $limit = max(0, (int) $request->input('limit', 0));

        $total = count($cards);
        $length = $limit > 0 ? min($limit, max(0, $total - $offset)) : max(0, $total - $offset);
        $slice = $length > 0 ? array_slice($cards, $offset, $length) : [];

        return response()->json([
            'html' => implode('', $slice),
            'total' => $total,
            'offset' => $offset,
            'count' => count($slice),
            'hasMore' => ($offset + count($slice)) < $total,
        ]);
    }
}
