<?php

namespace App\Console\Commands;

use App\Models\DownloadHistory;
use App\Models\Product;
use App\Services\CatalogService;
use App\Services\FacebookBusinessService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishFacebookPending extends Command
{
    protected $signature = 'campaigns:publish-facebook {--limit=3 : Cuántos relojes pendientes publicar al azar}';

    protected $description = 'Publica en Facebook los relojes pendientes (no descendidos/publicados) al azar';

    public function handle(FacebookBusinessService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Facebook no está configurado. Revisá META_ACCESS_TOKEN y META_PAGE_ID.');
            return Command::FAILURE;
        }

        $limit = max(1, (int) $this->option('limit'));

        $downloadedIds = DownloadHistory::pluck('product_id')->flip();

        $pending = (new CatalogService())->baseProducts()
            ->filter(fn (Product $p) => (float) $p->precio_venta > 0 && (int) $p->stock > 0)
            ->reject(fn (Product $p) => $downloadedIds->has($p->id))
            ->values();

        if ($pending->isEmpty()) {
            $this->info('No hay relojes pendientes por publicar.');
            return Command::SUCCESS;
        }

        $this->info("Hay {$pending->count()} relojes pendientes. Publicando hasta {$limit} al azar...");

        $published = 0;

        foreach ($pending->shuffle()->take($limit) as $product) {
            try {
                $message = $this->buildMessage($product);

                $postId = $service->publishPhotoPost($product->imagen, $message, $this->productUrl($product));

                if (!$postId) {
                    $this->error("No se pudo publicar {$product->modelo} a Facebook.");
                    continue;
                }

                DownloadHistory::create([
                    'product_id' => $product->id,
                    'model_code' => $product->modelo,
                    'product_image' => $product->imagen,
                    'text_content' => $message,
                ]);

                $this->info("Publicado {$product->modelo} (post {$postId}).");
                $published++;
            } catch (\Throwable $e) {
                Log::error("Facebook publish failed for {$product->modelo}: " . $e->getMessage());
                $this->error("Error al publicar {$product->modelo}: " . $e->getMessage());
            }
        }

        $this->info("Listo: {$published} relojes publicados.");
        return Command::SUCCESS;
    }

    private function buildMessage(Product $product): string
    {
        $formattedPrice = '₡' . number_format((float) $product->price_after_discount, 0);
        $modelo = $product->modelo;
        $coleccion = $product->coleccion ? strtoupper($product->coleccion) : 'INVICTA';
        $size = $product->size;
        $mov = $product->tipo_movimiento;

        $headline = "{$coleccion} {$modelo} – El estilo que merecés";
        $body = "✨ Conocé el {$modelo} de Invicta.\n\n"
              . "✅ Diseño {$size}mm\n"
              . "✅ Movimiento {$mov}\n"
              . "✅ Resistente al agua\n\n"
              . "💰 {$formattedPrice}\n"
              . "🚚 Envío gratis en GAM\n\n"
              . "📲 ¡Escríbenos al WhatsApp!";
        $cta = '¡Compra ahora!';

        return $headline . "\n\n" . $body . "\n\n" . $this->productUrl($product) . "\n\n" . $cta;
    }

    private function productUrl(Product $product): string
    {
        return route('products.show', ['slug' => $product->slug ?: $product->modelo]);
    }
}