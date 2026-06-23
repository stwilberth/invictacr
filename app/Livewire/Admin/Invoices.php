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

    public function render()
    {
        $query = Invoice::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', "%{$this->search}%")
                  ->orWhere('client_name', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $invoices = $query->latest()->paginate(20);

        return view('livewire.admin.invoices', compact('invoices'))
            ->layout('components.admin-layout');
    }
}
