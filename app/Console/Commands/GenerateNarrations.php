<?php

namespace App\Console\Commands;

use App\Models\Narration;
use App\Models\Product;
use App\Services\NarrationService;
use Illuminate\Console\Command;

/**
 * Agente batch de narraciones: genera el audio TTS de productos con
 * anuncio pendiente y sin narración (para videos de campaña).
 */
class GenerateNarrations extends Command
{
    protected $signature = 'narration:generate {--limit=5 : Cuántas narraciones generar} {--dry-run : Solo muestra qué haría}';

    protected $description = 'Genera narraciones de audio (TTS) para productos sin narración';

    public function handle(NarrationService $service): int
    {
        $limit = (int) $this->option('limit');
        $narratedIds = Narration::pluck('product_id')->filter()->flip();

        $products = Product::where('stock', '>', 0)
            ->where('precio_venta', '>', 0)
            ->orderBy('modelo')
            ->get()
            ->reject(fn (Product $p) => $narratedIds->has($p->id))
            ->take($limit);

        if ($products->isEmpty()) {
            $this->info('No hay productos pendientes de narración.');

            return self::SUCCESS;
        }

        foreach ($products as $product) {
            $price = $product->price_after_discount ?? $product->precio_venta;
            $script = $service->buildScript([
                'headline' => 'Invicta ' . ($product->coleccion ?? $product->modelo) . ' ' . $product->modelo,
                'body' => "Diseño {$product->size} milímetros, movimiento {$product->tipo_movimiento}. Precio " . number_format($price, 0) . " colones. Envío gratis en GAM. Escríbenos al WhatsApp.",
            ]);

            if ($this->option('dry-run')) {
                $this->line("[DRY-RUN] {$product->modelo}: " . mb_substr($script, 0, 80) . '...');
                continue;
            }

            try {
                $filename = 'invicta-' . preg_replace('/[^A-Za-z0-9]+/', '-', strtolower($product->modelo ?? 'reloj')) . '-' . $product->id . '-' . now()->format('Ymd-His') . '.mp3';
                $path = $service->narrate($script, null, $filename);

                Narration::create([
                    'product_id' => $product->id,
                    'script' => $script,
                    'audio_path' => $path,
                    'voice_id' => config('services.ai_gateway.voice'),
                    'provider' => 'elevenlabs',
                ]);

                $this->info("OK {$product->modelo} -> {$path}");
            } catch (\Throwable $e) {
                $this->error("FALLÓ {$product->modelo}: " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
