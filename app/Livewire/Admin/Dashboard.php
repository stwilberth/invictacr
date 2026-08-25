<?php

namespace App\Livewire\Admin;

use App\Models\ExternalFactor;
use App\Models\FacebookAdReport;
use App\Models\GoogleAdsReport;
use App\Models\GoogleAnalyticsReport;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SearchConsoleReport;
use App\Models\User;
use App\Models\VisitorEvent;
use Livewire\Component;

class Dashboard extends Component
{
    // Gestión interna (Admin)
    public array $userStats = [];
    public array $upcomingProducts = [];
    public int $upcomingCount = 0;

    // Métricas de negocio (Analytics)
    public string $period = '30d';
    public array $revenueData = [];
    public array $analyticsSummary = [];
    public array $trafficSources = [];
    public array $topPages = [];
    public array $deviceBreakdown = [];
    public array $searchConsoleSummary = [];
    public array $searchConsoleByDevice = [];
    public array $searchConsoleByCountry = [];
    public array $adsPerformance = [];
    public array $fbAdsPerformance = [];
    public array $growth = [];
    public array $correlationNotes = [];
    public ?int $realtimeUsers = null;

    public bool $syncing = false;

    public array $serverStats = [];
    public array $serverPeak = [];
    public array $serverSeries = [];
    public bool $serverMetricsAvailable = false;

    public string $codeServerStatus = 'unknown';
    public string $opencodeWebStatus = 'unknown';
    public string $devToolsMessage = '';
    public string $devToolsError = '';

    public function mount(): void
    {
        $this->loadAdminData();
        $this->loadAnalytics();
        $this->loadDevToolsStatus();
        $this->loadServerStats();
    }

    protected function loadServerStats(): void
    {
        $service = app(\App\Services\ServerMetricsService::class);
        $this->serverMetricsAvailable = $service->available();
        if (!$this->serverMetricsAvailable) {
            return;
        }

        $this->serverStats = $service->current();
        $this->serverPeak = $service->peak(604800);
        $this->serverSeries = $service->series(86400, 48);
    }

    public function updatedPeriod(): void
    {
        $this->loadAnalytics();
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

    protected function loadAdminData(): void
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $admins = User::where('is_admin', true)->count();
        $totalUsers = User::count();

        $this->userStats = [
            'total' => $totalUsers,
            'admins' => $admins,
            'clients' => $totalUsers - $admins,
            'new_this_month' => User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'visitors_today' => VisitorEvent::where('type', 'page_view')
                ->whereDate('created_at', $now->toDateString())
                ->distinct('visitor_id')
                ->count('visitor_id'),
            'whatsapp_clicks' => VisitorEvent::where('type', 'whatsapp_click')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
        ];

        $this->upcomingCount = Product::where('proximo', true)->count();
        $this->upcomingProducts = Product::where('proximo', true)
            ->orderByDesc('updated_at')
            ->take(4)
            ->get(['modelo', 'title', 'imagen'])
            ->map(fn($p) => [
                'name' => $p->title ?: $p->modelo,
                'image' => $p->imagen,
            ])
            ->toArray();
    }

