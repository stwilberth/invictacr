<?php

namespace App\Livewire\Admin;

use App\Models\AiCeoRecommendation;
use App\Models\Alert;
use App\Models\ExternalFactor;
use App\Models\FacebookAdReport;
use App\Models\GoogleAdsReport;
use App\Models\GoogleAnalyticsReport;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\SearchConsoleReport;
use App\Models\User;
use App\Models\VisitorEvent;
use App\Models\WaitlistEntry;
use App\Models\WaitlistNotification;
use Livewire\Component;

class Dashboard extends Component
{
    // Gestión interna (Admin)
    public array $userStats = [];
    public array $upcomingProducts = [];
    public int $upcomingCount = 0;
    public array $waitlistResumen = [];
    public int $waitlistPendientes = 0;
    public array $waitlistNotifications = [];
    public int $waitlistUnread = 0;

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
    public array $socialPropertyStats = [];
    public array $adsPerformance = [];
    public array $fbAdsPerformance = [];
    public array $growth = [];
    public array $correlationNotes = [];
    public ?int $realtimeUsers = null;

    public bool $syncing = false;

    // Servidor
    public array $serverStats = [];
    public array $serverPeak = [];
    public array $serverSeries = [];
    public bool $serverMetricsAvailable = false;
    public array $serverPhpFallback = [];

    // Logs de aplicación
    public array $appErrors = [];

    // Inventario y envíos
    public array $inventorySummary = [];
    public array $shippingSummary = [];

    // Lista de espera - conversión
    public array $waitlistConversion = [];

    // Alertas de API
    public array $activeAlerts = [];

    // CEO
    public ?array $topCeoRecommendation = null;

    // Sync health
    public ?int $daysSinceLastGaSync = null;
    public ?int $daysSinceLastAdsSync = null;
    public ?int $daysSinceLastFbSync = null;
    public ?int $daysSinceLastScSync = null;

    // Connection tests
    public ?array $gaConnectionTest = null;
    public ?array $adsConnectionTest = null;
    public ?array $scConnectionTest = null;
    public ?array $fbConnectionTest = null;

    public function mount(): void
    {
        $this->loadAdminData();
        $this->loadWaitlist();
        $this->loadAnalytics();
        $this->loadServerStats();
        $this->loadAppErrors();
        $this->loadInventorySummary();
        $this->loadShippingSummary();
        $this->loadWaitlistConversion();
        $this->loadActiveAlerts();
        $this->loadTopCeoRecommendation();
        $this->loadSyncHealth();
    }

    // ───────────────────────────────────────────────
    // 4. Alertas de API
    // ───────────────────────────────────────────────

