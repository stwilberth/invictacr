<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class Campaigns extends Component
{
    public $activeTab = 'generator';
    public $selectedProductId;
    public $campaignType = 'single';
    public $generatedContent = null;

    public function generateAd()
    {
        $product = Product::find($this->selectedProductId);
        if (!$product) {
            session()->flash('error', 'Selecciona un producto.');
            return;
        }

        $this->generatedContent = [
            'title' => "🔥 Reloj Invicta {$product->modelo}",
            'description' => "Tenemos el Invicta perfecto para ti 💪\nModelo: {$product->modelo}\nPrecio: ₡" . number_format($product->precio_venta * (1 - ($product->descuento ?? 0) / 100), 0) . "\n¡Escríbenos al WhatsApp!",
            'model' => $product->modelo,
            'price' => $product->price_after_discount,
            'image' => $product->imagen,
        ];

        session()->flash('message', 'Anuncio generado. Copia el texto y compártelo.');
    }

    public function render()
    {
        $products = Product::where('activo', true)->where('precio_venta', '>', 0)->orderBy('modelo')->get();

        return view('livewire.admin.campaigns', compact('products'))
            ->layout('components.admin-layout');
    }
}
