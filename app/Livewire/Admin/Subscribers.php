<?php

namespace App\Livewire\Admin;

use App\Models\Subscriber;
use Livewire\Component;
use Livewire\WithPagination;

class Subscribers extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function toggle($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->update(['active' => !$subscriber->active]);
    }

    public function delete($id)
    {
        Subscriber::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = Subscriber::query();

        if ($this->search) {
            $query->where('email', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus === 'active') {
            $query->where('active', true);
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('active', false);
        }

        $subscribers = $query->latest()->paginate(20);
        $total = Subscriber::count();
        $activeCount = Subscriber::where('active', true)->count();
        $inactiveCount = $total - $activeCount;

        return view('livewire.admin.subscribers', compact('subscribers', 'total', 'activeCount', 'inactiveCount'))
            ->layout('components.admin-layout');
    }
}
