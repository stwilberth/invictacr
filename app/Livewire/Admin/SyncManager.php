<?php

namespace App\Livewire\Admin;

use App\Models\SyncLog;
use App\Services\VariedadesSyncService;
use Livewire\Component;

class SyncManager extends Component
{
    public bool $syncing = false;
    public ?string $lastResult = null;
    public ?string $lastError = null;
    public ?array $lastDetails = null;

    public function triggerSync()
    {
        if ($this->syncing) {
            return;
        }

        $this->syncing = true;
        $this->lastResult = null;
        $this->lastError = null;
        $this->lastDetails = null;

        try {
            $service = app(VariedadesSyncService::class);
            $result = $service->execute();

            if ($result["success"]) {
                $this->lastResult = $result["message"];
                $this->lastDetails = $result["details"] ?? null;
            } else {
                $this->lastError = $result["error"];
            }
        } catch (\Exception $e) {
            $this->lastError = "Error inesperado: " . $e->getMessage();
        } finally {
            $this->syncing = false;
        }
    }

    public function getLastSuccessProperty()
    {
        return SyncLog::where("status", "completed")->latest()->first();
    }

    public function render()
    {
        return view('livewire.admin.sync-manager');
    }
}
