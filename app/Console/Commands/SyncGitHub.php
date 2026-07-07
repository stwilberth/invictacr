<?php

namespace App\Console\Commands;

use App\Services\GitHubService;
use Illuminate\Console\Command;

class SyncGitHub extends Command
{
    protected $signature = 'sync:github {--branch=main : Branch to sync} {--limit=50 : Number of commits to fetch}';
    protected $description = 'Sync GitHub commits for the repository';

    public function handle(GitHubService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('GitHub not configured. Set GITHUB_TOKEN, GITHUB_OWNER, and GITHUB_REPO.');
            return Command::FAILURE;
        }

        $branch = $this->option('branch');
        $limit = (int) $this->option('limit');

        $count = $service->fetchCommits($branch, $limit);

        $this->info("Synced {$count} commits from {$branch} branch.");
        return Command::SUCCESS;
    }
}
