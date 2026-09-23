<?php

namespace App\Services;

use App\Models\FacebookAdReport;
use Illuminate\Support\Facades\Http;

class FacebookAdsService
{
    protected string $accessToken;
    protected string $adAccountId;
    protected string $apiVersion;

    protected array $insightFields = [
        'campaign_name',
        'campaign_id',
        'adset_name',
        'adset_id',
        'ad_name',
        'ad_id',
        'impressions',
        'clicks',
        'unique_clicks',
        'inline_link_clicks',
        'inline_post_engagement',
        'spend',
        'reach',
        'frequency',
        'cpm',
        'cpc',
        'cpp',
        'ctr',
        'actions',
        'action_values',
        'date_start',
        'date_stop',
    ];

    private ?array $campaignCache = null;

    public function __construct()
    {
        $this->accessToken = config('services.facebook_ads.access_token');
        $this->adAccountId = config('services.facebook_ads.ad_account_id');
        $this->apiVersion = 'v22.0';
    }

    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && !empty($this->adAccountId);
    }

    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'message' => 'No configurado: falta access token o ad account ID'];
        }

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/me", [
                'access_token' => $this->accessToken,
                'fields' => 'name,id',
            ]);

            if ($response->successful()) {
                $name = $response->json('name', 'desconocido');
                return ['ok' => true, 'message' => "Conectado a Meta Ads: {$name}"];
            }

            $error = $response->json('error.message', 'Error desconocido');
            return ['ok' => false, 'message' => $error];
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    public function fetchInsights(
        \DateTime $date,
        string $level = 'campaign',
    ): array {
        $since = $date->format('Y-m-d');
        $until = $date->format('Y-m-d');
        $fields = $this->insightFields;

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->adAccountId}/insights", [
                'fields' => implode(',', $fields),
                'time_range' => json_encode(['since' => $since, 'until' => $until]),
                'level' => $level,
                'limit' => 100,
                'access_token' => $this->accessToken,
            ]);

            if (!$response->successful()) {
                logger()->warning('Meta Ads API error', ['body' => $response->body()]);
                return [];
            }

            return $response->json('data', []);
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }

    public function getCampaignsMeta(): array
    {
        if ($this->campaignCache !== null) {
            return $this->campaignCache;
        }

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->adAccountId}/campaigns", [
                'fields' => 'id,name,objective,status,daily_budget,lifetime_budget',
                'limit' => 100,
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                $campaigns = $response->json('data', []);
                $this->campaignCache = [];
                foreach ($campaigns as $c) {
                    $this->campaignCache[$c['id']] = $c;
                }
                return $this->campaignCache;
            }
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }

    public function fetchCampaignPerformance(\DateTime $date): array
    {
        $rows = $this->fetchInsights($date, 'campaign');
        $campaignsMeta = $this->getCampaignsMeta();

        foreach ($rows as &$row) {
            $cid = $row['campaign_id'] ?? null;
            if ($cid && isset($campaignsMeta[$cid])) {
                $row['campaign_objective'] = $campaignsMeta[$cid]['objective'] ?? null;
                $row['campaign_status'] = $campaignsMeta[$cid]['status'] ?? null;
            }
        }

        return $rows;
    }

    public function fetchActiveCampaigns(): array
    {
        return $this->getCampaignsMeta();
    }

    public function extractActions(array $actions, array $types = []): array
    {
        $result = ['total' => 0, 'conversion_value' => 0.0, 'by_type' => []];

        foreach ($actions as $action) {
            $type = $action['action_type'] ?? '';
            $value = (int) ($action['value'] ?? 0);

            if (!empty($types) && !in_array($type, $types, true)) {
                continue;
            }

            $result['total'] += $value;
            $result['by_type'][$type] = ($result['by_type'][$type] ?? 0) + $value;
        }

        return $result;
    }

    public function extractActionValues(array $actionValues, array $types = []): float
    {
        $total = 0.0;

        foreach ($actionValues as $av) {
            $type = $av['action_type'] ?? '';
            $value = (float) ($av['value'] ?? 0);

            if (!empty($types) && !in_array($type, $types, true)) {
                continue;
            }

            $total += $value;
        }

        return $total;
    }

    public function syncDaily(\DateTime $date, string $level = 'campaign'): int
    {
        $fetchMethod = $level === 'campaign' ? 'fetchCampaignPerformance' : 'fetchInsights';
        $rows = $this->{$fetchMethod}($date, $level);

        $count = 0;

        foreach ($rows as $row) {
            $spend = (float) ($row['spend'] ?? 0);
            if ($spend <= 0) continue;

            $actions = $row['actions'] ?? [];
            $actionValues = $row['action_values'] ?? [];

            // OJO: 'lead' genérico es rollup que YA incluye
            // 'offsite_conversion.fb_pixel_lead' -> contar ambos duplica.
            // Se usan solo variantes offsite (eventos reales del pixel).
            $conversionTypes = [
                'purchase',
                'add_to_cart',
                'initiate_checkout',
                'complete_registration',
                'subscribe',
                'offsite_conversion.fb_pixel_purchase',
                'offsite_conversion.fb_pixel_add_to_cart',
                'offsite_conversion.fb_pixel_initiate_checkout',
                'offsite_conversion.fb_pixel_lead',
            ];
            // Meta ya reporta action_values en la moneda de la cuenta (USD),
            // aunque el pixel envíe CRC. No convertir de nuevo.
            $valueTypes = [
                'purchase',
                'offsite_conversion.fb_pixel_purchase',
                // El clic a WhatsApp dispara evento Lead con valor (precio del
                // producto). Sin esto, conversion_value siempre era 0.
                'offsite_conversion.fb_pixel_lead',
            ];

            $conversions = $this->extractActions($actions, $conversionTypes);
            $conversionValue = $this->extractActionValues($actionValues, $valueTypes);

            $conversionCount = $conversions['total'];
            $costPerConversion = $conversionCount > 0 ? $spend / $conversionCount : 0;
            $roas = $spend > 0 && $conversionValue > 0 ? $conversionValue / $spend : 0;

            FacebookAdReport::updateOrCreate(
                [
                    'report_date' => $date->format('Y-m-d'),
                    'campaign_id' => $row['campaign_id'] ?? null,
                    'level' => $level,
                    'ad_id' => $row['ad_id'] ?? null,
                ],
                [
                'ad_account_id' => $this->adAccountId,
                'campaign_name' => $row['campaign_name'] ?? 'Unknown',
                'campaign_objective' => $row['campaign_objective'] ?? null,
                'campaign_status' => $row['campaign_status'] ?? null,
                'adset_name' => $row['adset_name'] ?? null,
                'adset_id' => $row['adset_id'] ?? null,
                'ad_name' => $row['ad_name'] ?? null,
                'is_active' => true,
                'impressions' => $row['impressions'] ?? 0,
                'clicks' => $row['clicks'] ?? 0,
                'unique_clicks' => $row['unique_clicks'] ?? 0,
                'inline_link_clicks' => $row['inline_link_clicks'] ?? 0,
                'inline_post_engagement' => $row['inline_post_engagement'] ?? 0,
                'spend' => $spend,
                'reach' => $row['reach'] ?? 0,
                'frequency' => $row['frequency'] ?? 0,
                'cpm' => $row['cpm'] ?? 0,
                'cpc' => $row['cpc'] ?? 0,
                'cpp' => $row['cpp'] ?? 0,
                'ctr' => $row['ctr'] ?? 0,
                'conversions' => $conversionCount,
                'conversion_value' => round($conversionValue, 2),
                'roas' => round($roas, 2),
                'cost_per_conversion' => round($costPerConversion, 2),
                'level' => $level,
                'date_start' => $row['date_start'] ?? null,
                'date_stop' => $row['date_stop'] ?? null,
                'raw_data' => $row,
            ]);

            $count++;
        }

        return $count;
    }

    public function syncDailyAllLevels(\DateTime $date): array
    {
        return [
            'campaigns' => $this->syncDaily($date, 'campaign'),
            'adsets' => $this->syncDaily($date, 'adset'),
            'ads' => $this->syncDaily($date, 'ad'),
        ];
    }
}