<?php

namespace App\Services;

use Google\Client as GoogleClient;

class GoogleServiceAccount
{
    protected ?GoogleClient $client = null;
    protected string $keyFilePath;

    public function __construct()
    {
        $this->keyFilePath = config('services.google.service_account_key_path');
    }

    public function isConfigured(): bool
    {
        return !empty($this->keyFilePath) && file_exists($this->keyFilePath);
    }

    public function getClient(): ?GoogleClient
    {
        if ($this->client) {
            return $this->client;
        }

        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($this->keyFilePath);
            $client->setApplicationName('InvictaCR Analytics');
            $this->client = $client;
            return $client;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function getAccessToken(string $scope): ?string
    {
        $client = $this->getClient();
        if (!$client) return null;

        try {
            $client->setScopes([$scope]);
            $token = $client->fetchAccessTokenWithAssertion();

            if (isset($token['access_token'])) {
                return $token['access_token'];
            }
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }
}
