<?php

namespace App\Console\Commands;

use App\Services\VariedadesSyncService;
use Illuminate\Console\Command;

class SyncStock extends Command
{
    protected $signature = "stock:sync";
    protected $description = "Sync products stock from variedadescr.com API";

    public function handle()
    {
        $this->info("=== VariedadesCR Stock Sync ===");

        $service = app(VariedadesSyncService::class);
        $result = $service->execute();

        if ($result["success"]) {
            $this->info("\n=== Sync Complete ===");
            $this->info($result["message"]);
        } else {
            $this->error("Sync failed: " . ($result["error"] ?? "Unknown error"));
            return 1;
        }
    }
}
