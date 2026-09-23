<?php

namespace App\Console\Commands;

use App\Services\FacebookAdsService;
use Illuminate\Console\Command;

class SyncFacebookAds extends Command
{
    protected $signature = 'sync:facebook-ads {--days=7 : Number of days to sync} {--level=all : Insight level: campaign|adset|ad|all}';
    protected $description = 'Sync Facebook Ads campaign/adset/ad data for the last N days with conversions, ROAS and CPA';

    public function handle(FacebookAdsService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Facebook Ads not configured. Set META_ACCESS_TOKEN and FB_AD_ACCOUNT_ID.');
            return Command::FAILURE;
        }

        $days = (int) $this->option('days');
        $level = $this->option('level');
        $total = 0;

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i);

            if ($level === 'all') {
                $result = $service->syncDailyAllLevels($date);
                $sub = $result['campaigns'] + $result['adsets'] + $result['ads'];
                $total += $sub;
                if ($sub > 0) {
                    $this->line("Synced {$sub} entries (c:{$result['campaigns']} + as:{$result['adsets']} + a:{$result['ads']}) for {$date->format('Y-m-d')}");
                }
            } else {
                $count = $service->syncDaily($date, $level);
                $total += $count;
                if ($count > 0) {
                    $this->line("Synced {$count} {$level} entries for {$date->format('Y-m-d')}");
                }
            }
        }

        $this->info("Synced {$total} total entries from Facebook Ads (level: {$level}).");
        return Command::SUCCESS;
    }
}