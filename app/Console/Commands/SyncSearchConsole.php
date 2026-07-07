<?php

namespace App\Console\Commands;

use App\Services\GoogleSearchConsoleService;
use Illuminate\Console\Command;

class SyncSearchConsole extends Command
{
    protected $signature = 'sync:search-console {--days=7 : Number of days to sync}';
    protected $description = 'Sync Google Search Console data for the last N days';

    public function handle(GoogleSearchConsoleService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Search Console not configured. Set GOOGLE_SEARCH_CONSOLE_ACCESS_TOKEN and GOOGLE_SEARCH_CONSOLE_SITE_URL.');
            return Command::FAILURE;
        }

        $days = (int) $this->option('days');
        $total = 0;

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i);
            $count = $service->syncDaily($date);
            $total += $count;
            $this->line("Synced {$count} search queries for {$date->format('Y-m-d')}");
        }

        $this->info("Synced {$total} search console entries.");
        return Command::SUCCESS;
    }
}
