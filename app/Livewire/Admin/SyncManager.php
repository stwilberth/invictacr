<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\SyncLog;
use App\Services\VariedadesSyncService;
use Livewire\Component;

class SyncManager extends Component
{
    public bool $syncing = false;
    public ?string $lastResult = null;
    public ?string $lastError = null;

    public function triggerSync()
    {
        if ($this->syncing) {
            return;
        }

        $this->syncing = true;
        $this->lastResult = null;
        $this->lastError = null;

        try {
            $service = app(VariedadesSyncService::class);
            $result = $service->execute();

            if ($result["success"]) {
                $this->lastResult = $result["message"];
            } else {
                $this->lastError = $result["error"];
            }
        } catch (\Exception $e) {
            $this->lastError = "Error inesperado: " . $e->getMessage();
        } finally {
            $this->syncing = false;
        }
    }

    public function getStatsProperty()
    {
        return [
            "total" => Product::where("activo", true)->count(),
            "from_variedades" => Product::where("activo", true)->where("bloqueado", false)->count(),
            "propios" => Product::where("activo", true)->where("bloqueado", true)->count(),
            "with_stock" => Product::where("stock", ">", 0)->count(),
            "sin_stock" => Product::where("stock", 0)->where("precio_venta", ">", 0)->count(),
            "upcoming" => Product::where("proximo", true)->count(),
        ];
    }

    public function getRecentLogsProperty()
    {
        return SyncLog::latest()->take(20)->get();
    }

    public function getLastSyncProperty()
    {
        return SyncLog::latest()->first();
    }

    public function getLastSuccessProperty()
    {
        return SyncLog::where("status", "completed")->latest()->first();
    }

    public function render()
    {
        return view('livewire.admin.sync-manager')
            ->layout('components.admin-layout', ['title' => 'Sincronizar']);
    }
}
