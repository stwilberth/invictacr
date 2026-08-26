<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CloudflareCacheService
{
    private string $apiToken;
    private string $zoneId;

    public function __construct()
    {
        $this->apiToken = config('services.cloudflare.api_token');
        $this->zoneId = config('services.cloudflare.zone_id');
    }

    public function purgeEverything(): bool
    {
        if (!$this->apiToken || !$this->zoneId) {
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Content-Type' => 'application/json',
        ])->post("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/purge_cache", [
            'purge_everything' => true,
        ]);

        return $response->json('success', false);
    }

    public function purgeUrls(array $urls): bool
    {
        if (!$this->apiToken || !$this->zoneId || empty($urls)) {
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Content-Type' => 'application/json',
        ])->post("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/purge_cache", [
            'files' => $urls,
        ]);

        return $response->json('success', false);
    }
}
