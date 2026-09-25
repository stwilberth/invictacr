<?php

namespace App\Console\Commands;

use App\Models\Abono;
use App\Models\Alert;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\MarketingTask;
use App\Models\Product;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use App\Models\WaitlistEntry;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StatsToday extends Command
{
    protected $signature = 'stats:today {--date= : Fecha YYYY-MM-DD (default: hoy)} {--json : Salida en JSON}';
    protected $description = 'Resumen rápido del negocio: visitas, ventas, inventario, gastos y marketing';

    public function handle(): int
    {
        $dateOpt = $this->option('date');
        try {
            $day = $dateOpt ? Carbon::parse($dateOpt)->startOfDay() : now()->startOfDay();
        } catch (\Throwable) {
            $this->error('Fecha inválida. Usa YYYY-MM-DD.');
            return Command::FAILURE;
        }
        $dateStr = $day->toDateString();
        $monthStart = $day->copy()->startOfMonth();

        // ── Visitas ──
        // visitantes únicos del día: vistos por middleware (last_seen en el día)
        // O con eventos JS ese día (cubre recurrentes cuyo last_seen ya avanzó).
        $dayEnd = $day->copy()->endOfDay();
        $todayVisitors = Visitor::whereBetween('last_seen_at', [$day, $dayEnd])
            ->orWhereHas('events', fn ($q) => $q->whereDate('created_at', $dateStr))
            ->count();
        $newVisitors = Visitor::whereBetween('first_seen_at', [$day, $dayEnd])->count();
        $returning = max(0, $todayVisitors - $newVisitors);
        $isToday = $dateStr === now()->toDateString();
        $activeNow = $isToday ? Visitor::where('last_seen_at', '>=', now()->subMinutes(5))->count() : 0;
        $eventsByType = VisitorEvent::whereDate('created_at', $dateStr)
            ->selectRaw('type, count(*) c')->groupBy('type')->pluck('c', 'type')->toArray();
        $totalEvents = array_sum($eventsByType);
        $topProducts = VisitorEvent::whereDate('visitor_events.created_at', $dateStr)
            ->where('type', 'product_view')
            ->join('products', 'products.id', '=', 'visitor_events.product_id')
            ->selectRaw('products.modelo, count(*) c')
            ->groupBy('products.modelo')->orderByDesc('c')->limit(5)->pluck('c', 'modelo')->toArray();

        // ── Ventas ──
        $invToday = Invoice::whereDate('created_at', $dateStr)->where('status', '!=', 'cancelled');
        $invCount = (clone $invToday)->count();
        $invTotal = (float) (clone $invToday)->sum('total');
        $invUtility = (float) (clone $invToday)->where('status', 'facturado')->sum('estimated_utility');
        $invByStatus = (clone $invToday)->selectRaw('status, count(*) c, COALESCE(SUM(total),0) t')
            ->groupBy('status')->get()->keyBy('status');
        $abonosToday = (float) Abono::whereDate('date', $dateStr)->sum('amount');
        $abonosCount = Abono::whereDate('date', $dateStr)->count();
        // Mes acumulado
        $invMonth = Invoice::where('created_at', '>=', $monthStart)->where('status', '!=', 'cancelled');
        $monthCount = (clone $invMonth)->count();
        $monthTotal = (float) (clone $invMonth)->sum('total');
        $monthUtility = (float) (clone $invMonth)->where('status', 'facturado')->sum('estimated_utility');

        // ── Inventario ──
        $inv = Product::where('activo', true)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN stock > 0 AND (disponibilidad IS NULL OR disponibilidad != "agotado") THEN 1 ELSE 0 END) as in_stock,
                SUM(CASE WHEN stock <= 0 OR disponibilidad = "agotado" THEN 1 ELSE 0 END) as agotados,
                COALESCE(SUM(precio_costo * stock),0) as costo,
                COALESCE(SUM(precio_venta * stock),0) as venta
            ')->first();
        $lowStock = Product::where('activo', true)->where('stock', '>', 0)->where('stock', '<=', 3)->count();

        // ── Gastos ──
        $expToday = (float) Expense::whereDate('expense_date', $dateStr)->sum('amount');
        $expMonth = (float) Expense::where('expense_date', '>=', $monthStart->toDateString())->sum('amount');

        // ── Marketing / operación ──
        $tasksPending = MarketingTask::where('status', 'pending')->count();
        $waitlistPending = WaitlistEntry::where('estado', WaitlistEntry::ESTADO_PENDIENTE)->count();
        $waToday = (int) ($eventsByType['whatsapp_click'] ?? 0);
        $waMonth = VisitorEvent::where('type', 'whatsapp_click')->where('created_at', '>=', $monthStart)->count();
        $alerts = Alert::active()->count();
        $leadsRetomar = \App\Models\Lead::porRetomar()->count();

        $data = [
            'fecha' => $dateStr,
            'visitas' => [
                'visitantes_unicos' => $todayVisitors,
                'nuevos' => $newVisitors,
                'recurrentes' => $returning,
                'activos_5min' => $activeNow,
                'eventos_total' => $totalEvents,
                'eventos_por_tipo' => $eventsByType,
                'top_productos_vistos' => $topProducts,
            ],
            'ventas' => [
                'facturas_hoy' => $invCount,
                'monto_hoy' => round($invTotal, 2),
                'utilidad_hoy_facturado' => round($invUtility, 2),
                'por_estado' => $invByStatus->map(fn ($r) => ['count' => $r->c, 'total' => round((float) $r->t, 2)])->toArray(),
                'abonos_hoy' => round($abonosToday, 2),
                'abonos_count' => $abonosCount,
                'mes_facturas' => $monthCount,
                'mes_monto' => round($monthTotal, 2),
                'mes_utilidad' => round($monthUtility, 2),
            ],
            'inventario' => [
                'modelos_activos' => (int) ($inv->total ?? 0),
                'en_stock' => (int) ($inv->in_stock ?? 0),
                'agotados' => (int) ($inv->agotados ?? 0),
                'stock_bajo_lte3' => $lowStock,
                'valor_costo' => round((float) ($inv->costo ?? 0), 2),
                'valor_venta' => round((float) ($inv->venta ?? 0), 2),
            ],
            'gastos' => [
                'hoy' => round($expToday, 2),
                'mes' => round($expMonth, 2),
            ],
            'marketing' => [
                'tareas_pendientes' => $tasksPending,
                'waitlist_pendientes' => $waitlistPending,
                'whatsapp_hoy' => $waToday,
                'whatsapp_mes' => $waMonth,
                'alertas_activas' => $alerts,
                'leads_por_retomar' => $leadsRetomar,
            ],
        ];

        if ($this->option('json')) {
            $this->line(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return Command::SUCCESS;
        }

        $fmt = fn ($n) => number_format($n, 0);
        $money = fn ($n) => '₡' . number_format((float) $n, 0);

        $this->info("📊 {$dateStr} — Resumen del negocio");
        $this->line("👁️  Visitas: <fg=cyan>{$fmt($todayVisitors)} únicos</> ({$fmt($newVisitors)} nuevos + {$fmt($returning)} recurrentes) · {$fmt($totalEvents)} eventos · {$activeNow} activos ahora");
        if (!empty($eventsByType)) {
            $parts = [];
            foreach ($eventsByType as $t => $c) {
                $parts[] = "{$t} {$fmt($c)}";
            }
            $this->line('   └ ' . implode(' · ', $parts));
        }
        if (!empty($topProducts)) {
            $parts = [];
            foreach ($topProducts as $m => $c) {
                $parts[] = "{$m} ({$c})";
            }
            $this->line('   └ Top vistos: ' . implode(', ', $parts));
        }
        $this->line("💰 Ventas hoy: <fg=green>{$invCount} facturas · {$money($invTotal)}</> · utilidad facturado {$money($invUtility)} · abonos {$money($abonosToday)} ({$abonosCount})");
        $this->line("   └ Mes: {$monthCount} facturas · {$money($monthTotal)} · utilidad {$money($monthUtility)}");
        $this->line("⌚ Inventario: {$fmt((int) ($inv->total ?? 0))} modelos · {$fmt((int) ($inv->in_stock ?? 0))} en stock · {$fmt((int) ($inv->agotados ?? 0))} agotados · {$lowStock} stock bajo(≤3)");
        $this->line("💸 Gastos: hoy {$money($expToday)} · mes {$money($expMonth)}");
        $this->line("📣 Mkt: {$tasksPending} tareas pend · {$waitlistPending} waitlist pend · WhatsApp hoy {$waToday} / mes {$waMonth} · {$alerts} alertas · {$leadsRetomar} leads por retomar");

        return Command::SUCCESS;
    }
}
