<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $email, $phone, $notes;
    public $editingClientId = null;
    public $showForm = false;
    public $extractedCount = 0;

    public function extractFromInvoices()
    {
        $invoices = Invoice::select('client_name', 'client_email', 'client_phone', 'customer_address')
            ->whereNotNull('client_name')
            ->where('client_name', '!=', '')
            ->groupBy('client_name', 'client_email', 'client_phone', 'customer_address')
            ->orderByRaw('MAX(created_at) desc')
            ->get();

        $imported = 0;
        foreach ($invoices as $inv) {
            $exists = Client::where('name', $inv->client_name)
                ->orWhere(function ($q) use ($inv) {
                    if ($inv->client_email) $q->orWhere('email', $inv->client_email);
                    if ($inv->client_phone) $q->orWhere('phone', $inv->client_phone);
                })
                ->exists();

            if (!$exists) {
                Client::create([
                    'name' => $inv->client_name,
                    'email' => $inv->client_email ?: null,
                    'phone' => $inv->client_phone ?: null,
                    'address' => $inv->customer_address ?: null,
                ]);
                $imported++;
            }
        }

        $this->extractedCount = $imported;
    }

    public function create()
    {
        $this->reset(['name', 'email', 'phone', 'notes', 'editingClientId']);
        $this->showForm = true;
    }

    public function edit($clientId)
    {
        $client = Client::findOrFail($clientId);
        $this->editingClientId = $client->id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->notes = $client->notes;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $data = ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'notes' => $this->notes];

        if ($this->editingClientId) {
            Client::findOrFail($this->editingClientId)->update($data);
        } else {
            Client::create($data);
        }

        $this->showForm = false;
        $this->reset(['name', 'email', 'phone', 'notes', 'editingClientId']);
    }

    public function render()
    {
        $query = Client::query();
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });
        }
        $clients = $query->latest()->paginate(20);
        return view('livewire.admin.clients', compact('clients'))
            ->layout('components.admin-layout');
    }
}
