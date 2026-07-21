<?php

namespace App\Livewire\Admin;

use App\Models\ExternalFactor;
use App\Models\GoogleAnalyticsReport;
use App\Models\GoogleAdsReport;
use App\Models\SearchConsoleReport;
use App\Models\FacebookInsight;
use App\Models\FacebookPost;
use App\Models\FacebookAdReport;
use App\Models\GitHubCommit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\Process\Process;

class AnalyticsDashboard extends Component
{
    public string $period = '30d';
    public array $analyticsSummary = [];
    public array $topPages = [];
    public array $trafficSources = [];
    public array $deviceBreakdown = [];
    public ?int $realtimeUsers = null;
    public array $revenueData = [];
    public array $adsPerformance = [];
    public array $searchConsoleSummary = [];
    public array $searchConsoleByDevice = [];
    public array $searchConsoleByCountry = [];
    public array $facebookSummary = [];
    public array $fbAdsPerformance = [];
    public array $gitHubSummary = [];
    public array $topProducts = [];
    public array $externalFactors = [];
    public array $correlationNotes = [];
    public array $growth = [];
    public bool $syncing = false;

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

    public function loadData(): void
    {
        [$start, $end] = $this->getDateRange();

        // Google Analytics
        $gaReports = GoogleAnalyticsReport::whereBetween('report_date', [$start, $end])->get();
        $this->analyticsSummary = [
            'total_users' => $gaReports->sum('users'),
            'total_sessions' => $gaReports->sum('sessions'),
            'total_pageviews' => $gaReports->sum('pageviews'),
            'avg_bounce_rate' => $gaReports->avg('bounce_rate'),
            'avg_session_duration' => $gaReports->avg('avg_session_duration'),
            'total_new_users' => $gaReports->sum('new_users'),
            'daily' => $gaReports->map(fn($r) => [
                'date' => $r->report_date->format('Y-m-d'),
                'users' => $r->users,
                'sessions' => $r->sessions,
                'pageviews' => $r->pageviews,
            ])->values()->toArray(),
        ];

        // Top pages from GA
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

        // Traffic sources from GA
        $this->trafficSources = $gaReports
            ->filter(fn($r) => !empty($r->traffic_sources))
            ->flatMap(fn($r) => $r->traffic_sources)
            ->groupBy(fn($item) => $item['source'] . ' / ' . $item['medium'])
            ->map(fn($group) => [
                'source' => $group->first()['source'] ?? '',
                'medium' => $group->first()['medium'] ?? '',
                'users' => $group->sum('users'),
                'sessions' => $group->sum('sessions'),
            ])
            ->sortByDesc('users')
            ->take(8)
            ->values()
            ->toArray();

        // Device breakdown from GA
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

        // Revenue from invoices
        $invoices = Invoice::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->get();

        $dailyRevenue = $invoices->groupBy(fn($i) => $i->created_at->format('Y-m-d'))
            ->map(fn($group) => $group->sum('total'));

        $weeklyRevenue = $invoices->groupBy(fn($i) => $i->created_at->format('o-W'))
            ->map(fn($group) => [
                'week' => $group->first()->created_at->format('o-W'),
                'label' => 'Sem ' . $group->first()->created_at->format('W'),
                'total' => $group->sum('total'),
            ])
            ->sortBy('week')
            ->values();

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
            'daily' => $dailyRevenue->toArray(),
            'weekly' => $weeklyRevenue->toArray(),
        ];

        // Google Ads
        $adsReports = GoogleAdsReport::whereBetween('report_date', [$start, $end])->get();
        $this->adsPerformance = [
            'total_impressions' => $adsReports->sum('impressions'),
            'total_clicks' => $adsReports->sum('clicks'),
            'total_cost' => $adsReports->sum('cost'),
            'total_conversions' => $adsReports->sum('conversions'),
            'total_conversion_value' => $adsReports->sum('conversion_value'),
            'avg_ctr' => $adsReports->avg('ctr'),
            'avg_cpc' => $adsReports->avg('average_cpc'),
            'by_campaign' => $adsReports->groupBy('campaign_name')->map(fn($group) => [
                'impressions' => $group->sum('impressions'),
                'clicks' => $group->sum('clicks'),
                'cost' => $group->sum('cost'),
                'conversions' => $group->sum('conversions'),
            ])->toArray(),
        ];

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

