<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\ImageOptimizerService;
use Livewire\Component;

class OptimizeImages extends Component
{
    public bool $optimizing = false;
    public int $total = 0;
    public int $optimized = 0;
    public int $unoptimized = 0;
    public array $unoptimizedProducts = [];
    public ?string $lastResult = null;
    public ?string $lastError = null;
    public int $processed = 0;
    public int $successCount = 0;
    public int $failCount = 0;
    public ?string $currentModelo = null;

    public function mount(ImageOptimizerService $service)
    {
        $stats = $service->getStats();
        $this->total = $stats['total'];
        $this->optimized = $stats['optimized'];
        $this->unoptimized = $stats['unoptimized'];
        $this->unoptimizedProducts = $service->getUnoptimizedProducts();
    }

    public function optimizeAll()
    {
        if ($this->optimizing) {
            return;
        }

        $this->optimizing = true;
        $this->processed = 0;
        $this->successCount = 0;
        $this->failCount = 0;
        $this->lastResult = null;
        $this->lastError = null;

        try {
            $service = app(ImageOptimizerService::class);

            $result = $service->optimizeAll(function ($processed, $total, $productResult) {
                $this->processed = $processed;

                if ($productResult['success']) {
                    $this->successCount++;
                } else {
                    $this->failCount++;
                }

                $this->currentModelo = $productResult['modelo'];
            });

            $msg = "Optimización completada: {$result['success']} exitosos, {$result['failed']} fallos.";
            if ($result['failed'] > 0) {
                $errors = implode(', ', array_map(
                    fn($m, $e) => "{$m}: {$e}",
                    array_keys($result['errors']),
                    $result['errors']
                ));
                $msg .= " Errores: {$errors}";
                $this->lastError = $msg;
            } else {
                $this->lastResult = $msg;
            }

            $stats = $service->getStats();
            $this->total = $stats['total'];
            $this->optimized = $stats['optimized'];
            $this->unoptimized = $stats['unoptimized'];
            $this->unoptimizedProducts = $service->getUnoptimizedProducts();

        } catch (\Exception $e) {
            $this->lastError = "Error inesperado: " . $e->getMessage();
        } finally {
            $this->optimizing = false;
            $this->currentModelo = null;
        }
    }

    public function render()
    {
        return view('livewire.admin.optimize-images')
            ->layout('components.admin-layout', ['title' => 'Optimizar Imágenes']);
    }
}
