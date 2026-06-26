<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    public $productId;
    public $modelo, $title, $slug, $descripcion, $color, $brazalete;
    public $coleccion, $tipo_movimiento, $size, $genero, $caja;
    public $resistencia_agua, $precio_venta, $precio_original;
    public $descuento = 0,
        $stock = 0,
        $imagen,
        $activo = true;
    public $bloqueado = false,
        $variedades_price,
        $variedades_increase;
    public $downloadStatus = "";
    public $downloadMessage = "";

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
            $this->activo = $product->activo;
            $this->bloqueado = (bool) $product->bloqueado;
            $this->variedades_price = $product->variedades_price;
            $this->variedades_increase = $product->variedades_increase;
        }
    }

    public function updatedModelo($value)
    {
        if (!$this->slug) {
            $this->slug = "invicta-" . Str::slug($value);
        }
        if (!$this->title) {
            $this->title = $value;
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

            $this->ensureStorageSymlink();

            Storage::disk("public")->makeDirectory("relojes");
            Storage::disk("public")->put($relative, $response->body());

            if (!Storage::disk("public")->exists($relative)) {
                $this->setDownloadError(
                    "La imagen se descargó pero no se guardó en disco.",
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

    public function save()
    {
        $this->validate([
            "modelo" => "required|string|max:255",
            "slug" => "required|string|max:255",
            "precio_venta" => "required|numeric|min:0",
        ]);

        $data = [
            "modelo" => $this->modelo,
            "title" => $this->title,
            "slug" => $this->slug,
            "descripcion" => $this->descripcion,
            "color" => $this->color,
            "brazalete" => $this->brazalete,
            "coleccion" => $this->coleccion,
            "tipo_movimiento" => $this->tipo_movimiento,
            "size" => $this->size,
            "genero" => $this->genero,
            "caja" => $this->caja,
            "resistencia_agua" => $this->resistencia_agua,
            "precio_venta" => $this->precio_venta,
            "precio_original" => $this->precio_original ?: null,
            "descuento" => $this->descuento ?: 0,
            "stock" => $this->stock ?: 0,
            "imagen" => $this->imagen,
            "activo" => $this->activo,
            "bloqueado" => $this->bloqueado,
            "variedades_price" => $this->variedades_price ?: null,
            "variedades_increase" => $this->variedades_increase ?: null,
        ];

        if ($this->productId) {
            Product::findOrFail($this->productId)->update($data);
            session()->flash("message", "Producto actualizado.");
        } else {
            Product::create($data);
            session()->flash("message", "Producto creado.");
        }

        $this->redirect(route("admin.products"));
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

        return view(
            "livewire.admin.product-form",
            compact("colecciones", "colores"),
        )->layout("components.admin-layout");
    }
}
