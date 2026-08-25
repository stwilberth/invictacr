<?php

namespace App\Livewire\Admin;

use App\Models\AiCeoRecommendation;
use App\Services\CeoAdvisorService;
use Livewire\Component;

class CeoAdvisor extends Component
{
    public array $recommendations = [];
    public ?string $lastBatchKey = null;
    public ?string $generatedAt = null;
    public bool $generating = false;
    public array $history = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $latestKey = AiCeoRecommendation::max('batch_key');
        $this->lastBatchKey = $latestKey;

        if ($latestKey) {
            $current = AiCeoRecommendation::where('batch_key', $latestKey)
                ->orderByRaw("FIELD(priority, 'alta', 'media', 'baja')")
                ->orderByRaw("FIELD(category, 'urgente', 'oportunidad', 'estrategia')")
                ->get();

            $this->recommendations = $current->map(fn($r) => [
                'id' => $r->id,
                'category' => $r->category,
                'priority' => $r->priority,
                'title' => $r->title,
                'rationale' => $r->rationale,
                'action' => $r->action,
                'status' => $r->status,
            ])->toArray();

            $this->generatedAt = optional($current->first()?->generated_at)->format('d/m/Y H:i');
        } else {
            $this->recommendations = [];
            $this->generatedAt = null;
        }

        // Historial de batches anteriores (resumen)
        $this->history = AiCeoRecommendation::where('batch_key', '!=', $latestKey ?? '')
            ->orderByDesc('batch_key')
            ->get()
            ->groupBy('batch_key')
            ->map(function ($group, $key) {
                return [
                    'batch_key' => $key,
                    'generated_at' => optional($group->first()->generated_at)->format('d/m/Y H:i'),
                    'total' => $group->count(),
                    'hechas' => $group->where('status', 'hecho')->count(),
                    'descartadas' => $group->where('status', 'descartado')->count(),
                ];
            })
            ->take(10)
            ->values()
            ->toArray();
    }

    public function generatePlan(): void
    {
        $this->generating = true;

        try {
            $service = new CeoAdvisorService();
            $count = $service->generatePlan();

            $this->loadData();

            if ($count > 0) {
                session()->flash('message', "Nuevo plan de acción generado: {$count} recomendaciones.");
            } else {
                session()->flash('error', 'No se pudo generar el plan. Verifica la configuración de la IA (ANTHROPIC_API_KEY) e intenta de nuevo.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el plan: ' . $e->getMessage());
        }

        $this->generating = false;
    }

    public function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, ['pendiente', 'hecho', 'descartado'], true)) {
            return;
        }

        $rec = AiCeoRecommendation::find($id);
        if (!$rec) {
            return;
        }

        $rec->status = $status;
        $rec->resolved_at = $status === 'pendiente' ? null : now();
        $rec->save();

        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.ceo-advisor')
            ->layout('components.admin-layout', ['title' => 'Asesor CEO IA']);
    }
}
