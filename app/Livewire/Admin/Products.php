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
    public $filterStock = "all";
    public $filterActivo = "all";

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

        $products = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);
        $colecciones = collect(config("collections", []))
            ->map(fn($c) => trim($c))
            ->filter()
            ->unique()
            ->sort(fn($a, $b) => strcasecmp($a, $b))
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
            compact("products", "colecciones", "optimizationStatus"),
        )->layout("components.admin-layout");
    }
}
