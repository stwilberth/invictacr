<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Subscriber;
use App\Models\SyncLog;
use App\Models\GoogleAnalyticsReport;
use App\Models\FacebookPost;
use App\Models\FacebookInsight;
use App\Models\VisitorEvent;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];
    public array $recentSyncs = [];
    public array $recentInvoices = [];
    public array $topCollections = [];
    public array $recentSubscribers = [];
    public array $recentFbPosts = [];
    public array $gaSummary = [];
    public array $trafficSources = [];
    public bool $syncing = false;

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $monthInvoices = Invoice::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalRevenue = $monthInvoices->sum('total');
        $totalInvoices = $monthInvoices->count();

        $this->stats = [
            'products' => Product::where('activo', true)->count(),
            'monthly_revenue' => $totalRevenue,
            'monthly_invoices' => $totalInvoices,
            'avg_order_value' => $totalInvoices > 0 ? $totalRevenue / $totalInvoices : 0,
            'visitors_today' => $this->getTodayVisitors(),
            'whatsapp_clicks' => VisitorEvent::where('type', 'whatsapp_click')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
            'monthly_subscribers' => Subscriber::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'low_stock' => Product::where('stock', '<', 5)->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'upcoming' => Product::where('proximo', true)->count(),
        ];

        $this->recentSyncs = SyncLog::latest()->take(5)->get()->toArray();

        $this->recentInvoices = Invoice::where('status', '!=', 'cancelled')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($inv) => [
                'id' => $inv->id,
                'client' => $inv->client_name ?? $inv->client->name ?? 'N/A',
                'total' => $inv->total,
                'status' => $inv->status,
                'created_at' => $inv->created_at->format('d/m/Y'),
            ])
            ->toArray();

        $this->topCollections = InvoiceItem::whereHas('invoice', fn($q) => $q
            ->where('status', '!=', 'cancelled')
        )
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->select('products.coleccion as name', \Illuminate\Support\Facades\DB::raw('SUM(invoice_items.quantity) as total_qty'), \Illuminate\Support\Facades\DB::raw('SUM(invoice_items.subtotal) as total_revenue'))
            ->whereNotNull('products.coleccion')
            ->where('products.coleccion', '!=', '')
            ->groupBy('products.coleccion')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get()
            ->toArray();

        $this->recentSubscribers = Subscriber::latest()->take(5)->get()
            ->map(fn($s) => [
                'email' => $s->email,
                'created_at' => $s->created_at ? $s->created_at->format('d/m/Y') : 'N/A',
            ])
            ->toArray();

        $this->recentFbPosts = FacebookPost::latest('posted_at')->take(5)->get()
            ->map(fn($p) => [
                'message' => \Illuminate\Support\Str::limit($p->message ?? 'Sin texto', 80),
                'likes' => $p->likes ?? 0,
                'comments' => $p->comments ?? 0,
                'shares' => $p->shares ?? 0,
                'posted_at' => $p->posted_at ? $p->posted_at->format('d/m/Y') : 'N/A',
            ])
            ->toArray();

        $gaReports = GoogleAnalyticsReport::latest('report_date')->take(30)->get();
        $this->gaSummary = [
            'total_users' => $gaReports->sum('users'),
            'total_sessions' => $gaReports->sum('sessions'),
            'total_pageviews' => $gaReports->sum('pageviews'),
            'avg_bounce_rate' => $gaReports->avg('bounce_rate'),
        ];

        $this->trafficSources = $gaReports
            ->filter(fn($r) => !empty($r->traffic_sources))
            ->flatMap(fn($r) => $r->traffic_sources)
            ->groupBy(fn($item) => ($item['source'] ?? '') . ' / ' . ($item['medium'] ?? ''))
            ->map(fn($group) => [
                'source' => $group->first()['source'] ?? '(direct)',
                'users' => $group->sum('users'),
            ])
            ->sortByDesc('users')
            ->take(6)
            ->values()
            ->toArray();
    }

    protected function getTodayVisitors(): int
    {
        return VisitorEvent::where('type', 'page_view')
            ->whereDate('created_at', now()->toDateString())
            ->distinct('visitor_id')
            ->count('visitor_id');
    }

    public function syncData(): void
    {
        $this->syncing = true;

        try {
            \Illuminate\Support\Facades\Artisan::call('sync:google-analytics', ['--days' => 7]);
            \Illuminate\Support\Facades\Artisan::call('sync:google-ads', ['--days' => 7]);
            \Illuminate\Support\Facades\Artisan::call('sync:search-console', ['--days' => 7]);
            \Illuminate\Support\Facades\Artisan::call('sync:facebook', ['--days' => 7, '--posts' => 10]);
            \Illuminate\Support\Facades\Artisan::call('sync:facebook-ads', ['--days' => 7]);
            \Illuminate\Support\Facades\Artisan::call('sync:github');

            $this->loadData();
            session()->flash('message', 'Datos actualizados correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al sincronizar: ' . $e->getMessage());
        }

        $this->syncing = false;
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.admin-layout');
    }
}
