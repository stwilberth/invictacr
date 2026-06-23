<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SyncLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StockApiController extends Controller
{
    public function sync()
    {
        $log = SyncLog::create(['type' => 'stock', 'status' => 'running']);

        try {
            $response = Http::get('https://variedadescr.com/api/productos/stock?marca=67');
            $products = $response->json();

            $count = 0;
            foreach ($products as $item) {
                $modelo = $item['modelo'] ?? $item['model'];
                if (!$modelo) continue;

                Product::updateOrCreate(
                    ['modelo' => $modelo],
                    [
                        'stock' => $item['stock'] ?? 0,
                        'precio_venta' => ($item['precio'] ?? 0) * 1.15,
                        'precio_original' => $item['precio'] ?? null,
                    ]
                );
                $count++;
            }

            $log->update(['status' => 'completed', 'message' => "{$count} productos sincronizados"]);
            return response()->json(['success' => true, 'count' => $count]);
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'message' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
