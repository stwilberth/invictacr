<?php

namespace App\Livewire\Admin;

use App\Models\SearchLog;
use Livewire\Component;
use Livewire\WithPagination;

class SearchLogs extends Component
{
    use WithPagination;

    public $search = "";
    public $sortField = "created_at";
    public $sortDirection = "desc";

    protected $queryString = ["search"];

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
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(50);

        return view("livewire.admin.search-logs", compact("logs"))
            ->layout("components.admin-layout");
    }
}
