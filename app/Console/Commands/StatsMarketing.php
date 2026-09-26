<?php

namespace App\Console\Commands;

use App\Services\MarketingStatsService;
use Illuminate\Console\Command;

class StatsMarketing extends Command
{
    protected $signature = 'stats:marketing {--days=14 : Cantidad de días hacia atrás} {--json : Salida en JSON}';
    protected $description = 'Vista unificada de marketing por día: GA vs sitio vs gasto Meta/Google Ads vs leads vs ventas';

    public function handle(MarketingStatsService $stats): int
    {
        $days = max(1, (int) $this->option('days'));
        $result = $stats->daily($days);
        $rows = $result['dias'];
        $tot = $result['totales'];

        if ($this->option('json')) {
            $this->line(json_encode(['dias' => $rows->values()->all(), 'totales' => $tot], JSON_UNESCAPED_UNICODE));
            return Command::SUCCESS;
        }

        $this->info("📣 Marketing unificado {$rows->last()['fecha']} → {$rows->first()['fecha']}");
        $this->table(
            ['Fecha', 'GA users', 'GA sess', 'Site', 'Clic WA', 'Meta $', 'G-Ads $', 'Leads', '·con anuncio', 'Facturas', '₡ Ventas'],
            $rows->map(fn ($r) => [
                \Carbon\Carbon::parse($r['fecha'])->format('d/m'),
                $r['ga_users'],
                $r['ga_sessions'],
                $r['site'],
                $r['wa'],
                number_format($r['fb'], 2),
                number_format($r['gads'], 2),
                $r['leads'],
                $r['leads_fb'] ?: '',
                $r['inv'],
                number_format($r['total'], 0),
            ])->all()
        );
        $this->line(sprintf(
            'TOTAL: GA %d users · site %d · clics WA %d · Meta $%.2f · G-Ads $%.2f · leads %d (%d con anuncio) · %d facturas ₡%s',
            $tot['ga_users'], $tot['site'], $tot['wa'], $tot['fb'], $tot['gads'], $tot['leads'], $tot['leads_fb'], $tot['inv'], number_format($tot['total'], 0)
        ));

        return Command::SUCCESS;
    }
}
