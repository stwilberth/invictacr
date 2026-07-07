<?php

namespace App\Console\Commands;

use App\Services\GoogleAdsService;
use Illuminate\Console\Command;

class SyncGoogleAds extends Command
{
    protected $signature = 'sync:google-ads {--days=7 : Number of days to sync}';
    protected $description = 'Sync Google Ads campaign data for the last N days';

    public function handle(GoogleAdsService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Google Ads not configured. Set GOOGLE_ADS_ACCESS_TOKEN, GOOGLE_ADS_CUSTOMER_ID, and GOOGLE_ADS_DEVELOPER_TOKEN.');
            return Command::FAILURE;
        }

        $days = (int) $this->option('days');
        $total = 0;

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i);
            $count = $service->syncDaily($date);
            $total += $count;
            $this->line("Synced {$count} campaigns for {$date->format('Y-m-d')}");
        }

        $this->info("Synced {$total} campaign entries from Google Ads.");
        return Command::SUCCESS;
    }
}
