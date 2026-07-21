<?php

namespace App\Services;

use App\Models\FacebookAdReport;
use Illuminate\Support\Facades\Http;

class FacebookAdsService
{
    protected string $accessToken;
    protected string $adAccountId;
    protected string $apiVersion;

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

    public function fetchCampaignPerformance(\DateTime $date): array
    {
        $since = $date->format('Y-m-d');
        $until = $date->format('Y-m-d');

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->adAccountId}/insights", [
                'fields' => 'campaign_name,campaign_id,impressions,clicks,spend,reach,frequency,cpm,cpc,ctr',
                'time_range' => json_encode(['since' => $since, 'until' => $until]),
                'level' => 'campaign',
                'access_token' => $this->accessToken,
            ]);

            if (!$response->successful()) return [];

            return $response->json('data', []);
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }

    public function syncDaily(\DateTime $date): int
    {
        $campaigns = $this->fetchCampaignPerformance($date);
        $count = 0;

        foreach ($campaigns as $campaign) {
            $spend = (float) ($campaign['spend'] ?? 0);
            if ($spend <= 0) continue;

            FacebookAdReport::create([
                'report_date' => $date->format('Y-m-d'),
                'ad_account_id' => $this->adAccountId,
                'campaign_name' => $campaign['campaign_name'] ?? 'Unknown',
                'campaign_id' => $campaign['campaign_id'] ?? null,
                'is_active' => true,
                'impressions' => $campaign['impressions'] ?? 0,
                'clicks' => $campaign['clicks'] ?? 0,
                'spend' => $spend,
                'reach' => $campaign['reach'] ?? 0,
                'frequency' => $campaign['frequency'] ?? 0,
                'cpm' => $campaign['cpm'] ?? 0,
                'cpc' => $campaign['cpc'] ?? 0,
                'ctr' => $campaign['ctr'] ?? 0,
                'raw_data' => $campaign,
            ]);

            $count++;
        }

        return $count;
    }
}
