<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
        if (!$this->imagen || !$this->modelo) {
            session()->flash("error", "Se requiere modelo y URL de imagen.");
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
                session()->flash(
                    "error",
                    "No se pudo descargar la imagen desde el CDN.",
                );
                return;
            }

            $extension = self::detectExtension($this->imagen, $response);
            $filename = $this->modelo . "." . $extension;
            $directory = storage_path("app/public/relojes");
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            File::put($directory . "/" . $filename, $response->body());

            $this->imagen = "/storage/relojes/" . $filename;
            session()->flash("message", "Imagen descargada: " . $filename);
        } catch (\Exception $e) {
            session()->flash(
                "error",
                "Error al descargar: " . $e->getMessage(),
            );
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
        return view("livewire.admin.product-form")->layout(
            "components.admin-layout",
        );
    }
}
