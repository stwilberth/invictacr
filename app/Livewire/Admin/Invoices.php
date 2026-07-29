<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class Invoices extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterShipping = '';
    public $filterAbonos = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $totalMin = '';
    public $totalMax = '';

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $query = Invoice::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', "%{$this->search}%")
                  ->orWhere('client_name', 'like', "%{$this->search}%")
                  ->orWhere('client_phone', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterShipping) {
            $query->where('shipping_status', $this->filterShipping);
        }

        if ($this->filterAbonos === 'con_abonos') {
            $query->has('abonos');
        } elseif ($this->filterAbonos === 'sin_abonos') {
            $query->doesntHave('abonos');
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->totalMin !== '') {
            $query->where('total', '>=', (float) $this->totalMin);
        }

        if ($this->totalMax !== '') {
            $query->where('total', '<=', (float) $this->totalMax);
        }

        $invoices = $query->with('abonos', 'items.product')->latest()->paginate(20);

        $totalsQuery = clone $query;
        $totals = (object) [
            'count' => (clone $totalsQuery)->count(),
            'totalAmount' => (clone $totalsQuery)->sum('total'),
            'totalDiscount' => (clone $totalsQuery)->sum('discount'),
            'totalShipping' => (clone $totalsQuery)->sum('shipping'),
            'totalUtility' => (clone $totalsQuery)->sum('estimated_utility'),
        ];
        $totals->average = $totals->count > 0 ? $totals->totalAmount / $totals->count : 0;

        $totals->totalAbonado = 0;
        $totals->saldoPendiente = 0;
        if ($this->filterStatus === 'apartado') {
            $invoiceIds = (clone $totalsQuery)->pluck('id');
            $totals->totalAbonado = \App\Models\Abono::whereIn('invoice_id', $invoiceIds)->sum('amount');
            $totals->saldoPendiente = $totals->totalAmount - $totals->totalAbonado;
        }

        return view('livewire.admin.invoices', compact('invoices', 'totals'))
            ->layout('components.admin-layout');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterShipping', 'filterAbonos', 'totalMin', 'totalMax']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    public function delete($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $invoice->items()->delete();
        $invoice->abonos()->delete();
        $invoice->delete();

        session()->flash('message', 'Factura eliminada correctamente.');
    }
}
