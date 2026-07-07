<?php

namespace App\Console\Commands;

use App\Services\FacebookBusinessService;
use Illuminate\Console\Command;

class SyncFacebook extends Command
{
    protected $signature = 'sync:facebook {--days=7 : Number of days of insights to sync} {--posts=20 : Number of recent posts to sync}';
    protected $description = 'Sync Facebook page insights and posts';

    public function handle(FacebookBusinessService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Facebook not configured. Set META_ACCESS_TOKEN and META_PAGE_ID.');
            return Command::FAILURE;
        }

        $days = (int) $this->option('days');
        $insightsCount = 0;

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i);
            $insight = $service->fetchPageInsights($date);
            if ($insight) {
                $insightsCount++;
                $this->line("Synced Facebook insights for {$date->format('Y-m-d')}");
            }
        }

        $posts = (int) $this->option('posts');
        $postsCount = $service->fetchPosts(now()->subDays($days), $posts);

        $this->info("Synced {$insightsCount} days of insights and {$postsCount} posts from Facebook.");
        return Command::SUCCESS;
    }
}
