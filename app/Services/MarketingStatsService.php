<?php

namespace App\Services;

use App\Models\FacebookAdReport;
use App\Models\GoogleAdsReport;
use App\Models\GoogleAnalyticsReport;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MarketingStatsService
{
    /**
     * Serie diaria unificada: GA vs sitio vs gasto Meta/Google Ads vs leads vs ventas.
     * Devuelve ['dias' => Collection, 'totales' => array] ordenado de más reciente a más viejo.
     */
    public function daily(int $days = 14, ?Carbon $end = null): array
    {
        $end = ($end ?? now())->startOfDay();
        $start = $end->copy()->subDays(max(1, $days) - 1);
        $from = $start->toDateString();
        $to = $end->toDateString();

        $ga = GoogleAnalyticsReport::whereBetween('report_date', [$from, $to])
            ->get(['report_date', 'users', 'sessions'])
            ->keyBy(fn ($r) => Carbon::parse($r->report_date)->toDateString());

        $fb = FacebookAdReport::whereBetween('report_date', [$from, $to])
            ->selectRaw('report_date, SUM(spend) gasto')
            ->groupBy('report_date')
            ->pluck('gasto', 'report_date');

        $gads = GoogleAdsReport::whereBetween('report_date', [$from, $to])
            ->selectRaw('report_date, SUM(cost) costo')
            ->groupBy('report_date')
            ->pluck('costo', 'report_date');

        $wa = VisitorEvent::where('type', 'whatsapp_click')
            ->whereBetween('created_at', [$start, $end->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) d, COUNT(*) c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $leadsByDay = Lead::whereBetween('created_at', [$start, $end->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) d, COUNT(*) c, SUM(fb_ad_id IS NOT NULL) con_ad')
            ->groupBy('d')
            ->get()
            ->keyBy('d');

        $ventas = Invoice::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) d, COUNT(*) c, COALESCE(SUM(total),0) total')
            ->groupBy('d')
            ->get()
            ->keyBy('d');

        $rows = collect();
        $tot = ['ga_users' => 0, 'ga_sessions' => 0, 'site' => 0, 'wa' => 0, 'fb' => 0.0, 'gads' => 0.0, 'leads' => 0, 'leads_fb' => 0, 'inv' => 0, 'total' => 0.0];

        for ($d = $end->copy(); $d->gte($start); $d->subDay()) {
            $ds = $d->toDateString();

            // Visitantes únicos del día (misma lógica que stats:today)
            $site = Visitor::whereBetween('last_seen_at', [$d->copy(), $d->copy()->endOfDay()])
                ->orWhereHas('events', fn ($q) => $q->whereDate('created_at', $ds))
                ->count();

            $gaUsers = (int) ($ga[$ds]->users ?? 0);
            $gaSessions = (int) ($ga[$ds]->sessions ?? 0);
            $fbSpend = (float) ($fb[$ds] ?? 0);
            $gadsCost = (float) ($gads[$ds] ?? 0);
            $waClicks = (int) ($wa[$ds] ?? 0);
            $leadRow = $leadsByDay[$ds] ?? null;
            $leads = (int) ($leadRow->c ?? 0);
            $leadsFb = (int) ($leadRow->con_ad ?? 0);
            $invRow = $ventas[$ds] ?? null;
            $inv = (int) ($invRow->c ?? 0);
            $total = (float) ($invRow->total ?? 0);

            $rows->push([
                'fecha' => $ds,
                'ga_users' => $gaUsers,
                'ga_sessions' => $gaSessions,
                'site' => $site,
                'wa' => $waClicks,
                'fb' => $fbSpend,
                'gads' => $gadsCost,
                'leads' => $leads,
                'leads_fb' => $leadsFb,
                'inv' => $inv,
                'total' => $total,
            ]);

            $tot['ga_users'] += $gaUsers;
            $tot['ga_sessions'] += $gaSessions;
            $tot['site'] += $site;
            $tot['wa'] += $waClicks;
            $tot['fb'] += $fbSpend;
            $tot['gads'] += $gadsCost;
            $tot['leads'] += $leads;
            $tot['leads_fb'] += $leadsFb;
            $tot['inv'] += $inv;
            $tot['total'] += $total;
        }

        return ['dias' => $rows, 'totales' => $tot];
    }
}
