<?php

namespace App\Livewire\Admin;

use App\Models\Visitor;
use Livewire\Component;
use Livewire\WithPagination;

class Visitors extends Component
{
    use WithPagination;

    public $search = "";
    public $filter = "";
    public $sortField = "last_seen_at";
    public $sortDirection = "desc";

    public $totalVisitors = 0;
    public $todayVisitors = 0;
    public $activeNow = 0;
    public $whatsappRecent = 0;
    public $registeredCount = 0;

    protected $queryString = ["search", "filter"];

    public function mount()
    {
        $this->loadStats();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function loadStats()
    {
        $this->totalVisitors = Visitor::count();
        $this->todayVisitors = Visitor::where('last_seen_at', '>=', now()->startOfDay())->count();
        $this->activeNow = Visitor::where('last_seen_at', '>=', now()->subMinutes(5))->count();
        $this->whatsappRecent = Visitor::where('whatsapp_clicked_at', '>=', now()->subHours(24))->count();
        $this->registeredCount = Visitor::whereNotNull('user_id')->count();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === "asc" ? "desc" : "asc";
        } else {
            $this->sortField = $field;
            $this->sortDirection = "desc";
        }
    }

    public function render()
    {
        $visitors = Visitor::query()
            ->with('user')
            ->withCount(['events as products_seen' => function ($q) {
                $q->where('type', 'product_view')->select(\Illuminate\Support\Facades\DB::raw('count(distinct product_id)'));
            }])
            ->where(function ($q) {
                if ($this->search) {
                    $term = "%{$this->search}%";
                    $q->where(function ($sub) use ($term) {
                        $sub->where('name', 'like', $term)
                            ->orWhere('email', 'like', $term)
                            ->orWhere('phone', 'like', $term)
                            ->orWhere('uuid', 'like', $term)
                            ->orWhere('ip', 'like', $term);
                    });
                }

                match ($this->filter) {
                    'whatsapp' => $q->where('whatsapp_clicked_at', '>=', now()->subHours(24)),
                    'registered' => $q->whereNotNull('user_id'),
                    'with_contact' => $q->where(function ($sub) {
                        $sub->whereNotNull('phone')->orWhereNotNull('email');
                    }),
                    'ads' => $q->whereNotNull('utm_source'),
                    default => null,
                };
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(30);

        return view('livewire.admin.visitors', compact('visitors'))
            ->layout('components.admin-layout', ['title' => 'Visitantes']);
    }
}
