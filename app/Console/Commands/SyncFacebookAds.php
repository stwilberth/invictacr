<?php

namespace App\Console\Commands;

use App\Services\FacebookAdsService;
use Illuminate\Console\Command;

class SyncFacebookAds extends Command
{
    protected $signature = 'sync:facebook-ads {--days=7 : Number of days to sync}';
    protected $description = 'Sync Facebook Ads campaign data for the last N days';

    public function handle(FacebookAdsService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Facebook Ads not configured. Set META_ACCESS_TOKEN and FB_AD_ACCOUNT_ID.');
            return Command::FAILURE;
        }

        $days = (int) $this->option('days');
        $total = 0;

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i);
            $count = $service->syncDaily($date);
            $total += $count;
            if ($count > 0) {
                $this->line("Synced {$count} ad campaigns for {$date->format('Y-m-d')}");
            }
        }

        $this->info("Synced {$total} campaign entries from Facebook Ads.");
        return Command::SUCCESS;
    }
}
