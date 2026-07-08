<?php

namespace App\Services;

use App\Models\GoogleAdsReport;
use Illuminate\Support\Facades\Http;

class GoogleAdsService
{
    protected GoogleServiceAccount $serviceAccount;
    protected string $customerId;
    protected string $developerToken;

    public function __construct()
    {
        $this->serviceAccount = app(GoogleServiceAccount::class);
        $this->customerId = str_replace('-', '', config('services.google.ads_customer_id'));
        $this->developerToken = config('services.google.ads_developer_token');
    }

    public function isConfigured(): bool
    {
        return $this->serviceAccount->isConfigured() && !empty($this->customerId) && !empty($this->developerToken);
    }

    protected function getToken(): ?string
    {
        return $this->serviceAccount->getAccessToken('https://www.googleapis.com/auth/adwords');
    }

    public function fetchCampaignPerformance(\DateTime $date): array
    {
        $token = $this->getToken();
        if (!$token) return [];

        $dateStr = $date->format('Y-m-d');

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'developer-token' => $this->developerToken,
            ])->post("https://googleads.googleapis.com/v24/customers/{$this->customerId}/googleAds:search", [
                'query' => "
                    SELECT
                        campaign.id,
                        campaign.name,
                        metrics.impressions,
                        metrics.clicks,
                        metrics.cost_micros,
                        metrics.conversions,
                        metrics.conversions_value,
                        metrics.ctr,
                        metrics.average_cpc
                    FROM campaign
                    WHERE segments.date = '{$dateStr}'
                ",
            ]);

            if (!$response->successful()) return [];


            return $response->json('results', []);
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
            $campaignData = $campaign['campaign'] ?? [];
            $metrics = $campaign['metrics'] ?? [];

            GoogleAdsReport::create([
                'report_date' => $date->format('Y-m-d'),
                'campaign_name' => $campaignData['name'] ?? 'Unknown',
                'campaign_id' => $campaignData['id'] ?? null,
                'impressions' => $metrics['impressions'] ?? 0,
                'clicks' => $metrics['clicks'] ?? 0,
                'cost' => ($metrics['costMicros'] ?? 0) / 1000000,
                'conversions' => $metrics['conversions'] ?? 0,
                'conversion_value' => $metrics['conversionsValue'] ?? 0,
                'ctr' => $metrics['ctr'] ?? 0,
                'average_cpc' => ($metrics['averageCpc'] ?? 0) / 1000000,
                'raw_data' => $campaign,
            ]);

            $count++;
        }

        return $count;
    }
}