    protected function loadAnalytics(): void
    {
        [$start, $end] = $this->getDateRange();

        // Tráfico web (Google Analytics)
        $gaReports = GoogleAnalyticsReport::whereBetween('report_date', [$start, $end])->get();
        $this->analyticsSummary = [
            'total_users' => $gaReports->sum('users'),
            'total_sessions' => $gaReports->sum('sessions'),
            'total_pageviews' => $gaReports->sum('pageviews'),
            'avg_bounce_rate' => $gaReports->avg('bounce_rate'),
            'avg_session_duration' => $gaReports->avg('avg_session_duration'),
            'total_new_users' => $gaReports->sum('new_users'),
        ];

        $this->trafficSources = $gaReports
            ->filter(fn($r) => !empty($r->traffic_sources))
            ->flatMap(fn($r) => $r->traffic_sources)
            ->groupBy(fn($item) => ($item['source'] ?? '') . ' / ' . ($item['medium'] ?? ''))
            ->map(fn($group) => [
                'source' => $group->first()['source'] ?? '',
                'users' => $group->sum('users'),
            ])
            ->sortByDesc('users')
            ->take(8)
            ->values()
            ->toArray();

        // Páginas más vistas (GA)
        $this->topPages = $gaReports
            ->filter(fn($r) => !empty($r->top_pages))
            ->flatMap(fn($r) => $r->top_pages)
            ->groupBy('path')
            ->map(fn($group) => [
                'path' => $group->first()['path'] ?? '',
                'views' => $group->sum('views'),
            ])
            ->sortByDesc('views')
            ->take(10)
            ->values()
            ->toArray();

        // Dispositivos (GA)
        $this->deviceBreakdown = $gaReports
            ->filter(fn($r) => !empty($r->device_breakdown))
            ->flatMap(fn($r) => $r->device_breakdown)
            ->groupBy('category')
            ->map(fn($group) => [
                'category' => $group->first()['category'] ?? '',
                'users' => $group->sum('users'),
                'sessions' => $group->sum('sessions'),
            ])
            ->values()
            ->toArray();

        // Search Console
        $scReports = SearchConsoleReport::whereBetween('report_date', [$start, $end])->get();
        $topQueries = $scReports->groupBy('query')->map(fn($group) => [
            'clicks' => $group->sum('clicks'),
            'impressions' => $group->sum('impressions'),
            'avg_position' => $group->avg('position'),
        ])->sortByDesc('clicks')->take(20);

        $this->searchConsoleSummary = [
            'total_clicks' => $scReports->sum('clicks'),
            'total_impressions' => $scReports->sum('impressions'),
            'avg_ctr' => $scReports->avg('ctr'),
            'avg_position' => $scReports->avg('position'),
            'top_queries' => $topQueries->toArray(),
        ];

        $this->searchConsoleByDevice = $scReports
            ->groupBy('device')
            ->map(fn($group) => [
                'clicks' => $group->sum('clicks'),
                'impressions' => $group->sum('impressions'),
                'avg_position' => $group->avg('position'),
            ])
            ->toArray();

        $this->searchConsoleByCountry = $scReports
            ->groupBy('country')
            ->map(fn($group) => [
                'clicks' => $group->sum('clicks'),
                'impressions' => $group->sum('impressions'),
            ])
            ->sortByDesc('clicks')
            ->take(10)
            ->toArray();

        // Ingresos y utilidad
        $invoices = Invoice::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalRevenue = $invoices->sum('total');
        $totalInvoices = $invoices->count();
        $totalUtility = $invoices->sum(function ($invoice) {
            $shippingCost = $invoice->shipping_cost ?? 0;
            return ($invoice->subtotal * 0.30) - $invoice->discount + $invoice->shipping - $shippingCost;
        });

        $this->revenueData = [
            'total_revenue' => $totalRevenue,
            'total_invoices' => $totalInvoices,
            'total_utility' => $totalUtility,
            'avg_order_value' => $totalInvoices > 0 ? $totalRevenue / $totalInvoices : 0,
        ];

        // Campañas Google Ads
        $adsReports = GoogleAdsReport::whereBetween('report_date', [$start, $end])->get();
        $this->adsPerformance = [
            'total_clicks' => $adsReports->sum('clicks'),
            'total_cost' => $adsReports->sum('cost'),
            'by_campaign' => $adsReports->groupBy('campaign_name')->map(fn($group) => [
                'impressions' => $group->sum('impressions'),
                'clicks' => $group->sum('clicks'),
                'cost' => $group->sum('cost'),
                'conversions' => $group->sum('conversions'),
            ])->toArray(),
        ];

        // Campañas Meta Ads
        $fbAds = FacebookAdReport::whereBetween('report_date', [$start, $end])->get();
        $this->fbAdsPerformance = [
            'total_clicks' => $fbAds->sum('clicks'),
            'total_spend' => $fbAds->sum('spend'),
            'by_campaign' => $fbAds->groupBy('campaign_name')->map(fn($group) => [
                'impressions' => $group->sum('impressions'),
                'clicks' => $group->sum('clicks'),
                'spend' => $group->sum('spend'),
                'reach' => $group->sum('reach'),
            ])->toArray(),
        ];

        // Crecimiento vs período anterior
        $this->growth = $this->calculateGrowth($start, $end);

        // Usuarios en tiempo real
        try {
            $gaService = app(\App\Services\GoogleAnalyticsService::class);
            $this->realtimeUsers = $gaService->fetchRealtimeUsers();
        } catch (\Exception $e) {
            $this->realtimeUsers = null;
        }

        $this->correlationNotes = $this->generateCorrelationNotes($start, $end);
    }