        // Search Console by device
        $this->searchConsoleByDevice = $scReports
            ->groupBy('device')
            ->map(fn($group) => [
                'clicks' => $group->sum('clicks'),
                'impressions' => $group->sum('impressions'),
                'avg_position' => $group->avg('position'),
            ])
            ->toArray();

        // Search Console by country
        $this->searchConsoleByCountry = $scReports
            ->groupBy('country')
            ->map(fn($group) => [
                'clicks' => $group->sum('clicks'),
                'impressions' => $group->sum('impressions'),
            ])
            ->sortByDesc('clicks')
            ->take(10)
            ->toArray();

        // Facebook
        $fbInsights = FacebookInsight::whereBetween('report_date', [$start, $end])->get();
        $fbPosts = FacebookPost::whereBetween('posted_at', [$start, $end])->get();

        $this->facebookSummary = [
            'total_impressions' => $fbInsights->sum('page_impressions'),
            'total_engagement' => $fbInsights->sum('page_engaged_users'),
            'total_follows' => $fbInsights->sum('page_follows'),
            'total_reactions' => $fbPosts->sum('likes'),
            'total_comments' => $fbPosts->sum('comments'),
            'total_shares' => $fbPosts->sum('shares'),
            'total_views' => $fbInsights->sum('page_views'),
            'posts_count' => $fbPosts->count(),
            'recent_posts' => $fbPosts->sortByDesc('posted_at')->take(10)->values()->toArray(),
        ];

        // Facebook Ads
        $fbAds = FacebookAdReport::whereBetween('report_date', [$start, $end])->get();
        $this->fbAdsPerformance = [
            'total_impressions' => $fbAds->sum('impressions'),
            'total_clicks' => $fbAds->sum('clicks'),
            'total_spend' => $fbAds->sum('spend'),
            'total_reach' => $fbAds->sum('reach'),
            'avg_cpm' => $fbAds->avg('cpm'),
            'avg_cpc' => $fbAds->avg('cpc'),
            'avg_ctr' => $fbAds->avg('ctr'),
            'by_campaign' => $fbAds->groupBy('campaign_name')->map(fn($group) => [
                'impressions' => $group->sum('impressions'),
                'clicks' => $group->sum('clicks'),
                'spend' => $group->sum('spend'),
                'reach' => $group->sum('reach'),
            ])->toArray(),
        ];

        // GitHub
        $commits = GitHubCommit::whereBetween('committed_at', [$start, $end])->get();
        $this->gitHubSummary = [
            'total_commits' => $commits->count(),
            'total_additions' => $commits->sum('additions'),
            'total_deletions' => $commits->sum('deletions'),
            'total_files_changed' => $commits->sum('files_changed'),
            'deploy_count' => $commits->filter(fn($c) =>
                str_contains(strtolower($c->message), 'deploy') ||
                str_contains(strtolower($c->message), 'release')
            )->count(),
            'recent_commits' => $commits->sortByDesc('committed_at')->take(10)->values()->toArray(),
        ];

        // Top products
        $this->topProducts = InvoiceItem::whereHas('invoice', fn($q) => $q
            ->whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
        )
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get()
            ->toArray();

        // External factors
        $this->externalFactors = ExternalFactor::where('active', true)
            ->whereBetween('event_date', [$start, $end])
            ->orderByDesc('event_date')
            ->get()
            ->toArray();

        // Previous period comparison
        $this->growth = $this->calculateGrowth($start, $end);

        // Realtime users
        try {
            $gaService = app(\App\Services\GoogleAnalyticsService::class);
            $this->realtimeUsers = $gaService->fetchRealtimeUsers();
        } catch (\Exception $e) {
            $this->realtimeUsers = null;
        }

        // Generate correlation notes
        $this->correlationNotes = $this->generateCorrelationNotes();
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

        $currentInvoices = Invoice::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled');
        $prevInvoices = Invoice::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('status', '!=', 'cancelled');

        $currentRevenue = (clone $currentInvoices)->sum('total');
        $prevRevenue = (clone $prevInvoices)->sum('total');
        $currentCount = (clone $currentInvoices)->count();
        $prevCount = (clone $prevInvoices)->count();

