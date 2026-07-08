<?php

namespace App\Livewire\Admin;

use App\Models\ExternalFactor;
use App\Models\GoogleAnalyticsReport;
use App\Models\GoogleAdsReport;
use App\Models\SearchConsoleReport;
use App\Models\FacebookInsight;
use App\Models\FacebookPost;
use App\Models\GitHubCommit;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class AnalyticsDashboard extends Component
{
    public string $period = '30d';
    public array $analyticsSummary = [];
    public array $revenueData = [];
    public array $adsPerformance = [];
    public array $searchConsoleSummary = [];
    public array $facebookSummary = [];
    public array $gitHubSummary = [];
    public array $externalFactors = [];
    public array $correlationNotes = [];
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
            ]),
        ];

        // Revenue from invoices
        $invoices = Invoice::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->get();

        $dailyRevenue = $invoices->groupBy(fn($i) => $i->created_at->format('Y-m-d'))
            ->map(fn($group) => $group->sum('total'));

        $this->revenueData = [
            'total_revenue' => $invoices->sum('total'),
            'total_invoices' => $invoices->count(),
            'daily' => $dailyRevenue,
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
            ]),
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
            'top_queries' => $topQueries,
        ];

        // Facebook
        $fbInsights = FacebookInsight::whereBetween('report_date', [$start, $end])->get();
        $fbPosts = FacebookPost::whereBetween('posted_at', [$start, $end])->get();

        $this->facebookSummary = [
            'total_impressions' => $fbInsights->sum('page_impressions'),
            'total_engagement' => $fbInsights->sum('page_engaged_users'),
            'total_follows' => $fbInsights->sum('page_follows'),
            'total_reactions' => $fbInsights->sum('page_reactions'),
            'total_comments' => $fbInsights->sum('page_comments'),
            'total_shares' => $fbInsights->sum('page_shares'),
            'posts_count' => $fbPosts->count(),
            'recent_posts' => $fbPosts->sortByDesc('posted_at')->take(10)->values(),
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
            'recent_commits' => $commits->sortByDesc('committed_at')->take(10)->values(),
        ];

        // External factors
        $this->externalFactors = ExternalFactor::where('active', true)
            ->whereBetween('event_date', [$start, $end])
            ->orderByDesc('event_date')
            ->get()
            ->toArray();

        // Generate correlation notes
        $this->correlationNotes = $this->generateCorrelationNotes();
    }

    protected function generateCorrelationNotes(): array
    {
        $notes = [];

        // Check for revenue drops near deploys
        $recentCommits = $this->gitHubSummary['recent_commits'] ?? collect();
        $revenue = $this->revenueData['daily'] ?? collect();

        if ($recentCommits->isNotEmpty() && $revenue->isNotEmpty()) {
            foreach ($recentCommits as $commit) {
                $commitDate = $commit['committed_at']->format('Y-m-d');
                $revenueAfter = collect();
                for ($i = 1; $i <= 3; $i++) {
                    $date = $commit['committed_at']->copy()->addDays($i)->format('Y-m-d');
                    if (isset($revenue[$date])) {
                        $revenueAfter[$date] = $revenue[$date];
                    }
                }

                if ($revenueAfter->isNotEmpty() && $revenueAfter->avg() < ($revenue->average() * 0.7)) {
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

        Artisan::call('sync:google-analytics', ['--days' => 1]);
        Artisan::call('sync:google-ads', ['--days' => 30]);
        Artisan::call('sync:search-console', ['--days' => 1]);
        Artisan::call('sync:facebook', ['--days' => 7]);
        Artisan::call('sync:github', ['--branch' => 'main', '--limit' => 50]);

        $this->loadData();
        $this->syncing = false;
    }

    public function render()
    {
        return view('livewire.admin.analytics-dashboard')
            ->layout('components.admin-layout', ['title' => 'Analytics']);
    }
}
