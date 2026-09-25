<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\CloudflareCacheService;
use App\Services\ImageOptimizerService;
use App\Services\InvictaWatchScraper;
use App\Services\PricingService;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductForm extends Component
{
    public $productId;
    public $modelo, $title, $slug, $descripcion, $color, $brazalete;
    public $coleccion, $tipo_movimiento, $size, $genero, $caja;
    public $resistencia_agua, $precio_venta, $precio_original, $precio_costo;
    public $video_uid;
    public $video_thumb_time;
    public $video_extra_scenes = [];
    public $scenesSuggested = false;
    public ?string $originalVideoUid = null;
    public $descuento = 0,
        $stock = 0,
        $imagen,
        $activo = true;
    public $bloqueado = false,
        $proximo = false,
        $manual_override = false;
    public $disponibilidad = 'disponible';

    public $precioMinimo = null;
    public $precioSugerido = null;
    public $costoSugerido = null;
    public $margenEsperado = null;
    public $descuentoMaximo = null;
    public $downloadStatus = "";
    public $downloadMessage = "";
    public $fetchStatus = "";
    public $fetchMessage = "";
    public ?string $optimizeStatus = null;
    public ?string $optimizeMessage = null;
    public string $videoDeleteStatus = "";
    public string $videoDeleteMessage = "";

    public function mount($productId = null)
    {
        if ($productId) {
            $product = Product::findOrFail($productId);
            $this->productId = $product->id;
            $this->modelo = $product->modelo;
            $this->title = $product->title;
            $this->slug = $product->slug;
            $this->descripcion = $product->descripcion;
            $this->color = $product->color;
            $this->brazalete = $product->brazalete;
            $this->coleccion = $product->coleccion;
            $this->tipo_movimiento = $product->tipo_movimiento;
            $this->size = $product->size;
            $this->genero = $product->genero;
            $this->caja = $product->caja;
            $this->resistencia_agua = $product->resistencia_agua;
            $this->precio_venta = $product->precio_venta;
            $this->precio_original = $product->precio_original;
            $this->precio_costo = $product->precio_costo;
            $this->descuento = $product->descuento;
            $this->stock = $product->stock;
            $this->imagen = $product->imagen;
            $this->video_uid = $product->video_uid;
            $this->originalVideoUid = $product->video_uid;
            $this->video_thumb_time = $product->video_thumb_time;
            $this->video_extra_scenes = array_values(array_map('intval', (array) ($product->video_extra_scenes ?? [])));
            $this->activo = $product->activo;
            $this->bloqueado = (bool) $product->bloqueado;
            $this->proximo = (bool) $product->proximo;
            $this->manual_override = (bool) $product->manual_override;
            $this->disponibilidad = $product->disponibilidad ?? 'disponible';
        }

        if ($this->video_uid && empty($this->video_extra_scenes)) {
            $this->video_extra_scenes = $this->suggestScenes();
            $this->scenesSuggested = !empty($this->video_extra_scenes);
        }

        $this->refreshPricing();
    }

    /**
     * Sugiere hasta 3 escenas repartidas en el video (solo pre-llena el
     * formulario; no guarda nada). Usa la duración de Stream y si falla
     * usa valores fijos.
     */
    private function suggestScenes(): array
    {
        $primary = is_numeric($this->video_thumb_time) ? (int) $this->video_thumb_time : null;
        $duration = $this->fetchVideoDuration();
        if ($duration > 0) {
            $scenes = [
                (int) round($duration * 0.2),
                (int) round($duration * 0.45),
                (int) round($duration * 0.7),
            ];
        } else {
            $scenes = [2, 5, 10];
        }
        $out = [];
        foreach ($scenes as $s) {
            $s = max(0, $s);
            if ($s !== $primary && !in_array($s, $out, true)) {
                $out[] = $s;
            }
            if (count($out) >= 3) {
                break;
            }
        }
        return $out;
    }

    private function fetchVideoDuration(): float
    {
        try {
            $accountId = config('services.cloudflare.account_id');
            $apiToken = config('services.cloudflare.api_token');
            if (!$accountId || !$apiToken || !$this->video_uid) {
                return 0;
            }
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
                ->timeout(5)
                ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                ->get("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/{$this->video_uid}");
            if (!$response->successful()) {
                return 0;
            }
            return (float) ($response->json('result.duration', 0));
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function updatedModelo($value)
    {
        if (!$this->slug) {
            $this->slug = "invicta-" . Str::slug($value);
        }
        $this->title = Product::buildDisplayTitle($this->coleccion, $this->genero, $this->modelo, $this->size, $this->tipo_movimiento);
    }

    public function updatedColeccion($value)
    {
        $this->title = Product::buildDisplayTitle($this->coleccion, $this->genero, $this->modelo, $this->size, $this->tipo_movimiento);
    }

    public function updatedGenero($value)
    {
        $this->title = Product::buildDisplayTitle($this->coleccion, $this->genero, $this->modelo, $this->size, $this->tipo_movimiento);
    }

    public function updatedSize($value)
    {
        $this->title = Product::buildDisplayTitle($this->coleccion, $this->genero, $this->modelo, $this->size, $this->tipo_movimiento);
    }

    public function updatedTipoMovimiento($value)
    {
        $this->title = Product::buildDisplayTitle($this->coleccion, $this->genero, $this->modelo, $this->size, $value);
    }

    public function updatedPrecioOriginal($value)
    {
        $this->refreshPricing();
        if (! $this->manual_override) {
            $this->aplicarSugerencia();
        }
    }

    public function recalcular()
    {
        $this->refreshPricing();
        $this->aplicarSugerencia();
    }

    public function refreshPricing(): void
    {
        $this->precioMinimo = null;
        $this->precioSugerido = null;
        $this->costoSugerido = null;
        $this->margenEsperado = null;
        $this->descuentoMaximo = null;

        if (! is_numeric($this->precio_original) || (float) $this->precio_original <= 0) {
            return;
        }

        $pricing = app(PricingService::class)->calculate((float) $this->precio_original);

        $this->costoSugerido = $pricing['precio_costo'];
        $this->precioMinimo = $pricing['precio_minimo'];
        $this->precioSugerido = $pricing['precio_final'];
        $this->margenEsperado = $pricing['margen_bruto_pct'];
        $this->descuentoMaximo = $pricing['descuento_maximo'];
    }

    private function aplicarSugerencia(): void
    {
        if ($this->costoSugerido !== null) {
            $this->precio_costo = $this->costoSugerido;
        }
        if ($this->precioSugerido !== null) {
            $this->precio_venta = $this->precioSugerido;
        }
    }

    public function downloadImage()
    {
        $this->downloadStatus = "";
        $this->downloadMessage = "";

        if (!$this->imagen || !$this->modelo) {
            $this->setDownloadError("Se requiere modelo y URL de imagen.");
            return;
        }

        if (!preg_match("#^https?://#i", $this->imagen)) {
            $this->setDownloadError(
                "La URL de imagen no es válida (debe empezar con http).",
            );
            return;
        }

        try {
            $response = Http::withHeaders([
                "User-Agent" => self::CDN_USER_AGENT,
            ])
                ->withOptions(
                    app()->environment("local") ? ["verify" => false] : [],
                )
                ->timeout(30)
                ->get($this->imagen);

            if (!$response->ok() || $response->body() === "") {
                $this->setDownloadError(
                    "No se pudo descargar la imagen (HTTP " .
                        $response->status() .
                        ").",
                );
                return;
            }

            $extension = self::detectExtension($this->imagen, $response);
            $safeModelo = preg_replace("/[^a-zA-Z0-9_-]/", "", $this->modelo);
            if ($safeModelo === "") {
                $safeModelo = "producto";
            }
            $filename = strtolower($safeModelo) . "." . $extension;
            $relative = "relojes/" . $filename;

            Storage::disk('r2')->put($relative, $response->body(), 'public');

            if (!Storage::disk('r2')->exists($relative)) {
                $this->setDownloadError(
                    "La imagen se descargó pero no se guardó en R2.",
                );
                return;
            }

            $this->imagen = "/storage/relojes/" . $filename;
            $this->downloadStatus = "ok";
            $this->downloadMessage =
                "Imagen descargada: " .
                $filename .
                " (verifica la vista previa abajo).";
        } catch (\Exception $e) {
            \Log::warning('Error al descargar imagen de producto', ['url' => $this->imagen, 'error' => $e->getMessage()]);
            $this->setDownloadError("No se pudo descargar la imagen. Reintentá en unos minutos.");
            return;
        }

        // Generar derivados WebP desde los bytes recién descargados (no releer R2:
        // es eventualmente consistente en sobrescrituras).
        try {
            app(ImageOptimizerService::class)->optimizeProductFromContents($this->toProduct(), $response->body());
        } catch (\Exception $e) {
            // Ignorado: la imagen ya se guardó, solo falló la optimización WebP
        }
    }

    private function setDownloadError(string $message): void
    {
        $this->downloadStatus = "error";
        $this->downloadMessage = $message;
    }

    /**
     * Garantiza el symlink public/storage -> storage/app/public.
     * En entornos donde php artisan storage:link no se ejecutó, la imagen
     * se guarda pero queda inaccesible desde la web.
     */
    private function ensureStorageSymlink(): void
    {
        $link = public_path("storage");
        $target = storage_path("app/public");

        if (is_link($link) || is_dir($link . "/relojes")) {
            return;
        }

        try {
            if (is_link($link) || is_file($link)) {
                @unlink($link);
            }
            if (!is_dir($link)) {
                @symlink($target, $link);
            }
        } catch (\Throwable $e) {
            // Ignorado: si no se puede crear, igual guardamos el archivo.
        }
    }

    private const CDN_USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";

    private static function detectExtension(string $url, $response): string
    {
        $contentType = $response->header("Content-Type") ?? "";
        if (str_contains($contentType, "webp")) {
            return "webp";
        }
        if (str_contains($contentType, "png")) {
            return "png";
        }
        if (str_contains($contentType, "gif")) {
            return "gif";
        }

        $path = parse_url($url, PHP_URL_PATH) ?? "";
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (
            $ext &&
            in_array(strtolower($ext), ["jpg", "jpeg", "png", "webp", "gif"])
        ) {
            return strtolower($ext);
        }
        return "jpg";
    }

    public function optimizeImage()
    {
        $this->optimizeStatus = null;
        $this->optimizeMessage = null;

        if (!$this->imagen || !$this->modelo) {
            $this->optimizeStatus = 'error';
            $this->optimizeMessage = 'Se requiere modelo e imagen local.';
            return;
        }

        if (!str_starts_with($this->imagen, '/storage/')) {
            $this->optimizeStatus = 'error';
            $this->optimizeMessage = 'La imagen debe ser local (/storage/relojes/...).';
            return;
        }

        try {
            $service = app(ImageOptimizerService::class);
            $result = $service->optimizeProduct($this->toProduct());

            if ($result['success']) {
                $parts = [];
                if ($result['thumb']) $parts[] = 'thumb ' . number_format($result['thumb_size'] / 1024, 1) . 'KB';
                if ($result['medium']) $parts[] = 'medium ' . number_format($result['medium_size'] / 1024, 1) . 'KB';
                if ($result['large']) $parts[] = 'large ' . number_format($result['large_size'] / 1024, 1) . 'KB';
                $this->optimizeStatus = 'ok';
                $this->optimizeMessage = 'WebP generados: ' . implode(', ', $parts);
            } else {
                $this->optimizeStatus = 'error';
                $this->optimizeMessage = $result['error'] ?? 'Error desconocido';
            }
        } catch (\Exception $e) {
            \Log::warning('Error al optimizar imagen de producto', ['id' => $this->productId, 'error' => $e->getMessage()]);
            $this->optimizeStatus = 'error';
            $this->optimizeMessage = 'No se pudo optimizar la imagen. Reintentá en unos minutos.';
        }
    }

    private function toProduct(): Product
    {
        $p = new Product();
        $p->id = $this->productId;
        $p->modelo = $this->modelo;
        $p->imagen = $this->imagen;
        return $p;
    }

    public function fetchFromInvicta()
    {
        $this->fetchStatus = "";
        $this->fetchMessage = "";

        if (!$this->modelo) {
            $this->fetchStatus = "error";
            $this->fetchMessage = "Ingrese un modelo primero.";
            return;
        }

        try {
            $scraper = app(InvictaWatchScraper::class);
            $data = $scraper->scrape($this->modelo);

            if (!$data) {
                $this->fetchStatus = "error";
                $this->fetchMessage = "No se pudo obtener información de InvictaWatch para el modelo \"{$this->modelo}\".";
                return;
            }

            $this->title = $data["title"] ?? $this->title;
            $this->descripcion = $data["descripcion"] ?? $this->descripcion;

            if (empty($this->slug)) {
                $this->slug = "invicta-" . Str::slug($this->modelo);
            }

            if ($data["coleccion"]) {
                $this->coleccion = $data["coleccion"];
            }
            if ($data["genero"]) {
                $this->genero = $data["genero"];
            }
            if ($data["msrp"]) {
                $this->precio_original = $data["msrp"];
            }
            if ($data["size"]) {
                $this->size = $data["size"];
            }
            if ($data["caja"]) {
                $this->caja = $data["caja"];
            }
            if ($data["brazalete"]) {
                $this->brazalete = $data["brazalete"];
            }
            if ($data["tipo_movimiento"]) {
                $this->tipo_movimiento = $data["tipo_movimiento"];
            }
            if ($data["resistencia_agua"]) {
                $this->resistencia_agua = $data["resistencia_agua"];
            }

            if ($data["imagen_local"]) {
                $this->imagen = $data["imagen_local"];
                // Derivados WebP desde los bytes recién descargados por el scraper;
                // nunca releer R2 (consistencia eventual en sobrescrituras).
                try {
                    $service = app(ImageOptimizerService::class);
                    if (!empty($data["imagen_contents"])) {
                        $service->optimizeProductFromContents($this->toProduct(), $data["imagen_contents"]);
                    } else {
                        $service->optimizeProduct($this->toProduct());
                    }
                } catch (\Exception $e) {
                    \Log::warning('Error al optimizar imagen desde Invicta', ['modelo' => $this->modelo, 'error' => $e->getMessage()]);
                }
            }

            $this->fetchStatus = "ok";
            $parts = [];
            if ($data["coleccion"]) $parts[] = "colección";
            if ($data["genero"]) $parts[] = "género";
            if ($data["msrp"]) $parts[] = "precio";
            if ($data["size"]) $parts[] = "medidas";
            if ($data["caja"]) $parts[] = "caja";
            if ($data["brazalete"]) $parts[] = "brazalete";
            if ($data["tipo_movimiento"]) $parts[] = "movimiento";
            if ($data["resistencia_agua"]) $parts[] = "resistencia";
            if ($data["imagen_local"]) $parts[] = "imagen+webp";
            $this->fetchMessage = "Datos cargados: " . implode(", ", $parts) . ".";
        } catch (\Exception $e) {
            \Log::warning('Error al obtener datos de Invicta', ['modelo' => $this->modelo, 'error' => $e->getMessage()]);
            $this->fetchStatus = "error";
            $this->fetchMessage = "No se pudieron obtener datos de InvictaWatch. Reintentá en unos minutos.";
        }
    }

    /**
     * Quita el video solo del formulario. El borrado real en Cloudflare y en
     * la base de datos se hace en save(), para que "Cancelar" no destruya nada.
     */
    public function deleteVideo()
    {
        if (!$this->video_uid) {
            return;
        }

        $this->video_uid = null;
        $this->video_thumb_time = null;
        $this->video_extra_scenes = [];
        $this->scenesSuggested = false;
        $this->videoDeleteStatus = 'staged';
        $this->videoDeleteMessage = 'El video se eliminará al guardar el producto.';
    }

    /**
     * Borra el video de Cloudflare Stream (best-effort). Devuelve una nota
     * para el mensaje de éxito del guardado.
     */
    private function deleteVideoFromStream(string $uid): string
    {
        try {
            $accountId = config('services.cloudflare.account_id');
            $apiToken = config('services.cloudflare.api_token');

            if (!$accountId || !$apiToken) {
                return "";
            }

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
                ->timeout(60)
                ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                ->delete("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/{$uid}");

            if (!$response->successful()) {
                $error = $response->json('errors.0.message') ?: ('HTTP ' . $response->status());
                \Log::warning('No se pudo borrar video de Stream', ['uid' => $uid, 'error' => $error]);
                return " El video se quitó del producto, pero falló el borrado en Stream.";
            }

            return " Video eliminado de Cloudflare Stream.";
        } catch (\Throwable $e) {
            \Log::warning('No se pudo borrar video de Stream', ['uid' => $uid, 'error' => $e->getMessage()]);
            return " El video se quitó del producto, pero falló el borrado en Stream.";
        }
    }

    public function addExtraScene()
    {
        if (count($this->video_extra_scenes) >= 10) {
            return;
        }
        $this->video_extra_scenes[] = '';
    }

    public function removeExtraScene($index)
    {
        unset($this->video_extra_scenes[(int) $index]);
        $this->video_extra_scenes = array_values($this->video_extra_scenes);
    }

    public static function normalizeScenes($scenes): ?array
    {
        $out = [];
        foreach ((array) $scenes as $s) {
            if ($s === '' || $s === null) {
                continue;
            }
            $out[] = max(0, (int) $s);
        }
        $out = array_values(array_unique($out));
        return !empty($out) ? $out : null;
    }

    public function save()
    {
        $ignoreId = $this->productId ?: null;
        $this->validate([
            "modelo" => [
                "required", "string", "max:255",
                Rule::unique("products", "modelo")->ignore($ignoreId),
            ],
            "slug" => [
                "required", "string", "max:255",
                Rule::unique("products", "slug")->ignore($ignoreId),
            ],
            "precio_venta" => "required|numeric|min:0",
            "precio_original" => "nullable|numeric|min:0",
            "precio_costo" => "nullable|numeric|min:0",
            "descuento" => "nullable|numeric|min:0|max:100",
            "stock" => "nullable|integer|min:0",
            "disponibilidad" => "nullable|in:disponible,agotado",
            "video_uid" => "nullable|string|max:64",
            "video_thumb_time" => "nullable|integer|min:0|max:3600",
            "video_extra_scenes" => "nullable|array|max:10",
            "video_extra_scenes.*" => "nullable|integer|min:0|max:3600",
        ]);

        $cleanedSize = $this->sanitizeNumeric($this->size);
        $cleanedResistencia = $this->sanitizeNumeric($this->resistencia_agua);

        $data = [
            "modelo" => $this->modelo,
            "title" => Product::buildDisplayTitle($this->coleccion, $this->genero, $this->modelo, $cleanedSize, $this->tipo_movimiento),
            "slug" => $this->slug,
            "descripcion" => $this->descripcion,
            "color" => $this->color,
            "brazalete" => $this->brazalete,
            "coleccion" => Product::normalizeColeccion($this->coleccion),
            "tipo_movimiento" => $this->tipo_movimiento,
            "size" => $cleanedSize,
            "genero" => $this->genero,
            "caja" => $this->caja,
            "resistencia_agua" => $cleanedResistencia,
            "precio_venta" => $this->precio_venta,
            "precio_original" => $this->precio_original ?: null,
            "precio_costo" => $this->precio_costo ?: null,
            "descuento" => $this->descuento ?: 0,
            "stock" => $this->stock ?: 0,
            "imagen" => $this->imagen,
            "video_uid" => $this->video_uid,
            "video_thumb_time" => $this->video_thumb_time !== '' && $this->video_thumb_time !== null ? (int) $this->video_thumb_time : null,
            "video_extra_scenes" => self::normalizeScenes($this->video_extra_scenes),
            "activo" => $this->activo,
            "bloqueado" => $this->bloqueado,
            "proximo" => $this->proximo,
            "manual_override" => $this->manual_override,
            "disponibilidad" => $this->disponibilidad,
        ];

        $isUpdate = (bool) $this->productId;

        $product = DB::transaction(function () use ($data, $isUpdate) {
            if ($isUpdate) {
                $product = Product::findOrFail($this->productId);

                $priceChanged =
                    (float) $product->precio_venta !== (float) $this->precio_venta
                    || (float) $product->precio_original !== (float) ($this->precio_original ?: 0)
                    || (float) $product->precio_costo !== (float) ($this->precio_costo ?: 0);

                if ($priceChanged) {
                    $this->manual_override = true;
                    $data["manual_override"] = true;
                }

                $product->update($data);
                return $product;
            }

            return Product::create($data);
        });

        $this->scenesSuggested = false;
        Product::forgetAllCache($product->id, $product->slug);

        // Borrado diferido del video: recién ahora que el guardado fue exitoso.
        // Cubre quitarlo o reemplazarlo por otro uid.
        $videoNote = "";
        if ($this->originalVideoUid && $this->originalVideoUid !== $this->video_uid) {
            $videoNote = $this->deleteVideoFromStream($this->originalVideoUid);
            $this->originalVideoUid = null;
        }

        try {
            $this->purgeProductCaches($product);
        } catch (\Exception $e) {
            \Log::warning('No se pudo purgar Cloudflare', ['id' => $product->id, 'error' => $e->getMessage()]);
        }

        $waitlistMsg = $this->notifyWaitlist($product);

        if ($isUpdate) {
            session()->flash("message", "Producto <strong>" . e($product->modelo) . "</strong> actualizado.{$videoNote}{$waitlistMsg} <a href=\"" . route('products.show', $product->slug) . "\" class=\"underline text-green-800 dark:text-green-300\">Ver reloj</a>");
        } else {
            session()->flash("message", "Producto creado.{$waitlistMsg}");
        }

        $this->redirect(route("products.show", $product->slug));
    }

    /**
     * Purga de Cloudflare todo lo relacionado al producto: su página, el
     * listado, la imagen OG y sus imágenes (principal + derivados WebP).
     */
    private function purgeProductCaches(Product $product): void
    {
        $baseUrl = config('app.url', 'https://invictacostarica.com');
        $cdnBase = 'https://cdn.invictacostarica.com';

        $urls = [
            "{$baseUrl}/relojes/{$product->slug}",
            "{$baseUrl}/relojes",
            "{$baseUrl}/og/product/{$product->slug}.png",
        ];

        $mainImage = $product->imagen;
        if ($mainImage && str_starts_with($mainImage, $cdnBase)) {
            $urls[] = $mainImage;
        }

        $rawImagen = $product->getRawOriginal('imagen');
        if ($rawImagen) {
            $modeloFile = pathinfo($rawImagen, PATHINFO_FILENAME);
            foreach (['thumbs', 'medium', 'large'] as $dir) {
                $urls[] = "{$cdnBase}/relojes/{$dir}/{$modeloFile}.webp";
            }
        }

        app(CloudflareCacheService::class)->purgeUrls(array_values(array_unique($urls)));
    }

    private function notifyWaitlist(Product $product): string
    {
        try {
            $notified = app(\App\Services\WaitlistService::class)->checkAndNotify($product);
            if ($notified > 0) {
                return " <strong>{$notified} contacto(s) en lista de espera notificados.</strong> <a href=\"" . route('admin.waitlist') . "\" class=\"underline text-green-800 dark:text-green-300\">Ver lista</a>";
            }
        } catch (\Throwable $e) {
        }
        return "";
    }

    private function sanitizeNumeric(mixed $value): ?string
    {
        if (is_null($value) || trim((string) $value) === '') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9.,]/', '', trim((string) $value));
        $cleaned = str_replace(',', '.', $cleaned);
        $cleaned = preg_replace('/\.(?=.*\.)/', '', $cleaned);
        if (!is_numeric($cleaned)) {
            return null;
        }
        $num = (float) $cleaned;
        return $num == intval($num) ? (string) intval($num) : rtrim(rtrim(sprintf('%.1f', $num), '0'), '.');
    }

    public function render()
    {
        $colecciones = collect(config("collections", []))
            ->map(fn($c) => trim($c))
            ->filter()
            ->unique()
            ->sort(fn($a, $b) => strcasecmp($a, $b))
            ->values();
        $colores = collect(config("colors", []))
            ->map(fn($c) => trim($c))
            ->filter()
            ->unique()
            ->sort(fn($a, $b) => strcasecmp($a, $b))
            ->values();

        $brazaletes = collect(config("brazaletes", []))
            ->map(fn($b) => trim($b))
            ->filter()
            ->unique()
            ->sort(fn($a, $b) => strcasecmp($a, $b))
            ->values();

        $cajas = collect(['Acero Inoxidable', 'Silicona', 'Titanio', 'Plastico']);

        return view(
            "livewire.admin.product-form",
            compact("colecciones", "colores", "brazaletes", "cajas"),
        )->layout("components.admin-layout", ["title" => $this->productId ? "Editar Producto" : "Nuevo Producto"]);
    }
}