        $currentGa = GoogleAnalyticsReport::whereBetween('report_date', [$start, $end])->sum('users');
        $prevGa = GoogleAnalyticsReport::whereBetween('report_date', [$prevStart, $prevEnd])->sum('users');

        $currentAds = GoogleAdsReport::whereBetween('report_date', [$start, $end])->sum('clicks');
        $prevAds = GoogleAdsReport::whereBetween('report_date', [$prevStart, $prevEnd])->sum('clicks');

        $currentSc = SearchConsoleReport::whereBetween('report_date', [$start, $end])->sum('clicks');
        $prevSc = SearchConsoleReport::whereBetween('report_date', [$prevStart, $prevEnd])->sum('clicks');

        return [
            'revenue' => $prevRevenue > 0 ? round((($currentRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0,
            'invoices' => $prevCount > 0 ? round((($currentCount - $prevCount) / $prevCount) * 100, 1) : 0,
            'ga_users' => $prevGa > 0 ? round((($currentGa - $prevGa) / $prevGa) * 100, 1) : 0,
            'ads_clicks' => $prevAds > 0 ? round((($currentAds - $prevAds) / $prevAds) * 100, 1) : 0,
            'sc_clicks' => $prevSc > 0 ? round((($currentSc - $prevSc) / $prevSc) * 100, 1) : 0,
        ];
    }

    protected function generateCorrelationNotes(): array
    {
        $notes = [];

        // Check for revenue drops near deploys
        $recentCommits = $this->gitHubSummary['recent_commits'] ?? [];
        $revenue = $this->revenueData['daily'] ?? [];

        if (count($recentCommits) > 0 && count($revenue) > 0) {
            $avgRevenue = count($revenue) > 0 ? array_sum($revenue) / count($revenue) : 0;
            foreach ($recentCommits as $commit) {
                $commitDate = \Carbon\Carbon::parse($commit['committed_at'])->format('Y-m-d');
                $revenueAfter = [];
                for ($i = 1; $i <= 3; $i++) {
                    $date = \Carbon\Carbon::parse($commit['committed_at'])->addDays($i)->format('Y-m-d');
                    if (isset($revenue[$date])) {
                        $revenueAfter[$date] = $revenue[$date];
                    }
                }

                $avgAfter = count($revenueAfter) > 0 ? array_sum($revenueAfter) / count($revenueAfter) : 0;
                if (count($revenueAfter) > 0 && $avgAfter < ($avgRevenue * 0.7)) {
                    $notes[] = [
                        'type' => 'warning',
                        'title' => 'Posible impacto por deploy',
                        'description' => "El commit \"{$commit['message']}\" del {$commitDate} fue seguido de una baja en ventas. Revisar cambios.",
                    ];
                }
            }
        }

        // Check for ad spending drops
        $ads = $this->adsPerformance;
        if (isset($ads['total_cost']) && $ads['total_cost'] < 1000 && $ads['total_clicks'] > 0) {
            $notes[] = [
                'type' => 'info',
                'title' => 'Inversión en anuncios baja',
                'description' => 'El gasto en Google Ads es bajo. Considerar aumentar presupuesto si las ventas están cayendo.',
            ];
        }

        // Check external factors
        foreach ($this->externalFactors as $factor) {
            if ($factor['impact_level'] === 'high') {
                $notes[] = [
                    'type' => 'external',
                    'title' => "Factor externo: {$factor['category']}",
                    'description' => "{$factor['title']}: {$factor['description']}",
                ];
            }
        }

        return $notes;
    }

    public function syncAll(): void
    {
        $this->syncing = true;

        $days = match ($this->period) {
            '7d' => 7,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };

        $this->dispatch('sync-started');

        try {
            \Illuminate\Support\Facades\Artisan::call('sync:google-analytics', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:google-ads', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:search-console', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:facebook', ['--days' => $days, '--posts' => 20]);
            \Illuminate\Support\Facades\Artisan::call('sync:facebook-ads', ['--days' => $days]);
            \Illuminate\Support\Facades\Artisan::call('sync:github');

            $this->loadData();
            session()->flash('message', "Datos sincronizados para los últimos {$days} días.");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al sincronizar: ' . $e->getMessage());
        }

        $this->syncing = false;
    }

    public function render()
    {
        return view('livewire.admin.analytics-dashboard')
            ->layout('components.admin-layout', ['title' => 'Analytics']);
    }
}
