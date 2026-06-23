<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class Inventory extends Component
{
    public $search = '';
    public $filterStock = '';

    public function updateStock($productId, $stock)
    {
        Product::findOrFail($productId)->update(['stock' => max(0, (int)$stock)]);
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('modelo', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStock === 'low') {
            $query->where('stock', '<', 5)->where('stock', '>', 0);
        } elseif ($this->filterStock === 'out') {
            $query->where('stock', 0);
        } elseif ($this->filterStock === 'available') {
            $query->where('stock', '>', 0);
        }

        $products = $query->orderBy('stock', 'asc')->paginate(50);

        return view('livewire.admin.inventory', compact('products'))
            ->layout('components.admin-layout');
    }
}
