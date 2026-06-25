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
    public $filterLocalImage = false;

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
            $query->where(function ($q) {
                $q->where("title", "like", "%{$this->search}%")->orWhere(
                    "modelo",
                    "like",
                    "%{$this->search}%",
                );
            });
        }

        if ($this->filterGender) {
            $query->where("genero", $this->filterGender);
        }

        if ($this->filterColeccion) {
            $query->where("coleccion", $this->filterColeccion);
        }

        if ($this->filterLocalImage) {
            $query->where("imagen", "like", "/storage/relojes/%");
        }

        $products = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);
        $colecciones = Product::whereNotNull("coleccion")
            ->distinct()
            ->pluck("coleccion");

        return view(
            "livewire.admin.products",
            compact("products", "colecciones"),
        )->layout("components.admin-layout");
    }
}
