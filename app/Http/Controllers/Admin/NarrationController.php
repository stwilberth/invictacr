<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Narration;
use App\Models\Product;
use App\Services\NarrationService;
use Illuminate\Http\Request;

/**
 * Genera la narración de audio del texto de campaña (formulario clásico,
 * no Livewire: el TTS puede tardar 10-30s).
 */
class NarrationController extends Controller
{
    public function store(Request $request, NarrationService $service)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'headline' => 'nullable|string|max:500',
            'body' => 'nullable|string|max:2000',
        ]);

        $product = Product::findOrFail($request->product_id);

        $script = $service->buildScript([
            'headline' => $request->headline ?? '',
            'body' => $request->body ?? '',
        ]);

        if (mb_strlen(trim($script)) < 10) {
            return response()->json(['error' => 'El texto es muy corto para narrar.'], 422);
        }

        try {
            $filename = 'invicta-' . preg_replace('/[^A-Za-z0-9]+/', '-', strtolower($product->modelo ?? 'reloj')) . '-' . $product->id . '-' . now()->format('Ymd-His') . '.mp3';
            $audioPath = $service->narrate($script, null, $filename);
        } catch (\Throwable $e) {
            \Log::error('Narración TTS falló: ' . $e->getMessage());

            return response()->json(['error' => 'No se pudo generar la narración: ' . $e->getMessage()], 500);
        }

        $narration = Narration::create([
            'product_id' => $product->id,
            'script' => $script,
            'audio_path' => $audioPath,
            'voice_id' => config('services.ai_gateway.voice'),
            'provider' => 'elevenlabs',
        ]);

        return response()->json([
            'id' => $narration->id,
            'audio_url' => $audioPath,
            'script' => $script,
        ]);
    }

    public function latest(Product $product)
    {
        $narration = Narration::where('product_id', $product->id)->latest()->first();

        if (! $narration) {
            return response()->json(['audio_url' => null]);
        }

        return response()->json([
            'id' => $narration->id,
            'audio_url' => $narration->audio_path,
            'created_at' => $narration->created_at->format('d/m/Y H:i'),
        ]);
    }
}
