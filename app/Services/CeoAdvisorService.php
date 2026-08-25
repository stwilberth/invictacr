<?php

namespace App\Services;

use App\Models\AiCeoRecommendation;
use App\Models\ExternalFactor;
use App\Models\FacebookAdReport;
use App\Models\GoogleAdsReport;
use App\Models\GoogleAnalyticsReport;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SearchConsoleReport;
use App\Models\VisitorEvent;
use Illuminate\Support\Facades\Http;

class CeoAdvisorService
{
    /**
     * Genera un nuevo plan de acción (batch de recomendaciones) analizando
     * el estado actual del negocio y lo guarda en base de datos.
     *
     * @return int Cantidad de recomendaciones generadas (0 si falló).
     */
    public function generatePlan(): int
    {
        $snapshot = $this->buildSnapshot();
        $json = $this->callAi($snapshot);

        if (!$json || !isset($json['recommendations']) || !is_array($json['recommendations'])) {
            return 0;
        }

        $batchKey = now()->format('Y-m-d_Hi');
        $count = 0;

        foreach ($json['recommendations'] as $rec) {
            if (empty($rec['title']) || empty($rec['action'])) {
                continue;
            }

            AiCeoRecommendation::create([
                'batch_key' => $batchKey,
                'category' => in_array($rec['category'] ?? '', ['urgente', 'oportunidad', 'estrategia'], true)
                    ? $rec['category']
                    : 'estrategia',
                'priority' => in_array($rec['priority'] ?? '', ['alta', 'media', 'baja'], true)
                    ? $rec['priority']
                    : 'media',
                'title' => $rec['title'],
                'rationale' => $rec['rationale'] ?? '',
                'action' => $rec['action'],
                'status' => 'pendiente',
                'raw_data' => $snapshot,
                'generated_at' => now(),
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Recolecta el estado consolidado del negocio: ventas, tráfico, ads,
     * inventario crítico y factores externos activos.
     */
    protected function buildSnapshot(): array
    {
        $now = now();
        $start30 = $now->copy()->subDays(30);
        $prevStart30 = $now->copy()->subDays(60);
        $prevEnd30 = $now->copy()->subDays(31);

        // Ingresos actuales vs período anterior
        $currentRevenue = Invoice::whereBetween('created_at', [$start30, $now])
            ->where('status', '!=', 'cancelled')->sum('total');
        $prevRevenue = Invoice::whereBetween('created_at', [$prevStart30, $prevEnd30])
            ->where('status', '!=', 'cancelled')->sum('total');
        $currentOrders = Invoice::whereBetween('created_at', [$start30, $now])
            ->where('status', '!=', 'cancelled')->count();
        $prevOrders = Invoice::whereBetween('created_at', [$prevStart30, $prevEnd30])
            ->where('status', '!=', 'cancelled')->count();

        $revenueGrowth = $prevRevenue > 0
            ? round((($currentRevenue - $prevRevenue) / $prevRevenue) * 100, 1)
            : null;

        // Tráfico (GA)
        $gaReports = GoogleAnalyticsReport::whereBetween('report_date', [$start30, $now])->get();
        $prevGaReports = GoogleAnalyticsReport::whereBetween('report_date', [$prevStart30, $prevEnd30])->get();
        $usersGrowth = $prevGaReports->sum('users') > 0
            ? round((($gaReports->sum('users') - $prevGaReports->sum('users')) / $prevGaReports->sum('users')) * 100, 1)
            : null;

        // Ads
        $adsReports = GoogleAdsReport::whereBetween('report_date', [$start30, $now])->get();
        $fbAds = FacebookAdReport::whereBetween('report_date', [$start30, $now])->get();

        // Search Console: top queries con buen CTR/impresiones pero baja posición (oportunidad SEO)
        $scReports = SearchConsoleReport::whereBetween('report_date', [$start30, $now])->get();
        $topQueries = $scReports->groupBy('query')->map(fn($g) => [
            'clicks' => $g->sum('clicks'),
            'impressions' => $g->sum('impressions'),
            'avg_position' => round($g->avg('position'), 1),
        ])->sortByDesc('impressions')->take(15)->toArray();

        // Productos con más vistas en los últimos 30 días
        $viewedProductIds = VisitorEvent::where('type', 'product_view')
            ->whereBetween('created_at', [$start30, $now])
            ->whereNotNull('product_id')
            ->selectRaw('product_id, COUNT(*) as views')
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->take(30)
            ->pluck('views', 'product_id');

        $trendingAgotados = Product::whereIn('id', $viewedProductIds->keys())
            ->where(function ($q) {
                $q->where('disponibilidad', 'agotado')->orWhere('stock', '<=', 0);
            })
            ->get(['id', 'modelo', 'title', 'stock', 'disponibilidad'])
            ->map(fn($p) => [
                'modelo' => $p->modelo,
                'title' => $p->title,
                'views_30d' => (int) ($viewedProductIds[$p->id] ?? 0),
            ])
            ->sortByDesc('views_30d')
            ->take(10)
            ->values()
            ->toArray();

        // Productos "próximos" (lanzamientos)
        $upcomingCount = Product::where('proximo', true)->count();

        // Productos agotados en general (no solo trending)
        $totalAgotados = Product::where(function ($q) {
            $q->where('disponibilidad', 'agotado')->orWhere('stock', '<=', 0);
        })->count();

        // Factores externos activos/recientes
        $externalFactors = ExternalFactor::where('active', true)
            ->where('event_date', '>=', $start30)
            ->orderByDesc('event_date')
            ->get(['category', 'title', 'description', 'impact_level'])
            ->toArray();

        // Última conclusión global del pipeline (Timeline IA) si existe
        $globalInsight = \App\Models\AiTimelineInsight::where('period_key', 'global')->first();

        return [
            'periodo_analizado' => '30 días (comparado con 30 días previos)',
            'ingresos' => [
                'actual' => (float) $currentRevenue,
                'anterior' => (float) $prevRevenue,
                'crecimiento_pct' => $revenueGrowth,
                'ordenes_actual' => $currentOrders,
                'ordenes_anterior' => $prevOrders,
            ],
            'trafico' => [
                'usuarios_actual' => (int) $gaReports->sum('users'),
                'usuarios_anterior' => (int) $prevGaReports->sum('users'),
                'crecimiento_pct' => $usersGrowth,
                'rebote_promedio' => round($gaReports->avg('bounce_rate') ?? 0, 1),
            ],
            'publicidad' => [
                'google_ads_costo' => (float) $adsReports->sum('cost'),
                'google_ads_clics' => (int) $adsReports->sum('clicks'),
                'google_ads_conversiones' => (float) $adsReports->sum('conversions'),
                'meta_ads_gasto' => (float) $fbAds->sum('spend'),
                'meta_ads_clics' => (int) $fbAds->sum('clicks'),
            ],
            'seo_top_queries' => $topQueries,
            'inventario' => [
                'productos_agotados_total' => $totalAgotados,
                'productos_proximos' => $upcomingCount,
                'agotados_con_trafico_alto' => $trendingAgotados,
            ],
            'factores_externos_recientes' => $externalFactors,
            'ultima_conclusion_pipeline' => $globalInsight?->conclusion,
            'ultimo_consejo_pipeline' => $globalInsight?->advice,
        ];
    }

    protected function callAi(array $snapshot): ?array
    {
        $apiKey = config('services.anthropic.key');
        $model = config('services.anthropic.model', 'claude-haiku-4-5');
        $timeout = (int) config('services.anthropic.timeout', 30);
        if (!$apiKey) {
            return null;
        }

        $dataJson = json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $systemPrompt = "Eres el CEO virtual de Invicta Costa Rica, una tienda online de relojes Invicta. "
            . "Tu trabajo es analizar los datos del negocio y decirle al equipo, con autoridad y claridad, "
            . "cuál es el camino a seguir. Piensas como un CEO real: priorizas, eres directo, y das acciones "
            . "concretas y ejecutables, no consejos genéricos. Devuelves SOLO un JSON válido sin markdown ni texto adicional.";

        $userPrompt = "Analiza estos datos del negocio de los últimos 30 días y genera un plan de acción.\n\n"
            . "Datos:\n{$dataJson}\n\n"
            . "Devuelve un JSON con la clave \"recommendations\": un array de 3 a 6 objetos, cada uno con:\n"
            . "- \"category\": una de \"urgente\" (requiere acción inmediata, riesgo real), \"oportunidad\" (crecimiento posible ahora), \"estrategia\" (mediano/largo plazo)\n"
            . "- \"priority\": \"alta\", \"media\" o \"baja\"\n"
            . "- \"title\": título corto y directo (máx 12 palabras)\n"
            . "- \"rationale\": por qué importa, citando datos concretos del snapshot (máx 3 oraciones)\n"
            . "- \"action\": la acción exacta que el equipo debe tomar (máx 2 oraciones, concreta y ejecutable)\n\n"
            . "Ordena las recomendaciones de más a menos importante. No inventes datos que no estén en el snapshot. "
            . "Si no hay suficiente información para alguna categoría, no la incluyas. Responde SOLO con el JSON.";

        try {
            $response = Http::timeout($timeout)->withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => 1800,
                'temperature' => 0.3,
                'system' => [
                    [
                        'type' => 'text',
                        'text' => $systemPrompt,
                        'cache_control' => ['type' => 'ephemeral'],
                    ],
                ],
                'messages' => [
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

            $data = $response->json();
            $text = $data['content'][0]['text'] ?? null;
            if (!$text) {
                return null;
            }

            $text = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($text));

            $decoded = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            return $decoded;
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }
}
