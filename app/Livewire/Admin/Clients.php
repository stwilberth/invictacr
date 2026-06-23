<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $email, $phone, $notes;
    public $editingClientId = null;
    public $showForm = false;

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
