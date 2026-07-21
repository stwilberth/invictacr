<?php

namespace App\Services;

use App\Models\GoogleAnalyticsReport;
use Illuminate\Support\Facades\Http;

class GoogleAnalyticsService
{
    protected GoogleServiceAccount $serviceAccount;
    protected string $propertyId;

    public function __construct()
    {
        $this->serviceAccount = app(GoogleServiceAccount::class);
        $this->propertyId = config('services.google.analytics_property_id');
    }

    public function isConfigured(): bool
    {
        return $this->serviceAccount->isConfigured() && !empty($this->propertyId);
    }

    protected function getToken(): ?string
    {
        return $this->serviceAccount->getAccessToken('https://www.googleapis.com/auth/analytics.readonly');
    }

    public function fetchRealtimeUsers(): ?int
    {
        $token = $this->getToken();
        if (!$token) return null;

        try {
            $response = Http::withToken($token)
                ->get("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}/runRealtimeReport", [
                    'metrics' => [['name' => 'activeUsers']],
                ]);

            if ($response->successful()) {
                return (int) ($response->json('rows.0.metricValues.0.value') ?? 0);
            }
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    public function fetchDailyReport(\DateTime $date): ?GoogleAnalyticsReport
    {
        $token = $this->getToken();
        if (!$token) return null;

        $dateStr = $date->format('Y-m-d');

        try {
            $response = Http::withToken($token)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", [
                    'dateRanges' => [['startDate' => $dateStr, 'endDate' => $dateStr]],
                    'metrics' => [
                        ['name' => 'activeUsers'],
                        ['name' => 'sessions'],
                        ['name' => 'screenPageViews'],
                        ['name' => 'bounceRate'],
                        ['name' => 'averageSessionDuration'],
                        ['name' => 'newUsers'],
                    ],
                    'dimensions' => [
                        ['name' => 'date'],
                    ],
                ]);

            if (!$response->successful()) return null;

            $data = $response->json('rows.0.metricValues', []);
            if (empty($data)) return null;

            $topPages = $this->fetchTopPages($date);
            $trafficSources = $this->fetchTrafficSources($date);
            $deviceBreakdown = $this->fetchDeviceBreakdown($date);

            return GoogleAnalyticsReport::updateOrCreate(
                ['report_date' => $dateStr],
                [
                    'users' => (int) ($data[0]['value'] ?? 0),
                    'sessions' => (int) ($data[1]['value'] ?? 0),
                    'pageviews' => (int) ($data[2]['value'] ?? 0),
                    'bounce_rate' => (float) ($data[3]['value'] ?? 0),
                    'avg_session_duration' => (float) ($data[4]['value'] ?? 0),
                    'new_users' => (int) ($data[5]['value'] ?? 0),
                    'top_pages' => $topPages,
                    'traffic_sources' => $trafficSources,
                    'device_breakdown' => $deviceBreakdown,
                    'raw_data' => $response->json(),
                ]
            );
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    public function fetchTrafficSources(\DateTime $date): array
    {
        $token = $this->getToken();
        if (!$token) return [];

        $dateStr = $date->format('Y-m-d');

        try {
            $response = Http::withToken($token)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", [
                    'dateRanges' => [['startDate' => $dateStr, 'endDate' => $dateStr]],
                    'metrics' => [['name' => 'activeUsers'], ['name' => 'sessions']],
                    'dimensions' => [['name' => 'sessionSource'], ['name' => 'sessionMedium']],
                    'orderBys' => [['metric' => ['metricName' => 'activeUsers'], 'desc' => true]],
                    'limit' => 10,
                ]);

            if (!$response->successful()) return [];

            $rows = $response->json('rows', []);
            $sources = [];

            foreach ($rows as $row) {
                $source = $row['dimensionValues'][0]['value'] ?? '';
                $medium = $row['dimensionValues'][1]['value'] ?? '';
                $key = "{$source} / {$medium}";
                $sources[] = [
                    'source' => $source,
                    'medium' => $medium,
                    'users' => (int) ($row['metricValues'][0]['value'] ?? 0),
                    'sessions' => (int) ($row['metricValues'][1]['value'] ?? 0),
                ];
            }

            return $sources;
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }

    public function fetchDeviceBreakdown(\DateTime $date): array
    {
        $token = $this->getToken();
        if (!$token) return [];

        $dateStr = $date->format('Y-m-d');

        try {
            $response = Http::withToken($token)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", [
                    'dateRanges' => [['startDate' => $dateStr, 'endDate' => $dateStr]],
                    'metrics' => [['name' => 'activeUsers'], ['name' => 'sessions']],
                    'dimensions' => [['name' => 'deviceCategory']],
                    'orderBys' => [['metric' => ['metricName' => 'activeUsers'], 'desc' => true]],
                ]);

            if (!$response->successful()) return [];

            $rows = $response->json('rows', []);
            $devices = [];

            foreach ($rows as $row) {
                $devices[] = [
                    'category' => $row['dimensionValues'][0]['value'] ?? '',
                    'users' => (int) ($row['metricValues'][0]['value'] ?? 0),
                    'sessions' => (int) ($row['metricValues'][1]['value'] ?? 0),
                ];
            }

            return $devices;
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }

    public function fetchTopPages(\DateTime $date): array
    {
        $token = $this->getToken();
        if (!$token) return [];

        $dateStr = $date->format('Y-m-d');

        try {
            $response = Http::withToken($token)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", [
                    'dateRanges' => [['startDate' => $dateStr, 'endDate' => $dateStr]],
                    'metrics' => [['name' => 'screenPageViews']],
                    'dimensions' => [['name' => 'pagePath']],
                    'orderBys' => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]],
                    'limit' => 10,
                ]);

            if (!$response->successful()) return [];

            $rows = $response->json('rows', []);
            $pages = [];

            foreach ($rows as $row) {
                $pages[] = [
                    'path' => $row['dimensionValues'][0]['value'] ?? '',
                    'views' => (int) ($row['metricValues'][0]['value'] ?? 0),
                ];
            }

            return $pages;
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }
}
