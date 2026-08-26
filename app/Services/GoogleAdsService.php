<?php

namespace App\Services;

use App\Models\GoogleAdsReport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleAdsService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $customerId;
    protected string $mccId;
    protected string $developerToken;

    public function __construct()
    {
        $this->clientId = config('services.google.ads_client_id');
        $this->clientSecret = config('services.google.ads_client_secret');
        $this->refreshToken = config('services.google.ads_refresh_token');
        $this->customerId = str_replace('-', '', config('services.google.ads_customer_id'));
        $this->mccId = str_replace('-', '', config('services.google.ads_mcc_id'));
        $this->developerToken = config('services.google.ads_developer_token');
    }

    public function isConfigured(): bool
    {
        return !empty($this->clientId) && !empty($this->clientSecret) && !empty($this->refreshToken) && !empty($this->customerId) && !empty($this->developerToken);
    }

    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'message' => 'No configurado: faltan credenciales OAuth o developer token'];
        }

        $token = $this->getToken();
        if (!$token) {
            return ['ok' => false, 'message' => 'No se pudo obtener token de acceso'];
        }

        try {
            $headers = [
                'Authorization' => "Bearer {$token}",
                'developer-token' => $this->developerToken,
            ];
            if (!empty($this->mccId)) {
                $headers['login-customer-id'] = $this->mccId;
            }

            $response = Http::withHeaders($headers)
                ->post("https://googleads.googleapis.com/v25/customers/{$this->customerId}/googleAds:search", [
                    'query' => "SELECT customer.id, customer.descriptive_name FROM customer LIMIT 1",
                ]);

            if ($response->successful()) {
                $name = $response->json('results.0.customer.descriptiveName', 'desconocido');
                return ['ok' => true, 'message' => "Conectado a Google Ads: {$name} ({$this->customerId})"];
            }

            $error = $response->json('error.message', 'Error desconocido');
            return ['ok' => false, 'message' => $error];
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    protected function getToken(): ?string
    {
        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::warning('Google Ads: getToken falló', ['body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function fetchCampaignPerformance(\DateTime $date): array
    {
        $token = $this->getToken();
        if (!$token) return [];

        $dateStr = $date->format('Y-m-d');

        try {
            $headers = [
                'Authorization' => "Bearer {$token}",
                'developer-token' => $this->developerToken,
            ];
            if (!empty($this->mccId)) {
                $headers['login-customer-id'] = $this->mccId;
            }

            $response = Http::withHeaders($headers)->post("https://googleads.googleapis.com/v25/customers/{$this->customerId}/googleAds:search", [
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
                      AND metrics.impressions > 0
                ",
            ]);

            if (!$response->successful()) {
                Log::warning('Google Ads: fetchCampaignPerformance falló', [
                    'date' => $dateStr,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

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
