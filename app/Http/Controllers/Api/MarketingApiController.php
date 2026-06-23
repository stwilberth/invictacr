<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarketingApiController extends Controller
{
    public function generateDescription(Request $request)
    {
        $request->validate([
            'modelo' => 'required|string',
            'coleccion' => 'nullable|string',
            'caracteristicas' => 'nullable|string',
        ]);

        $prompt = "Genera una descripción breve en español para un reloj Invicta modelo {$request->modelo}";
        if ($request->coleccion) $prompt .= " de la colección {$request->coleccion}";
        $prompt .= ". Máximo 2 oraciones, tono profesional pero amigable.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.deepseek.key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => 150,
            ]);

            $description = $response->json('choices.0.message.content');
            return response()->json(['description' => $description]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generateAdContent(Request $request)
    {
        $request->validate(['producto_id' => 'required|exists:products,id']);
        $product = Product::findOrFail($request->producto_id);

        $price = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);

        return response()->json([
            'title' => "🔥 Reloj Invicta {$product->modelo} - ¡Original!",
            'description' => "✨ Modelo: {$product->modelo}\n📏 Tamaño: {$product->size}MM\n⚙️ Movimiento: {$product->tipo_movimiento}\n💰 Precio: ₡" . number_format($price, 0) . "\n🚚 Envío gratis en GAM\n📲 Escríbenos al WhatsApp!",
            'model' => $product->modelo,
            'price' => $price,
            'image' => $product->imagen,
        ]);
    }
}
