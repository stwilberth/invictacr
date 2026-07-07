<?php

namespace App\Services;

use App\Models\SearchConsoleReport;
use Illuminate\Support\Facades\Http;

class GoogleSearchConsoleService
{
    protected GoogleServiceAccount $serviceAccount;
    protected string $siteUrl;

    public function __construct()
    {
        $this->serviceAccount = app(GoogleServiceAccount::class);
        $this->siteUrl = config('services.google.search_console_site_url');
    }

    public function isConfigured(): bool
    {
        return $this->serviceAccount->isConfigured() && !empty($this->siteUrl);
    }

    protected function getToken(): ?string
    {
        return $this->serviceAccount->getAccessToken('https://www.googleapis.com/auth/webmasters.readonly');
    }

    public function fetchSearchAnalytics(\DateTime $date): array
    {
        $token = $this->getToken();
        if (!$token) return [];

        $dateStr = $date->format('Y-m-d');

        try {
            $response = Http::withToken($token)
                ->post("https://searchconsole.googleapis.com/v1/sites/{$this->siteUrl}/searchAnalytics/query", [
                    'startDate' => $dateStr,
                    'endDate' => $dateStr,
                    'dimensions' => ['query', 'page', 'country', 'device'],
                    'rowLimit' => 100,
                ]);

            if (!$response->successful()) return [];

            return $response->json('rows', []);
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }

    public function syncDaily(\DateTime $date): int
    {
        $rows = $this->fetchSearchAnalytics($date);
        $count = 0;

        foreach ($rows as $row) {
            $dimensions = $row['keys'] ?? [];

            SearchConsoleReport::create([
                'report_date' => $date->format('Y-m-d'),
                'query' => $dimensions[0] ?? null,
                'page' => $dimensions[1] ?? null,
                'country' => $dimensions[2] ?? null,
                'device' => $dimensions[3] ?? null,
                'clicks' => $row['clicks'] ?? 0,
                'impressions' => $row['impressions'] ?? 0,
                'ctr' => $row['ctr'] ?? 0,
                'position' => $row['position'] ?? 0,
                'raw_data' => $row,
            ]);

            $count++;
        }

        return $count;
    }
}
