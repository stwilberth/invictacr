<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookNotifierService
{
    public function send(string $title, string $message, string $level = 'warning'): bool
    {
        $url = config('services.alerts.webhook_url');
        if (empty($url)) {
            return false;
        }

        try {
            $response = Http::timeout(10)->post($url, [
                'title' => $title,
                'message' => $message,
                'level' => $level,
                'source' => 'invicta-dashboard',
                'timestamp' => now()->toIso8601String(),
            ]);

            if (!$response->successful()) {
                Log::warning('WebhookNotifier: fallo al enviar', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('WebhookNotifier: excepción al enviar', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}