<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Invoice;
use App\Models\Subscriber;
use App\Models\SyncLog;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];
    public $recentSyncs = [];

    public function mount()
    {
        $this->stats = [
            'products' => Product::where('activo', true)->count(),
            'invoices' => Invoice::count(),
            'subscribers' => Subscriber::count(),
            'low_stock' => Product::where('stock', '<', 5)->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'upcoming' => Product::where('precio_venta', 0)->count(),
        ];

        $this->recentSyncs = SyncLog::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.admin-layout');
    }
}
