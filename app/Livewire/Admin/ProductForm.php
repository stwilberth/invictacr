<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\ImageOptimizerService;
use App\Services\InvictaWatchScraper;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductForm extends Component
{
    use WithFileUploads;

    public $productId;
    public $modelo, $title, $slug, $descripcion, $color, $brazalete;
    public $coleccion, $tipo_movimiento, $size, $genero, $caja;
    public $resistencia_agua, $precio_venta, $precio_original;
    public $video;
    public $descuento = 0,
        $stock = 0,
        $imagen,
        $activo = true;
    public $bloqueado = false,
        $proximo = false,
        $variedades_increase;
    public $imagenes_extra = [];
    public $newExtraImageUrl = "";
    public $extraDownloadStatus = "";
    public $extraDownloadMessage = "";
    public $downloadStatus = "";
    public $downloadMessage = "";
    public $fetchStatus = "";
    public $fetchMessage = "";
    public ?string $optimizeStatus = null;
    public ?string $optimizeMessage = null;
    public $newImageFile;
    public $newExtraImageFile;

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
            $this->descuento = $product->descuento;
            $this->stock = $product->stock;
            $this->imagen = $product->imagen;
            $this->video = $product->video;
            $this->imagenes_extra = $product->images()->pluck('url')->toArray();
            $this->activo = $product->activo;
            $this->bloqueado = (bool) $product->bloqueado;
            $this->proximo = (bool) $product->proximo;
            $this->variedades_increase = (int) $product->precio_original > 0 ? (int) $product->precio_venta - (int) $product->precio_original : 0;
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

    public function addImagenExtra()
    {
        $next = count($this->imagenes_extra) + 1;
        $this->imagenes_extra[] = "/storage/relojes/{$this->modelo}_{$next}.jpg";
    }

    public function removeImagenExtra(int $index)
    {
        if (isset($this->imagenes_extra[$index])) {
            unset($this->imagenes_extra[$index]);
            $this->imagenes_extra = array_values($this->imagenes_extra);
        }
    }

    public function uploadImage()
    {
        $this->downloadStatus = "";
        $this->downloadMessage = "";

        if (!$this->newImageFile) {
            $this->setDownloadError("Seleccione una imagen para subir.");
            return;
        }

        if (!$this->modelo) {
            $this->setDownloadError("Ingrese un modelo primero.");
            return;
        }

        try {
            $this->validate([
                'newImageFile' => 'required|image|max:10240',
            ], [
                'newImageFile.image' => 'El archivo debe ser una imagen (jpg, png, webp).',
                'newImageFile.max' => 'La imagen no debe superar 10MB.',
            ]);

            $file = $this->newImageFile;
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }

            $safeModelo = preg_replace("/[^a-zA-Z0-9_-]/", "", $this->modelo);
            if ($safeModelo === "") {
                $safeModelo = "producto";
            }
            $filename = strtolower($safeModelo) . "." . $extension;
            $relative = "relojes/" . $filename;

            Storage::disk('r2')->put($relative, file_get_contents($file->getRealPath()), 'public');

            if (!Storage::disk('r2')->exists($relative)) {
                $this->setDownloadError("La imagen no se guardó en R2.");
                return;
            }

            $this->imagen = "/storage/relojes/" . $filename;
            $this->newImageFile = null;
            $this->downloadStatus = "ok";
            $this->downloadMessage = "Imagen subida: " . $filename;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setDownloadError($e->getMessage());
            return;
        } catch (\Exception $e) {
            $this->setDownloadError("Error al subir: " . $e->getMessage());
            return;
        }

        try {
            $this->generateOptimizedVersions($filename);
        } catch (\Exception $e) {
            // Ignorado: la imagen ya se guardó, solo falló la optimización WebP
        }
    }

    public function uploadAndAddExtraImage()
    {
        $this->extraDownloadStatus = "";
        $this->extraDownloadMessage = "";

        if (!$this->newExtraImageFile) {
            $this->extraDownloadStatus = "error";
            $this->extraDownloadMessage = "Seleccione una imagen para subir.";
            return;
        }

        if (!$this->modelo) {
            $this->extraDownloadStatus = "error";
            $this->extraDownloadMessage = "Ingrese un modelo primero.";
            return;
        }

        try {
            $this->validate([
                'newExtraImageFile' => 'required|image|max:10240',
            ], [
                'newExtraImageFile.image' => 'El archivo debe ser una imagen (jpg, png, webp).',
                'newExtraImageFile.max' => 'La imagen no debe superar 10MB.',
            ]);

            $file = $this->newExtraImageFile;
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }

            $next = count($this->imagenes_extra) + 1;
            $safeModelo = preg_replace("/[^a-zA-Z0-9_-]/", "", $this->modelo);
            $filename = strtolower($safeModelo) . "_{$next}." . $extension;
            $relative = "relojes/" . $filename;

            Storage::disk('r2')->put($relative, file_get_contents($file->getRealPath()), 'public');

            if (!Storage::disk('r2')->exists($relative)) {
                $this->extraDownloadStatus = "error";
                $this->extraDownloadMessage = "La imagen no se guardó en R2.";
                return;
            }

            $localPath = "/storage/relojes/" . $filename;
            $this->imagenes_extra[] = $localPath;
            $this->newExtraImageFile = null;
            $this->extraDownloadStatus = "ok";
            $this->extraDownloadMessage = "Imagen subida: {$filename}";
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->extraDownloadStatus = "error";
            $this->extraDownloadMessage = $e->getMessage();
            return;
        } catch (\Exception $e) {
            $this->extraDownloadStatus = "error";
            $this->extraDownloadMessage = "Error: " . $e->getMessage();
            return;
        }

        try {
            $this->generateOptimizedVersions($filename);
        } catch (\Exception $e) {
            // Ignorado: la imagen ya se guardó, solo falló la optimización WebP
        }
    }

    public function downloadAndAddExtraImage()
    {
        $this->extraDownloadStatus = "";
        $this->extraDownloadMessage = "";

        if (!$this->newExtraImageUrl || !$this->modelo) {
            $this->extraDownloadStatus = "error";
            $this->extraDownloadMessage = "Se requiere modelo y URL de imagen.";
            return;
        }

        if (!preg_match("#^https?://#i", $this->newExtraImageUrl)) {
            $this->extraDownloadStatus = "error";
            $this->extraDownloadMessage = "La URL debe empezar con http.";
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
                ->get($this->newExtraImageUrl);

            if (!$response->ok() || $response->body() === "") {
                $this->extraDownloadStatus = "error";
                $this->extraDownloadMessage = "No se pudo descargar (HTTP " . $response->status() . ").";
                return;
            }

            $extension = self::detectExtension($this->newExtraImageUrl, $response);
            $next = count($this->imagenes_extra) + 1;
            $safeModelo = preg_replace("/[^a-zA-Z0-9_-]/", "", $this->modelo);
            $filename = strtolower($safeModelo) . "_{$next}." . $extension;
            $relative = "relojes/" . $filename;

            Storage::disk('r2')->put($relative, $response->body(), 'public');

            if (!Storage::disk('r2')->exists($relative)) {
                $this->extraDownloadStatus = "error";
                $this->extraDownloadMessage = "La imagen no se guardó en R2.";
                return;
            }

            $localPath = "/storage/relojes/" . $filename;
            $this->imagenes_extra[] = $localPath;
            $this->newExtraImageUrl = "";
            $this->extraDownloadStatus = "ok";
            $this->extraDownloadMessage = "Imagen agregada: {$filename}";
        } catch (\Exception $e) {
            $this->extraDownloadStatus = "error";
            $this->extraDownloadMessage = "Error: " . $e->getMessage();
            return;
        }

        try {
            $this->generateOptimizedVersions($filename);
        } catch (\Exception $e) {
            // Ignorado: la imagen ya se guardó, solo falló la optimización WebP
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
            $this->setDownloadError("Error al descargar: " . $e->getMessage());
            return;
        }

        try {
            $this->generateOptimizedVersions($filename);
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

    private function generateOptimizedVersions(string $filename): void
    {
        $r2Path = "relojes/{$filename}";
        $r2 = Storage::disk('r2');
        
        if (!$r2->exists($r2Path)) {
            return;
        }

        $tempPath = storage_path("app/temp/{$filename}");
        $tempDir = dirname($tempPath);
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }

        file_put_contents($tempPath, $r2->get($r2Path));

        $info = @getimagesize($tempPath);
        if (!$info) {
            @unlink($tempPath);
            return;
        }

        $source = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($tempPath),
            IMAGETYPE_PNG => @imagecreatefrompng($tempPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($tempPath),
            default => null,
        };

        if (!$source) {
            @unlink($tempPath);
            return;
        }

        $modelo = pathinfo($filename, PATHINFO_FILENAME);
        [$origW, $origH] = $info;

        $sizes = [
            'thumbs' => ['width' => 200, 'quality' => 80],
            'medium' => ['width' => 600, 'quality' => 80],
            'large' => ['width' => 1200, 'quality' => 85],
        ];

        foreach ($sizes as $dir => $cfg) {
            $maxW = $cfg['width'];

            if ($origW <= $maxW) {
                $newW = $origW;
                $newH = $origH;
            } else {
                $ratio = $maxW / $origW;
                $newW = $maxW;
                $newH = (int) round($origH * $ratio);
            }

            $resampled = imagecreatetruecolor($newW, $newH);
            if (!$resampled) {
                continue;
            }

            imagealphablending($resampled, false);
            imagesavealpha($resampled, true);
            imagecopyresampled($resampled, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

            $tempTarget = storage_path("app/temp/{$modelo}_{$dir}.webp");
            imagewebp($resampled, $tempTarget, $cfg['quality']);
            imagedestroy($resampled);

            $r2->put("relojes/{$dir}/{$modelo}.webp", file_get_contents($tempTarget), 'public');
            @unlink($tempTarget);
        }

        imagedestroy($source);
        @unlink($tempPath);
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
            $this->optimizeStatus = 'error';
            $this->optimizeMessage = 'Error: ' . $e->getMessage();
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
                $filename = basename($data["imagen_local"]);
                $this->generateOptimizedVersions($filename);
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
            $this->fetchStatus = "error";
            $this->fetchMessage = "Error al obtener datos: " . $e->getMessage();
        }
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
            "coleccion" => $this->coleccion,
            "tipo_movimiento" => $this->tipo_movimiento,
            "size" => $cleanedSize,
            "genero" => $this->genero,
            "caja" => $this->caja,
            "resistencia_agua" => $cleanedResistencia,
            "precio_venta" => $this->precio_venta,
            "precio_original" => $this->precio_original ?: null,
            "descuento" => $this->descuento ?: 0,
            "stock" => $this->stock ?: 0,
            "imagen" => $this->imagen,
            "video" => $this->video,
            "activo" => $this->activo,
            "bloqueado" => $this->bloqueado,
            "proximo" => $this->proximo,
        ];

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            session()->flash("message", "Producto <strong>" . e($product->modelo) . "</strong> actualizado. <a href=\"" . route('products.show', $product->slug) . "\" class=\"underline text-green-800 dark:text-green-300\">Ver reloj</a>");
        } else {
            $product = Product::create($data);
            session()->flash("message", "Producto creado.");
        }

        $product->images()->delete();
        foreach ($this->imagenes_extra as $order => $url) {
            $product->images()->create([
                'url' => $url,
                'order' => $order,
                'type' => 'image',
            ]);
        }

        $this->redirect(route("admin.products"));
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
        )->layout("components.admin-layout");
    }
}
