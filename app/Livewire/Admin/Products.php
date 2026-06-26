<?php

namespace App\Livewire\Admin;

use App\Models\Product;
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

        return view(
            "livewire.admin.products",
            compact("products", "colecciones"),
        )->layout("components.admin-layout");
    }
}
