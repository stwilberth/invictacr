<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VisitorDetail extends Component
{
    public Visitor $visitor;
    public $productsSeen = [];
    public $invoices = [];

    public function mount($id)
    {
        $this->visitor = Visitor::with('user')->findOrFail($id);

        // Relojes vistos agrupados con tiempo total
        $this->productsSeen = VisitorEvent::where('visitor_id', $this->visitor->id)
            ->whereNotNull('product_id')
            ->select(
                'product_id',
                DB::raw('COUNT(*) as views'),
                DB::raw('COALESCE(SUM(duration_seconds), 0) as total_seconds'),
                DB::raw('MAX(created_at) as last_viewed_at')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_seconds')
            ->with('product:id,modelo,title,slug,imagen,precio_venta')
            ->get()
            ->toArray();

        // Facturas asociadas por user_id o email
        $emails = array_filter([$this->visitor->email, $this->visitor->user?->email]);

        $this->invoices = Invoice::query()
            ->when($this->visitor->user_id, fn($q) => $q->orWhereHas('client', fn($c) => $c->where('email', $this->visitor->user->email ?? '')))
            ->when(!empty($emails), fn($q) => $q->orWhereIn('client_email', $emails))
            ->when($this->visitor->phone, fn($q) => $q->orWhere('client_phone', $this->visitor->phone))
            ->latest()
            ->take(10)
            ->get()
            ->unique('id')
            ->values()
            ->toArray();
    }

    public function render()
    {
        $events = VisitorEvent::where('visitor_id', $this->visitor->id)
            ->with('product:id,modelo,title,slug')
            ->latest('created_at')
            ->paginate(50);

        return view('livewire.admin.visitor-detail', compact('events'))
            ->layout('components.admin-layout', ['title' => 'Perfil de Visitante']);
    }
}
