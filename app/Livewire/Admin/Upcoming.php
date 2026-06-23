<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class Upcoming extends Component
{
    public $search = '';

    public function activate($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update([
            'precio_venta' => $product->precio_original ?? 1,
            'stock' => max(1, $product->stock),
        ]);
        session()->flash('message', "Producto {$product->modelo} activado.");
    }

    public function render()
    {
        $query = Product::where('precio_venta', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('modelo', 'like', "%{$this->search}%");
            });
        }

        $products = $query->latest()->paginate(20);

        return view('livewire.admin.upcoming', compact('products'))
            ->layout('components.admin-layout');
    }
}
