<?php

namespace App\Livewire\Admin;

use App\Models\AiCeoRecommendation;
use App\Models\AiTimelineInsight;
use App\Models\ExternalFactor;
use App\Models\FacebookAdReport;
use App\Models\FacebookInsight;
use App\Models\FacebookPost;
use App\Models\GitHubCommit;
use App\Models\GoogleAdsReport;
use App\Models\GoogleAnalyticsReport;
use App\Models\Invoice;
use App\Models\SearchConsoleReport;
use App\Services\TimelineAiService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UnifiedTimeline extends Component
{
    public string $period = '30d';
    public array $events = [];
    public array $dailyRevenue = [];
    public array $conclusions = [];
    public array $periodTotals = [];
    public array $globalConclusion = [];
    public bool $generating = false;

    public function mount(): void
    {
        $this->loadData();
    }

    public function updatedPeriod(): void
    {
        $this->loadData();
    }

    protected function getDateRange(): array
    {
        return match ($this->period) {
            '7d' => [now()->subDays(7), now()],
            '90d' => [now()->subDays(90), now()],
            '365d' => [now()->subDays(365), now()],
            default => [now()->subDays(30), now()],
        };
    }

    protected function periodKey(string $date): string
    {
        $dt = \Carbon\Carbon::parse($date);
        if (in_array($this->period, ['90d', '365d'])) {
            return $dt->format('Y-m');
        }
        return $dt->format('o-W');
    }

    protected function periodLabel(string $key): string
    {
        if (preg_match('/^\d{4}-(0[1-9]|1[012])$/', $key)) {
            $months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            [$y, $m] = explode('-', $key);
            return $months[(int) $m - 1] . ' ' . $y;
        }
        [, $week] = explode('-', $key);
        return 'Semana ' . $week;
    }

    protected function periodType(): string
    {
        return in_array($this->period, ['90d', '365d']) ? 'mes' : 'semana';
    }

    public function loadData(): void
    {
        [$start, $end] = $this->getDateRange();

        // Daily revenue
        $invoices = Invoice::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->get();

        $this->dailyRevenue = $invoices
            ->groupBy(fn($i) => $i->created_at->format('Y-m-d'))
            ->map(fn($group) => (int) $group->sum('total'))
            ->toArray();

        $events = [];

        // GitHub commits
        foreach (GitHubCommit::whereBetween('committed_at', [$start, $end])->get() as $c) {
            $events[] = [
                'date' => $c->committed_at->format('Y-m-d'),
                'source' => 'github',
                'type' => 'commit',
                'icon' => 'fa-code-branch',
                'color' => 'gray',
                'title' => 'Commit: ' . str_replace(["\n", "\r"], ' ', trim($c->message)),
                'detail' => $c->author_name . ' · +' . $c->additions . '/-' . $c->deletions . ' · ' . $c->files_changed . ' archivos',
            ];
        }

        // Facebook posts
        foreach (FacebookPost::whereBetween('posted_at', [$start, $end])->get() as $p) {
            $events[] = [
                'date' => $p->posted_at->format('Y-m-d'),
                'source' => 'facebook',
                'type' => 'post',
                'icon' => 'fa-facebook',
                'color' => 'blue',
                'title' => 'Post: ' . ($p->message ? mb_substr(preg_replace('/\s+/', ' ', trim($p->message)), 0, 100) : '(sin texto)'),
                'detail' => '❤️ ' . $p->likes . ' · 💬 ' . $p->comments . ' · 🔄 ' . $p->shares,
            ];
        }

        // Facebook ad reports
        foreach (FacebookAdReport::whereBetween('report_date', [$start, $end])->get() as $a) {
            $events[] = [
                'date' => $a->report_date->format('Y-m-d'),
                'source' => 'facebook_ads',
                'type' => 'ad',
                'icon' => 'fa-dollar-sign',
                'color' => 'red',
                'title' => 'Anuncio FB: ' . $a->campaign_name,
                'detail' => '₡' . number_format($a->spend, 2) . ' · ' . number_format($a->impressions) . ' imp · ' . number_format($a->clicks) . ' clics',
            ];
        }

        // Google Ads reports
        foreach (GoogleAdsReport::whereBetween('report_date', [$start, $end])->get() as $a) {
            $events[] = [
                'date' => $a->report_date->format('Y-m-d'),
                'source' => 'google_ads',
                'type' => 'ad',
                'icon' => 'fa-ad',
                'color' => 'orange',
                'title' => 'Anuncio Google: ' . $a->campaign_name,
                'detail' => '₡' . number_format($a->cost, 2) . ' · ' . number_format($a->impressions) . ' imp · ' . number_format($a->clicks) . ' clics · ' . $a->conversions . ' conv',
            ];
        }

        // Google Analytics daily summary
        foreach (GoogleAnalyticsReport::whereBetween('report_date', [$start, $end])->get() as $g) {
            $events[] = [
                'date' => $g->report_date->format('Y-m-d'),
                'source' => 'google_analytics',
                'type' => 'metric',
                'icon' => 'fa-chart-line',
                'color' => 'cyan',
                'title' => 'GA: ' . $g->users . ' usuarios · ' . $g->sessions . ' sesiones',
                'detail' => number_format($g->pageviews) . ' páginas vista · ' . number_format($g->bounce_rate, 1) . '% rebote',
            ];
        }

        // Search Console (aggregate per day)
        foreach (SearchConsoleReport::whereBetween('report_date', [$start, $end])
            ->select('report_date', DB::raw('SUM(clicks) as total_clicks'), DB::raw('SUM(impressions) as total_impressions'))
            ->groupBy('report_date')
            ->get() as $s) {
            $events[] = [
                'date' => $s->report_date->format('Y-m-d'),
                'source' => 'search_console',
                'type' => 'metric',
                'icon' => 'fa-search',
                'color' => 'green',
                'title' => 'SC: ' . $s->total_clicks . ' clics · ' . number_format($s->total_impressions) . ' impresiones',
                'detail' => '',
            ];
        }

        // External factors
        foreach (ExternalFactor::where('active', true)
            ->whereBetween('event_date', [$start, $end])
            ->get() as $f) {
            $events[] = [
                'date' => $f->event_date->format('Y-m-d'),
                'source' => 'external',
                'type' => 'factor',
                'icon' => 'fa-exclamation-triangle',
                'color' => $f->impact_level === 'high' ? 'red' : ($f->impact_level === 'positive' ? 'green' : 'amber'),
                'title' => $f->title,
                'detail' => $f->description . ' (' . strtoupper($f->impact_level) . ')',
            ];
        }

        // CEO Advisor recommendations
        $ceoAreaIcons = [
            'marketing' => 'fa-bullhorn', 'programacion' => 'fa-code', 'inventario' => 'fa-boxes-stacked',
            'finanzas' => 'fa-chart-pie', 'seo' => 'fa-magnifying-glass', 'ventas' => 'fa-cart-shopping',
            'soporte' => 'fa-headset', 'operaciones' => 'fa-gears', 'legal' => 'fa-scale-balanced', 'rrhh' => 'fa-users',
        ];
        foreach (AiCeoRecommendation::whereBetween('generated_at', [$start, $end])
            ->where('status', '!=', 'descartado')
            ->orderByDesc('generated_at')
            ->get() as $rec) {
            $events[] = [
                'date' => $rec->generated_at->format('Y-m-d'),
                'source' => 'ceo_advisor',
                'type' => 'recommendation',
                'icon' => $ceoAreaIcons[$rec->area] ?? 'fa-bullseye',
                'color' => $rec->status === 'hecho' ? 'green' : ($rec->category === 'urgente' ? 'red' : 'cyan'),
                'title' => ($rec->status === 'hecho' ? '✅ ' : '') . $rec->title,
                'detail' => ucfirst($rec->area ?? 'general') . ' · ' . ucfirst($rec->category) . ' · ' . ucfirst($rec->priority),
            ];
        }

        // Add synthetic events: flag periods with revenue but NO marketing activity
        $marketingSources = ['facebook', 'facebook_ads', 'google_ads', 'google_analytics', 'search_console'];
        $periodHasMarketing = [];
        $periodHasAnyEvent = [];
        foreach ($events as $e) {
            $pk = $this->periodKey($e['date']);
            $periodHasAnyEvent[$pk] = true;
            if (in_array($e['source'], $marketingSources, true)) {
                $periodHasMarketing[$pk] = true;
            }
        }

        $addedGap = [];
        foreach ($this->dailyRevenue as $date => $rev) {
            if ($rev <= 0) {
                continue;
            }
            $pk = $this->periodKey($date);
            if (isset($addedGap[$pk]) || !empty($periodHasMarketing[$pk])) {
                continue;
            }
            $addedGap[$pk] = true;
            $events[] = [
                'date' => $date,
                'source' => 'marketing_gap',
                'type' => 'marketing_gap',
                'icon' => 'fa-bullhorn',
                'color' => 'red',
                'title' => 'Ventas sin actividad de marketing',
                'detail' => 'Se registraron ventas pero ninguna campaña, anuncio o publicación este período — probable causa de la caída.',
            ];
        }

        // Periods with revenue but absolutely no events at all
        $addedEmpty = [];
        foreach ($this->dailyRevenue as $date => $rev) {
            if ($rev <= 0) {
                continue;
            }
            $pk = $this->periodKey($date);
            if (isset($addedEmpty[$pk]) || !empty($periodHasAnyEvent[$pk]) || !empty($periodHasMarketing[$pk])) {
                continue;
            }
            $addedEmpty[$pk] = true;
            $events[] = [
                'date' => $date,
                'source' => 'sistema',
                'type' => 'revenue',
                'icon' => 'fa-coins',
                'color' => 'green',
                'title' => 'Sin eventos registrados — solo ventas',
                'detail' => 'Período sin datos de marketing, código ni métricas',
            ];
        }

        // Sort by date descending
        usort($events, fn($a, $b) => strcmp($b['date'], $a['date']));

        // Assign period keys
        foreach ($events as &$e) {
            $e['period_key'] = $this->periodKey($e['date']);
            $e['period_label'] = $this->periodLabel($e['period_key']);
        }
        unset($e);

        $this->events = $events;

        // Compute period totals (unique days per period, not per event)
        $periodDays = [];
        foreach ($events as $e) {
            $pk = $e['period_key'];
            if (!isset($periodDays[$pk][$e['date']])) {
                $periodDays[$pk][$e['date']] = true;
                $periodTotals[$pk] = ($periodTotals[$pk] ?? 0) + ($this->dailyRevenue[$e['date']] ?? 0);
            }
        }
        $this->periodTotals = $periodTotals ?? [];

        // Load saved AI conclusions
        $periods = array_unique(array_column($events, 'period_key'));
        $saved = AiTimelineInsight::whereIn('period_key', $periods)->get()->keyBy('period_key');
        $this->conclusions = [];
        foreach ($periods as $pk) {
            $this->conclusions[$pk] = $saved[$pk]->conclusion ?? '';
        }

        // Load global conclusion
        $global = AiTimelineInsight::where('period_key', 'global')->first();
        $this->globalConclusion = $global ? [
            'conclusion' => $global->conclusion ?? '',
            'advice' => $global->advice ?? '',
            'generated_at' => $global->generated_at,
        ] : [];
    }

    public function generateConclusions(): void
    {
        $this->generating = true;

        try {
            // Build grouped data (unique day revenue per period)
            $grouped = [];
            foreach ($this->events as $e) {
                $pk = $e['period_key'];
                if (!isset($grouped[$pk])) {
                    $grouped[$pk] = ['label' => $e['period_label'], 'events' => [], 'revenue' => 0, 'days' => []];
                }
                $grouped[$pk]['events'][] = $e;
                $grouped[$pk]['days'][$e['date']] = true;
            }
            // Sum unique day revenue per period
            foreach ($grouped as $pk => &$g) {
                foreach ($g['days'] as $date => $_) {
                    $g['revenue'] += $this->dailyRevenue[$date] ?? 0;
                }
            }
            unset($g);

            $service = new TimelineAiService($grouped, $this->dailyRevenue, $this->periodType());
            $saved = $service->generateAll();

            $this->loadData();
            if ($saved > 0) {
                session()->flash('message', "{$saved} conclusiones generadas por IA correctamente.");
            } else {
                session()->flash('info', 'Ya existen conclusiones para todos los períodos. Usa "Regenerar" para actualizarlas.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar conclusiones: ' . $e->getMessage());
        }

        $this->generating = false;
    }

    public function render()
    {
        return view('livewire.admin.unified-timeline')
            ->layout('components.admin-layout', ['title' => 'Timeline Unificado']);
    }
}
