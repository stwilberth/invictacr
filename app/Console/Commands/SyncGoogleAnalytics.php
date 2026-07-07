<?php

namespace App\Console\Commands;

use App\Services\GoogleAnalyticsService;
use Illuminate\Console\Command;

class SyncGoogleAnalytics extends Command
{
    protected $signature = 'sync:google-analytics {--days=7 : Number of days to sync}';
    protected $description = 'Sync Google Analytics data for the last N days';

    public function handle(GoogleAnalyticsService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Google Analytics not configured. Set GOOGLE_ANALYTICS_ACCESS_TOKEN and GOOGLE_ANALYTICS_PROPERTY_ID.');
            return Command::FAILURE;
        }

        $days = (int) $this->option('days');
        $synced = 0;

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i);
            $report = $service->fetchDailyReport($date);

            if ($report) {
                $synced++;
                $this->line("Synced GA data for {$date->format('Y-m-d')}");
            }
        }

        $this->info("Synced {$synced} days of Google Analytics data.");
        return Command::SUCCESS;
    }
}
