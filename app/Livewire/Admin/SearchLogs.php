<?php

namespace App\Livewire\Admin;

use App\Models\SearchLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class SearchLogs extends Component
{
    use WithPagination;

    public $search = "";
    public $sortField = "created_at";
    public $sortDirection = "desc";
    public $filterResults = "";

    public $totalSearches = 0;
    public $aiSearches = 0;
    public $aiSkippedSearches = 0;
    public $noResultsSearches = 0;
    public $uniqueQueries = 0;
    public $topQueries = [];

    protected $queryString = ["search", "filterResults"];

    public function mount()
    {
        $this->loadStats();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterResults()
    {
        $this->resetPage();
    }

    public function loadStats()
    {
        $this->totalSearches = SearchLog::count();
        $this->aiSearches = SearchLog::where("used_ai", true)->count();
        $this->aiSkippedSearches = SearchLog::whereNotNull("ai_skipped_reason")->count();
        $this->noResultsSearches = SearchLog::where("results_count", 0)->count();
        $this->uniqueQueries = SearchLog::select("query")->distinct()->count();

        $this->topQueries = SearchLog::select("query", DB::raw("COUNT(*) as count"))
            ->groupBy("query")
            ->orderByDesc("count")
            ->take(10)
            ->get()
            ->toArray();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === "asc" ? "desc" : "asc";
        } else {
            $this->sortField = $field;
            $this->sortDirection = "asc";
        }
    }

    public function render()
    {
        $logs = SearchLog::with("user")
            ->where(function ($q) {
                if ($this->search) {
                    $q->where("query", "like", "%{$this->search}%");
                }
                if ($this->filterResults === "no_results") {
                    $q->where("results_count", 0);
                } elseif ($this->filterResults === "with_results") {
                    $q->where("results_count", ">", 0);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(50);

        return view("livewire.admin.search-logs", compact("logs"))
            ->layout("components.admin-layout", ["title" => "Búsquedas"]);
    }
}
