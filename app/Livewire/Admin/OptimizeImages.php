<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\ImageOptimizerService;
use Livewire\Component;
use Livewire\WithPagination;

class OptimizeImages extends Component
{
    use WithPagination;

    public bool $optimizing = false;
    public ?string $lastResult = null;
    public ?string $lastError = null;
    public int $processed = 0;
    public int $successCount = 0;
    public int $failCount = 0;
    public ?string $currentModelo = null;
    public ?int $optimizingProductId = null;

    public string $search = '';
    public string $filterStatus = 'all'; // all, pending, optimized

    protected $queryString = ['search', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function optimizeAll()
    {
        if ($this->optimizing) {
            return;
        }

        set_time_limit(0);
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
        } catch (\Exception $e) {
            $this->lastError = "Error inesperado: " . $e->getMessage();
        } finally {
            $this->optimizing = false;
            $this->currentModelo = null;
        }
    }

    public function optimizeProduct($productId)
    {
        if ($this->optimizing || $this->optimizingProductId) {
            return;
        }

        $this->optimizingProductId = $productId;
        $this->lastResult = null;
        $this->lastError = null;

        try {
            $product = Product::findOrFail($productId);
            $service = app(ImageOptimizerService::class);
            $result = $service->optimizeProduct($product);

            if ($result['success']) {
                $sizes = [];
                if ($result['thumb']) $sizes[] = 'thumb ' . number_format($result['thumb_size'] / 1024, 1) . 'KB';
                if ($result['medium']) $sizes[] = 'medium ' . number_format($result['medium_size'] / 1024, 1) . 'KB';
                if ($result['large']) $sizes[] = 'large ' . number_format($result['large_size'] / 1024, 1) . 'KB';
                $this->lastResult = "{$product->modelo}: WebP generados (" . implode(', ', $sizes) . ")";
            } else {
                $this->lastError = "{$product->modelo}: {$result['error']}";
            }
        } catch (\Exception $e) {
            $this->lastError = "Error: " . $e->getMessage();
        } finally {
            $this->optimizingProductId = null;
        }
    }

    public function render()
    {
        $service = app(ImageOptimizerService::class);

        $query = Product::whereNotNull('imagen');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('modelo', 'like', "%{$this->search}%")
                  ->orWhere('title', 'like', "%{$this->search}%");
            });
        }

        $products = $query->orderBy('modelo')->paginate(15);

        $stats = $service->getStats();

        $items = collect($products->items())->map(
            fn($p) => $service->getProductImageInfo($p)
        );

        if ($this->filterStatus === 'pending') {
            $items = $items->where('needs_optimization', true);
        } elseif ($this->filterStatus === 'optimized') {
            $items = $items->where('needs_optimization', false);
        }

        return view('livewire.admin.optimize-images', compact(
            'products', 'items', 'stats', 'service',
        ))->layout('components.admin-layout', ['title' => 'Optimizar Imágenes']);
    }
}