    protected function calculateGrowth($start, $end): array
    {
        $periodDays = match ($this->period) {
            '7d' => 7,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };

        $prevStart = (clone $start)->subDays($periodDays);
        $prevEnd = (clone $start)->subDay();

        $currentRevenue = Invoice::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')->sum('total');
        $prevRevenue = Invoice::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('status', '!=', 'cancelled')->sum('total');

        $currentGa = GoogleAnalyticsReport::whereBetween('report_date', [$start, $end])->sum('users');
        $prevGa = GoogleAnalyticsReport::whereBetween('report_date', [$prevStart, $prevEnd])->sum('users');

        $currentAds = GoogleAdsReport::whereBetween('report_date', [$start, $end])->sum('clicks');
        $prevAds = GoogleAdsReport::whereBetween('report_date', [$prevStart, $prevEnd])->sum('clicks');

        return [
            'revenue' => $prevRevenue > 0 ? round((($currentRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0,
            'ga_users' => $prevGa > 0 ? round((($currentGa - $prevGa) / $prevGa) * 100, 1) : 0,
            'ads_clicks' => $prevAds > 0 ? round((($currentAds - $prevAds) / $prevAds) * 100, 1) : 0,
        ];
    }

    protected function generateCorrelationNotes($start, $end): array
    {
        $notes = [];

        $ads = $this->adsPerformance;
        if (isset($ads['total_cost']) && $ads['total_cost'] < 1000 && $ads['total_clicks'] > 0) {
            $notes[] = [
                'type' => 'info',
                'title' => 'Inversión en anuncios baja',
                'description' => 'El gasto en Google Ads es bajo. Considerar aumentar presupuesto si las ventas están cayendo.',
            ];
        }

        $highImpactFactors = ExternalFactor::where('active', true)
            ->where('impact_level', 'high')
            ->whereBetween('event_date', [$start, $end])
            ->orderByDesc('event_date')
            ->get();

        foreach ($highImpactFactors as $factor) {
            $notes[] = [
                'type' => 'external',
                'title' => "Factor externo: {$factor->category}",
                'description' => "{$factor->title}: {$factor->description}",
            ];
        }

        return $notes;
    }

    public function syncData(): void
    {
        $this->syncing = true;

        $days = match ($this->period) {
            '7d' => 7,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };

        try {
            \Illuminate\Support\Facades\Artisan::call('sync:google-analytics', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:google-ads', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:search-console', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:facebook', ['--days' => $days, '--posts' => 20]);
            \Illuminate\Support\Facades\Artisan::call('sync:facebook-ads', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:github');

            $this->loadAdminData();
            $this->loadAnalytics();
            session()->flash('message', "Datos sincronizados para los últimos {$days} días.");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al sincronizar: ' . $e->getMessage());
        }

        $this->syncing = false;
    }

    protected function runSystemctl(string $action, string $unit): ?string
    {
        $allowed = ['start', 'stop', 'restart', 'is-active', 'status'];
        $allowedUnits = ['code-server@bitnami', 'opencode-web'];
        if (!in_array($action, $allowed, true) || !in_array($unit, $allowedUnits, true)) {
            return null;
        }

        $cmd = sprintf('sudo -n /usr/bin/systemctl %s %s 2>&1', escapeshellarg($action), escapeshellarg($unit));
        $out = shell_exec($cmd);
        return $out !== null ? trim($out) : '';
    }

    public function loadDevToolsStatus(): void
    {
        $this->codeServerStatus = $this->runSystemctl('is-active', 'code-server@bitnami') ?? 'unknown';
        $this->opencodeWebStatus = $this->runSystemctl('is-active', 'opencode-web') ?? 'unknown';
    }

    public function toggleDevTool(string $unit): void
    {
        $allowedUnits = ['code-server@bitnami', 'opencode-web'];
        if (!in_array($unit, $allowedUnits, true)) {
            return;
        }

        $current = $this->runSystemctl('is-active', $unit);
        $action = $current === 'active' ? 'stop' : 'start';
        $out = $this->runSystemctl($action, $unit);
        sleep(2);
        $newStatus = $this->runSystemctl('is-active', $unit);

        $label = $unit === 'code-server@bitnami' ? 'code-server' : 'opencode web';
        $expected = $action === 'stop' ? 'inactive' : 'active';

        if ($newStatus === $expected) {
            $this->devToolsMessage = sprintf('%s %s correctamente (estado: %s).', $label, $action === 'stop' ? 'detenido' : 'iniciado', $newStatus);
        } else {
            $this->devToolsError = sprintf('No se pudo %s %s. Salida: %s', $action, $label, $out);
        }

        $this->loadDevToolsStatus();
    }

    public function restartDevTool(string $unit): void
    {
        $allowedUnits = ['code-server@bitnami', 'opencode-web'];
        if (!in_array($unit, $allowedUnits, true)) {
            return;
        }

        $out = $this->runSystemctl('restart', $unit);
        sleep(2);
        $newStatus = $this->runSystemctl('is-active', $unit);

        $label = $unit === 'code-server@bitnami' ? 'code-server' : 'opencode web';

        if ($newStatus === 'active') {
            $this->devToolsMessage = sprintf('%s reiniciado correctamente (estado: %s).', $label, $newStatus);
        } else {
            $this->devToolsError = sprintf('No se pudo reiniciar %s. Salida: %s', $label, $out);
        }

        $this->loadDevToolsStatus();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.admin-layout', ['title' => 'Dashboard']);
    }
}
