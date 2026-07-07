<?php

namespace App\Livewire\Admin;

use App\Models\GitHubCommit;
use App\Services\GitHubService;
use Livewire\Component;

class GitHubReport extends Component
{
    public array $commits = [];
    public array $stats = [];
    public bool $syncing = false;
    public string $branch = 'main';

    public function mount(): void
    {
        $this->loadData();
    }

    public function sync(): void
    {
        $this->syncing = true;

        try {
            $service = app(GitHubService::class);
            if (!$service->isConfigured()) {
                session()->flash('error', 'GitHub no configurado. Revisa GITHUB_TOKEN, GITHUB_OWNER, GITHUB_REPO.');
                $this->syncing = false;
                return;
            }

            $count = $service->fetchCommits($this->branch, 50);
            $this->loadData();
            session()->flash('message', "Sincronizados {$count} commits.");
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }

        $this->syncing = false;
    }

    public function loadData(): void
    {
        $this->commits = GitHubCommit::where('branch', $this->branch)
            ->latest('committed_at')
            ->take(100)
            ->get()
            ->toArray();

        $all = GitHubCommit::where('branch', $this->branch)->get();

        $this->stats = [
            'total' => $all->count(),
            'additions' => $all->sum('additions'),
            'deletions' => $all->sum('deletions'),
            'files_changed' => $all->sum('files_changed'),
            'unique_authors' => $all->pluck('author_name')->unique()->count(),
            'deploy_commits' => $all->filter(fn($c) =>
                str_contains(strtolower($c->message), 'deploy') ||
                str_contains(strtolower($c->message), 'release') ||
                str_contains(strtolower($c->message), 'production')
            )->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.github-report')
            ->layout('components.admin-layout', ['title' => 'Reporte GitHub']);
    }
}
