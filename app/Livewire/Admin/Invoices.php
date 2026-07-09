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

        $invoices = $query->with('abonos')->latest()->paginate(20);

        return view('livewire.admin.invoices', compact('invoices'))
            ->layout('components.admin-layout');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterShipping', 'filterAbonos', 'dateFrom', 'dateTo', 'totalMin', 'totalMax']);
    }
}
