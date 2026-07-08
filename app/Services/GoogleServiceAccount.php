<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

class GoogleServiceAccount
{
    protected array $credentials = [];
    protected string $keyFilePath;

    public function __construct()
    {
        $this->keyFilePath = config('services.google.service_account_key_path');
    }

    public function isConfigured(): bool
    {
        if (empty($this->keyFilePath) || !file_exists($this->keyFilePath)) {
            return false;
        }

        try {
            $this->loadCredentials();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function loadCredentials(): void
    {
        if (!empty($this->credentials)) return;

        $json = file_get_contents($this->keyFilePath);
        $this->credentials = json_decode($json, true);

        if (empty($this->credentials['client_email']) || empty($this->credentials['private_key'])) {
            throw new \RuntimeException('Invalid service account JSON: missing client_email or private_key');
        }
    }

    public function getAccessToken(string $scope): ?string
    {
        try {
            $this->loadCredentials();
        } catch (\Exception $e) {
            report($e);
            return null;
        }

        $now = time();
        $jwt = JWT::encode([
            'iss' => $this->credentials['client_email'],
            'sub' => $this->credentials['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
            'scope' => $scope,
        ], $this->credentials['private_key'], 'RS256');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (!$response->successful()) {
            report($response->body());
            return null;
        }

        return $response->json('access_token');
    }
}
