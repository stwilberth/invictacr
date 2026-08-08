<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\ImageOptimizerService;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search = "";
    public $sortField = "created_at";
    public $sortDirection = "desc";
    public $filterGender = "";
    public $filterColeccion = "";
    public $filterColor = "";
    public $filterCaja = "";
    public $filterBrazalete = "";
    public $filterResistencia = "";
    public $filterTamano = "";
    public $filterStock = "in";
    public $filterActivo = "all";
    public $filterBloqueado = "all";
    public $filterProximo = "all";

    public ?int $optimizingProductId = null;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection =
                $this->sortDirection === "asc" ? "desc" : "asc";
        } else {
            $this->sortField = $field;
            $this->sortDirection = "asc";
        }
    }

    public function toggleActive($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(["activo" => !$product->activo]);
    }

    public function deleteProduct($productId)
    {
        Product::findOrFail($productId)->delete();
    }

    public function optimizeImage($productId)
    {
        $this->optimizingProductId = $productId;

        try {
            $product = Product::findOrFail($productId);
            $service = app(ImageOptimizerService::class);
            $result = $service->optimizeProduct($product);

            if ($result['success']) {
                $sizes = [];
                if ($result['thumb']) $sizes[] = 'thumb ' . number_format($result['thumb_size'] / 1024, 1) . 'KB';
                if ($result['medium']) $sizes[] = 'medium ' . number_format($result['medium_size'] / 1024, 1) . 'KB';
                if ($result['large']) $sizes[] = 'large ' . number_format($result['large_size'] / 1024, 1) . 'KB';
                session()->flash('message', "{$product->modelo}: WebP generados (" . implode(', ', $sizes) . ")");
            } else {
                session()->flash('error', "{$product->modelo}: {$result['error']}");
            }
        } catch (\Exception $e) {
            session()->flash('error', "Error al optimizar: " . $e->getMessage());
        } finally {
            $this->optimizingProductId = null;
        }
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where("modelo", "like", "%{$this->search}%");
        }

        if ($this->filterGender) {
            $query->where("genero", $this->filterGender);
        }

        if ($this->filterColeccion) {
            $query->whereRaw("LOWER(coleccion) = ?", [
                strtolower($this->filterColeccion),
            ]);
        }

        if ($this->filterColor) {
            $query->where("color", $this->filterColor);
        }

        if ($this->filterCaja) {
            $query->where("caja", $this->filterCaja);
        }

        if ($this->filterBrazalete) {
            $query->where("brazalete", $this->filterBrazalete);
        }

        if ($this->filterResistencia) {
            $query->where("resistencia_agua", $this->filterResistencia);
        }

        if ($this->filterTamano) {
            $query->whereRaw("size + 0 = ?", [$this->filterTamano]);
        }

        if ($this->filterStock === "in") {
            $query->where("stock", ">", 0);
        } elseif ($this->filterStock === "out") {
            $query->where("stock", 0);
        }

        if ($this->filterActivo === "yes") {
            $query->where("activo", true);
        } elseif ($this->filterActivo === "no") {
            $query->where("activo", false);
        }

        if ($this->filterBloqueado === "yes") {
            $query->where("bloqueado", true);
        } elseif ($this->filterBloqueado === "no") {
            $query->where("bloqueado", false);
        }

        if ($this->filterProximo === "yes") {
            $query->where(function ($q) {
                $q->where("proximo", true)
                  ->orWhere("precio_venta", "<=", 0);
            });
        } elseif ($this->filterProximo === "no") {
            $query->where("proximo", false)
                ->where("precio_venta", ">", 0);
        }

        $products = $query
            ->withCount('images')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);
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

        $resistencias = Product::whereNotNull('resistencia_agua')
            ->where('resistencia_agua', '!=', '')
            ->distinct()
            ->pluck('resistencia_agua')
            ->sort()
            ->values();

        $tamanios = Product::whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size')
            ->sort(fn($a, $b) => floatval($a) - floatval($b))
            ->values();

        $service = app(ImageOptimizerService::class);
        $optimizationStatus = [];
        foreach ($products as $product) {
            $optimizationStatus[$product->id] = $product->imagen
                ? !$service->needsOptimization($product)
                : null;
        }

        return view(
            "livewire.admin.products",
            compact("products", "colecciones", "colores", "brazaletes", "resistencias", "tamanios", "optimizationStatus"),
        )->layout("components.admin-layout");
    }
}
