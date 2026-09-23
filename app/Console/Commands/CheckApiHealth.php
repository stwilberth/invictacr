<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\FacebookAdReport;
use App\Models\GoogleAdsReport;
use App\Models\GoogleAnalyticsReport;
use App\Models\SearchConsoleReport;
use App\Services\WebhookNotifierService;
use Illuminate\Console\Command;

class CheckApiHealth extends Command
{
    protected $signature = 'app:check-api-health {--threshold=3 : Días máximos sin sincronizar}';
    protected $description = 'Verifica la última sincronización de APIs y genera alertas si alguna no sincroniza hace X días';

    public function handle(WebhookNotifierService $webhook): int
    {
        $threshold = max(1, (int) $this->option('threshold'));
        $services = [
            'google_analytics' => [
                'label' => 'Google Analytics',
                'last_date' => $this->parseDate(GoogleAnalyticsReport::max('report_date')),
            ],
            'google_ads' => [
                'label' => 'Google Ads',
                'last_date' => $this->parseDate(GoogleAdsReport::max('report_date')),
            ],
            'meta_ads' => [
                'label' => 'Meta Ads',
                'last_date' => $this->parseDate(FacebookAdReport::max('report_date')),
            ],
            'search_console' => [
                'label' => 'Search Console',
                'last_date' => $this->parseDate(SearchConsoleReport::max('report_date')),
            ],
        ];

        $hadIssues = false;
        $hasActiveAlerts = false;

        foreach ($services as $serviceKey => $service) {
            $lastDate = $service['last_date'];
            $daysSince = $lastDate ? (int) round(now()->diffInDays($lastDate, true)) : null;

            $activeAlert = Alert::active()
                ->byType("api_sync_{$serviceKey}")
                ->first();

            if ($daysSince === null) {
                if (!$activeAlert) {
                    Alert::create([
                        'type' => "api_sync_{$serviceKey}",
                        'title' => "{$service['label']}: sin datos",
                        'message' => "{$service['label']} nunca se ha sincronizado. Revisar configuración.",
                        'level' => 'critical',
                        'context' => ['service' => $serviceKey, 'days_since' => null],
                    ]);
                    $hadIssues = true;
                }
                continue;
            }

            if ($daysSince > $threshold) {
                $title = "{$service['label']}: {$daysSince} días sin sincronizar";
                $message = "La última sincronización de {$service['label']} fue hace {$daysSince} días (límite: {$threshold}). Las métricas del dashboard pueden estar desactualizadas.";

                if (!$activeAlert) {
                    $alert = Alert::create([
                        'type' => "api_sync_{$serviceKey}",
                        'title' => $title,
                        'message' => $message,
                        'level' => $daysSince > $threshold * 2 ? 'critical' : 'warning',
                        'context' => [
                            'service' => $serviceKey,
                            'days_since' => $daysSince,
                            'threshold' => $threshold,
                            'last_date' => $lastDate->format('Y-m-d'),
                        ],
                    ]);

                    $webhook->send($title, $message, $daysSince > $threshold * 2 ? 'critical' : 'warning');

                    $alert->update(['notified_at' => now()]);
                    $this->warn("ALERTA: {$title}");
                    $hadIssues = true;
                } else {
                    $this->warn("Alerta ya activa para {$service['label']} ({$daysSince} días)");
                    $hasActiveAlerts = true;
                }
            } else {
                if ($activeAlert) {
                    $activeAlert->resolve();
                    $this->info("Resuelta alerta para {$service['label']} (sincronizó hace {$daysSince} días)");
                }
                $this->line("{$service['label']}: OK ({$daysSince} días)");
            }
        }

        $this->newLine();

        if ($hadIssues || $hasActiveAlerts) {
            $activeCount = Alert::active()->count();
            $this->info("Verificación completada. {$activeCount} alerta(s) activa(s).");
        } else {
            $this->info('Todas las APIs sincronizan correctamente.');
        }

        return Command::SUCCESS;
    }

    private function parseDate(mixed $value): ?\Carbon\Carbon
    {
        if ($value === null) return null;
        if ($value instanceof \Carbon\Carbon) return $value;
        if (is_string($value)) {
            try {
                return \Carbon\Carbon::parse($value);
            } catch (\Throwable $e) {
                return null;
            }
        }
        return null;
    }
}