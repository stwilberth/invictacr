<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAllAnalytics extends Command
{
    protected $signature = 'sync:all-analytics {--days=1}';
    protected $description = 'Sync all analytics platforms at once';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $this->call(SyncGoogleAnalytics::class, ['--days' => $days]);
        $this->call(SyncGoogleAds::class, ['--days' => $days]);
        $this->call(SyncSearchConsole::class, ['--days' => $days]);
        $this->call(SyncFacebook::class, ['--days' => $days, '--posts' => 20]);
        $this->call(SyncGitHub::class);

        $this->info('All analytics synced successfully.');
        return Command::SUCCESS;
    }
}