    protected function loadActiveAlerts(): void
    {
        $this->activeAlerts = Alert::active()
            ->latest()
            ->get()
            ->map(fn(Alert $a) => [
                'id' => $a->id,
                'type' => $a->type,
                'title' => $a->title,
                'message' => $a->message,
                'level' => $a->level,
                'context' => $a->context,
                'created_at' => $a->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    public function resolverAlerta(int $id): void
    {
        Alert::find($id)?->resolve();
        $this->loadActiveAlerts();
    }

    public function resolverTodasAlertas(): void
    {
        Alert::active()->update(['resolved_at' => now()]);
        $this->loadActiveAlerts();
    }

    // ───────────────────────────────────────────────
    // 3. Conversión de lista de espera
    // ───────────────────────────────────────────────

    protected function loadWaitlistConversion(): void
    {
        $totalEntries = WaitlistEntry::count();
        $notifiedEntries = WaitlistEntry::whereIn('estado', [WaitlistEntry::ESTADO_NOTIFICADO, WaitlistEntry::ESTADO_CONTACTADO])->count();

        $modelos = WaitlistEntry::whereNotNull('modelo')
            ->pluck('modelo')
            ->unique()
            ->values()
            ->toArray();

        $purchasedCount = 0;
        if (!empty($modelos)) {
            $purchasedCount = InvoiceItem::whereIn('product_model', $modelos)
                ->whereHas('invoice', fn($q) => $q->where('status', '!=', 'cancelled'))
                ->distinct('product_model')
                ->count();
        }

        $avgWaitDays = WaitlistEntry::whereNotNull('notified_at')
            ->selectRaw('AVG(DATEDIFF(notified_at, created_at)) as avg_days')
            ->value('avg_days');

        $convertedToInvoice = 0;
        $entryModelos = WaitlistEntry::whereNotNull('modelo')
            ->where('estado', '!=', WaitlistEntry::ESTADO_DESCARTADO)
            ->pluck('modelo')
            ->toArray();

        if (!empty($entryModelos)) {
            $convertedToInvoice = InvoiceItem::whereIn('product_model', $entryModelos)
                ->whereHas('invoice', fn($q) => $q->where('status', '!=', 'cancelled'))
                ->distinct('product_model')
                ->count();
        }

        $this->waitlistConversion = [
            'total_entries' => $totalEntries,
            'notified' => $notifiedEntries,
            'models_with_purchase' => $convertedToInvoice,
            'unique_models_requested' => count($modelos),
            'conversion_rate' => $totalEntries > 0
                ? round(($convertedToInvoice / $totalEntries) * 100, 1)
                : 0,
            'avg_wait_days' => $avgWaitDays !== null ? round((float) $avgWaitDays, 1) : null,
        ];
    }

    // ───────────────────────────────────────────────
    // 2. Inventario y envíos
    // ───────────────────────────────────────────────

    protected function loadInventorySummary(): void
    {
        $products = Product::selectRaw('
                COUNT(*) as total_models,
                SUM(CASE WHEN stock > 0 AND (disponibilidad IS NULL OR disponibilidad != "agotado") THEN 1 ELSE 0 END) as in_stock,
                SUM(CASE WHEN stock <= 0 OR disponibilidad = "agotado" THEN 1 ELSE 0 END) as agotados,
                COALESCE(SUM(precio_costo * stock), 0) as total_cost_value,
                COALESCE(SUM(precio_venta * stock), 0) as total_sale_value
            ')
            ->where('activo', true)
            ->first();

        $costValue = (float) ($products->total_cost_value ?? 0);
        $saleValue = (float) ($products->total_sale_value ?? 0);

        $this->inventorySummary = [
            'total_models' => (int) ($products->total_models ?? 0),
            'in_stock' => (int) ($products->in_stock ?? 0),
            'agotados' => (int) ($products->agotados ?? 0),
            'total_cost_value' => $costValue,
            'total_sale_value' => $saleValue,
            'potential_margin' => $saleValue > 0
                ? round((($saleValue - $costValue) / $saleValue) * 100, 1)
                : 0,
        ];
    }

    protected function loadShippingSummary(): void
    {
        [$start, $end] = $this->getDateRange();

        $invoices = Invoice::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalShipping = $invoices->sum('shipping');
        $totalShippingCost = $invoices->sum('shipping_cost');
        $totalSubtotal = $invoices->sum('subtotal');
        $totalDiscount = $invoices->sum('discount');
        $invoiceCount = $invoices->count();

        $grossMargin = 0;
        $netMargin = 0;
        if ($totalSubtotal > 0) {
            $grossMargin = $totalSubtotal * 0.30;
            $netMargin = $grossMargin - $totalDiscount + $totalShipping - $totalShippingCost;
        }

        $this->shippingSummary = [
            'total_shipping_charged' => $totalShipping,
            'total_shipping_cost' => $totalShippingCost,
            'shipping_margin' => $totalShipping - $totalShippingCost,
            'invoice_count' => $invoiceCount,
            'total_subtotal' => $totalSubtotal,
            'total_discount' => $totalDiscount,
            'estimated_gross_margin' => $grossMargin,
            'estimated_net_margin' => $netMargin,
            'avg_shipping_per_invoice' => $invoiceCount > 0 ? $totalShipping / $invoiceCount : 0,
            'avg_shipping_cost_per_invoice' => $invoiceCount > 0 ? $totalShippingCost / $invoiceCount : 0,
        ];
    }

    // ───────────────────────────────────────────────
    // 1. Servidor y logs
    // ───────────────────────────────────────────────

    protected function loadServerStats(): void
    {
        $service = app(\App\Services\ServerMetricsService::class);
        $this->serverMetricsAvailable = $service->available();

        if ($this->serverMetricsAvailable) {
            $this->serverStats = $service->current();
            $this->serverPeak = $service->peak(604800);
            $this->serverSeries = $service->series(86400, 48);
        } else {
            $this->serverPhpFallback = $service->phpFallback();
        }
    }

    protected function loadAppErrors(): void
    {
        $this->appErrors = app(\App\Services\ApplicationErrorService::class)->getLast24hErrors();
    }

    // ───────────────────────────────────────────────
    // Sync health (ampliada)
    // ───────────────────────────────────────────────

    protected function loadSyncHealth(): void
    {
        $lastGa = GoogleAnalyticsReport::max('report_date');
        $lastAds = GoogleAdsReport::max('report_date');
        $lastFb = FacebookAdReport::max('report_date');
        $lastSc = SearchConsoleReport::max('report_date');

        $this->daysSinceLastGaSync = $lastGa ? (int) round(now()->diffInDays(\Carbon\Carbon::parse($lastGa), true)) : null;
        $this->daysSinceLastAdsSync = $lastAds ? (int) round(now()->diffInDays(\Carbon\Carbon::parse($lastAds), true)) : null;
        $this->daysSinceLastFbSync = $lastFb ? (int) round(now()->diffInDays(\Carbon\Carbon::parse($lastFb), true)) : null;
        $this->daysSinceLastScSync = $lastSc ? (int) round(now()->diffInDays(\Carbon\Carbon::parse($lastSc), true)) : null;
    }

    protected function loadTopCeoRecommendation(): void
    {
        $latestKey = AiCeoRecommendation::max('batch_key');
        if (!$latestKey) {
            return;
        }

        $top = AiCeoRecommendation::where('batch_key', $latestKey)
            ->where('status', 'pendiente')
            ->orderByRaw("FIELD(category, 'urgente', 'oportunidad', 'estrategia')")
            ->orderByRaw("FIELD(priority, 'alta', 'media', 'baja')")
            ->first();

        if ($top) {
            $this->topCeoRecommendation = [
                'category' => $top->category,
                'title' => $top->title,
                'action' => $top->action,
            ];
        }
    }

    public function updatedPeriod(): void
    {
        $this->loadAnalytics();
        $this->loadShippingSummary();
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

    protected function loadWaitlist(): void
    {
        $this->waitlistPendientes = WaitlistEntry::where('estado', WaitlistEntry::ESTADO_PENDIENTE)->count();
        $this->waitlistResumen = WaitlistEntry::latest()
            ->take(5)
            ->get(['id', 'nombre', 'telefono', 'modelo', 'estado', 'created_at'])
            ->map(fn($e) => [
                'nombre' => $e->nombre,
                'telefono' => $e->telefono,
                'modelo' => $e->modelo,
                'estado' => $e->estado,
            ])
            ->toArray();
        $this->waitlistUnread = WaitlistNotification::whereNull('leida_at')->count();
        $this->waitlistNotifications = WaitlistNotification::latest()
            ->take(5)
            ->get(['id', 'titulo', 'mensaje', 'leida_at', 'created_at'])
            ->map(fn($n) => [
                'id' => $n->id,
                'titulo' => $n->titulo,
                'mensaje' => $n->mensaje,
                'leida' => !is_null($n->leida_at),
            ])
            ->toArray();
    }

    public function marcarWaitlistLeida(int $id): void
    {
        WaitlistNotification::where('id', $id)->whereNull('leida_at')->update(['leida_at' => now()]);
        $this->loadWaitlist();
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

        // Social media properties (Search Console)
        $socialProps = config('services.google.search_console_social_properties', []);
        $this->socialPropertyStats = [];
        foreach ($socialProps as $platform => $url) {
            $propReports = $scReports->where('property_url', $url);
            $this->socialPropertyStats[$platform] = [
                'url' => $url,
                'clicks' => $propReports->sum('clicks'),
                'impressions' => $propReports->sum('impressions'),
                'avg_ctr' => $propReports->avg('ctr'),
                'avg_position' => $propReports->avg('position'),
                'top_queries' => $propReports->groupBy('query')
                    ->map(fn($group) => [
                        'clicks' => $group->sum('clicks'),
                        'impressions' => $group->sum('impressions'),
                        'avg_position' => $group->avg('position'),
                    ])
                    ->sortByDesc('clicks')
                    ->take(5)
                    ->toArray(),
            ];
        }

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

        // Campañas Meta Ads (con métricas enriquecidas)
        $fbAds = FacebookAdReport::whereBetween('report_date', [$start, $end])
            ->where('level', 'campaign')
            ->get();
        $fbAdsAll = FacebookAdReport::whereBetween('report_date', [$start, $end])->get();

        $totalConversions = $fbAdsAll->sum('conversions');
        $totalConversionValue = $fbAdsAll->sum('conversion_value');
        $totalSpend = $fbAds->sum('spend');

        $this->fbAdsPerformance = [
            'total_clicks' => $fbAds->sum('clicks'),
            'total_spend' => $totalSpend,
            'total_impressions' => $fbAds->sum('impressions'),
            'total_reach' => $fbAds->sum('reach'),
            'total_conversions' => $totalConversions,
            'total_conversion_value' => $totalConversionValue,
            'roas' => $totalSpend > 0 ? round($totalConversionValue / $totalSpend, 2) : 0,
            'avg_cpa' => $totalConversions > 0 ? round($totalSpend / $totalConversions, 2) : 0,
            'avg_cpc' => $fbAds->avg('cpc'),
            'avg_cpm' => $fbAds->avg('cpm'),
            'avg_ctr' => $fbAds->avg('ctr'),
            'by_campaign' => $fbAds->groupBy('campaign_name')->map(fn($group) => [
                'impressions' => $group->sum('impressions'),
                'clicks' => $group->sum('clicks'),
                'spend' => $group->sum('spend'),
                'reach' => $group->sum('reach'),
                'conversions' => $group->sum('conversions'),
                'conversion_value' => $group->sum('conversion_value'),
                'roas' => $group->sum('spend') > 0 ? round($group->sum('conversion_value') / $group->sum('spend'), 2) : 0,
                'cpa' => $group->sum('conversions') > 0 ? round($group->sum('spend') / $group->sum('conversions'), 2) : 0,
                'objective' => $group->first()['campaign_objective'] ?? null,
                'status' => $group->first()['campaign_status'] ?? null,
                'frequency' => $group->avg('frequency'),
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
            $this->loadWaitlist();
            $this->loadAnalytics();
            $this->loadInventorySummary();
            $this->loadShippingSummary();
            $this->loadWaitlistConversion();
            $this->loadSyncHealth();
            session()->flash('message', "Datos sincronizados para los últimos {$days} días.");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al sincronizar: ' . $e->getMessage());
        }

        $this->syncing = false;
    }

    public function testGaConnection(): void
    {
        $this->gaConnectionTest = app(\App\Services\GoogleAnalyticsService::class)->testConnection();
    }

    public function testAdsConnection(): void
    {
        $this->adsConnectionTest = app(\App\Services\GoogleAdsService::class)->testConnection();
    }

    public function testScConnection(): void
    {
        $this->scConnectionTest = app(\App\Services\GoogleSearchConsoleService::class)->testConnection();
    }

    public function testFbConnection(): void
    {
        $this->fbConnectionTest = app(\App\Services\FacebookAdsService::class)->testConnection();
    }

    public function syncGoogleAds(): void
    {
        $days = match ($this->period) {
            '7d' => 7,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };
        \Illuminate\Support\Facades\Artisan::call('sync:google-ads', ['--days' => $days]);
        $this->loadAnalytics();
        session()->flash('message', 'Google Ads sincronizado.');
    }

    public function syncMetaAds(): void
    {
        $days = match ($this->period) {
            '7d' => 7,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };

        $service = app(\App\Services\FacebookAdsService::class);
        $service->syncDailyAllLevels(now());

        $this->loadAnalytics();
        session()->flash('message', 'Meta Ads sincronizado (campañas + conjuntos + anuncios).');
    }

    public function syncGoogleAnalytics(): void
    {
        $days = match ($this->period) {
            '7d' => 7,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };
        \Illuminate\Support\Facades\Artisan::call('sync:google-analytics', ['--days' => $days]);
        $this->loadAnalytics();
        session()->flash('message', 'Google Analytics sincronizado.');
    }

    public function syncSearchConsole(): void
    {
        $days = match ($this->period) {
            '7d' => 7,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };
        \Illuminate\Support\Facades\Artisan::call('sync:search-console', ['--days' => $days]);
        $this->loadAnalytics();
        session()->flash('message', 'Search Console sincronizado.');
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.admin-layout', ['title' => 'Dashboard']);
    }
}