<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = 'all';
    public $filterVerified = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterVerified()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterRole === 'admin') {
            $query->where('is_admin', true);
        } elseif ($this->filterRole === 'user') {
            $query->where('is_admin', false);
        }

        if ($this->filterVerified === 'yes') {
            $query->whereNotNull('email_verified_at');
        } elseif ($this->filterVerified === 'no') {
            $query->whereNull('email_verified_at');
        }

        $users = $query->latest()->paginate(20);
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalVerified = User::whereNotNull('email_verified_at')->count();

        return view('livewire.admin.users', compact('users', 'totalUsers', 'totalAdmins', 'totalVerified'))
            ->layout('components.admin-layout');
    }
}
