<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use App\Models\Product;
use Livewire\Component;

class Messenger extends Component
{
    public $tab = 'pendientes';

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['pendientes', 'entregados'], true)) {
            $this->tab = $tab;
        }
    }

    /**
     * Única transición permitida desde esta vista: pendiente/creando → entregado.
     * No se expone ningún otro cambio de estado.
     */
    public function markDelivered(int $invoiceId): void
    {
        $invoice = Invoice::whereIn('shipping_status', ['pendiente', 'creando'])
            ->findOrFail($invoiceId);

        $invoice->update(['shipping_status' => 'entregado']);

        session()->flash('message', "Entrega {$invoice->invoice_number} marcada como entregada.");
    }

    public function render()
    {
        $invoices = Invoice::query()
            ->whereIn('shipping_status', ['pendiente', 'creando'])
            ->whereNotNull('delivery_date')
            ->with(['items.product', 'abonos'])
            ->orderBy('delivery_date')
            ->orderByRaw("CASE WHEN delivery_time_start IS NULL OR delivery_time_start = '' THEN 1 ELSE 0 END")
            ->orderBy('delivery_time_start')
            ->orderBy('id')
            ->get();

        // Solo los entregados del día: programados para hoy o marcados hoy.
        $today = now()->toDateString();
        $delivered = Invoice::query()
            ->where('shipping_status', 'entregado')
            ->where(function ($query) use ($today) {
                $query->whereDate('delivery_date', $today)
                    ->orWhereDate('updated_at', $today);
            })
            ->with(['items.product', 'abonos'])
            ->orderByDesc('updated_at')
            ->get();

        // Las facturas creadas desde administración históricamente no siempre
        // guardan product_id; conservar el fallback por modelo evita perder la foto.
        $models = $invoices->merge($delivered)
            ->flatMap(fn ($invoice) => $invoice->items->pluck('product_model'))
            ->filter()
            ->unique()
            ->values();
        $productByModelo = Product::whereIn('modelo', $models)->get()->keyBy('modelo');

        return view('livewire.admin.messenger', compact('invoices', 'delivered', 'productByModelo'))
            ->layout('components.admin-layout', ['title' => 'Mensajero']);
    }
}
