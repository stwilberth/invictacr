<?php

namespace App\Console\Commands;

use App\Http\Controllers\AdImageController;
use App\Models\Product;
use App\Models\StoryHistory;
use App\Services\CatalogService;
use App\Services\FacebookBusinessService;
use App\Services\InstagramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PublishStoryPending extends Command
{
    protected $signature = 'campaigns:publish-story {--limit=1 : Cuántas historias publicar al azar} {--product= : Modelo específico a publicar (para pruebas)} {--channel=facebook : Canal: facebook, instagram o both}';

    protected $description = 'Publica historias en Facebook/Instagram con el arte vertical 1080x1920';

    public function handle(FacebookBusinessService $fb, InstagramService $ig): int
    {
        $channel = strtolower((string) $this->option('channel'));
        if (!in_array($channel, ['facebook', 'instagram', 'both'], true)) {
            $this->error("Canal inválido: {$channel}. Usá facebook, instagram o both.");
            return Command::FAILURE;
        }

        $channels = $channel === 'both' ? ['facebook', 'instagram'] : [$channel];

        foreach ($channels as $ch) {
            $service = $ch === 'facebook' ? $fb : $ig;
            if (!$service->isConfigured()) {
                $this->warn(ucfirst($ch) . ' no está configurado. Revisá el token y ' . ($ch === 'instagram' ? 'IG_ACCOUNT_ID.' : 'META_PAGE_ID.'));
                continue;
            }
        }

        $limit = max(1, (int) $this->option('limit'));
        $published = 0;

        foreach ($channels as $ch) {
            $service = $ch === 'facebook' ? $fb : $ig;
            if (!$service->isConfigured()) {
                continue;
            }

            $storiedIds = StoryHistory::where('channel', $ch)->pluck('product_id')->flip();

            if ($this->option('product')) {
                $code = preg_replace('/^invicta-/i', '', trim((string) $this->option('product')));
                $product = Product::where('modelo', $code)
                    ->orWhere('modelo', 'invicta-' . $code)
                    ->first();

                if (!$product) {
                    $this->error("No se encontró el modelo {$code}.");
                    continue;
                }

                $pending = collect([$product]);
            } else {
                $pending = (new CatalogService())->baseProducts()
                    ->filter(fn (Product $p) => (float) $p->precio_venta > 0 && (int) $p->stock > 0)
                    ->reject(fn (Product $p) => $storiedIds->has($p->id))
                    ->values();
            }

            if ($pending->isEmpty()) {
                $this->info("No hay relojes pendientes para historias en {$ch}.");
                continue;
            }

            $this->info("Publicando hasta {$limit} historia(s) en {$ch}...");

            foreach ($pending->shuffle()->take($limit) as $product) {
                try {
                    $png = (new AdImageController())->generateStory($product);

                    if ($ch === 'facebook') {
                        $storyId = $fb->publishPhotoStory($png, $product->modelo . '-story.png');
                    } else {
                        $url = $this->uploadStoryImage($png, $product);
                        if (!$url) {
                            $this->error("No se pudo subir la imagen de {$product->modelo} a R2.");
                            continue;
                        }
                        $storyId = $ig->publishStory($url);
                    }

                    if (!$storyId) {
                        $this->error("No se pudo publicar la historia de {$product->modelo} en {$ch}.");
                        continue;
                    }

                    StoryHistory::create([
                        'product_id' => $product->id,
                        'model_code' => $product->modelo,
                        'story_id' => $storyId,
                        'channel' => $ch,
                        'text_content' => 'INVICTA ' . strtoupper($product->coleccion ?? $product->modelo ?? ''),
                    ]);

                    $this->info("Historia de {$product->modelo} publicada en {$ch} (id {$storyId}).");
                    $published++;
                } catch (\Throwable $e) {
                    Log::error("Story publish failed for {$product->modelo} ({$ch}): " . $e->getMessage());
                    $this->error("Error con {$product->modelo} en {$ch}: " . $e->getMessage());
                }
            }
        }

        $this->info("Listo: {$published} historia(s) publicada(s).");
        return Command::SUCCESS;
    }

    private function uploadStoryImage(string $png, Product $product): ?string
    {
        try {
            $key = 'historias/' . preg_replace('/[^a-z0-9-]+/i', '-', (string) $product->modelo) . '-' . now()->format('Ymd-His') . '.png';
            Storage::disk('r2')->put($key, $png, 'public');
            return 'https://cdn.invictacostarica.com/' . $key;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
}
