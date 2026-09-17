<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramService
{
    protected string $accessToken;
    protected string $igId;
    protected string $apiVersion;

    public function __construct()
    {
        $this->accessToken = config('services.facebook.access_token');
        $this->igId = config('services.instagram.account_id');
        $this->apiVersion = 'v22.0';
    }

    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && !empty($this->igId);
    }

    /**
     * Publica una historia en Instagram. La API exige URL pública (no bytes):
     * crea el contenedor, espera a que quede FINISHED y lo publica.
     *
     * @return string|null id del medio publicado, o null si falla.
     */
    public function publishStory(string $imageUrl): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $container = Http::post("https://graph.facebook.com/{$this->apiVersion}/{$this->igId}/media", [
                'image_url' => $imageUrl,
                'media_type' => 'STORIES',
                'access_token' => $this->accessToken,
            ]);

            if (!$container->successful() || !$container->json('id')) {
                Log::error('Instagram story container failed: ' . $container->body());
                return null;
            }

            $creationId = $container->json('id');

            // Esperar a que el contenedor esté listo (las imágenes suelen ser inmediato).
            $ready = false;
            for ($i = 0; $i < 12; $i++) {
                sleep(5);
                $status = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$creationId}", [
                    'fields' => 'status_code',
                    'access_token' => $this->accessToken,
                ]);
                $code = $status->json('status_code');
                if ($code === 'FINISHED') {
                    $ready = true;
                    break;
                }
                if ($code === 'ERROR') {
                    Log::error('Instagram story container error: ' . $status->body());
                    return null;
                }
            }

            if (!$ready) {
                Log::error('Instagram story container timeout: ' . $creationId);
                return null;
            }

            $publish = Http::post("https://graph.facebook.com/{$this->apiVersion}/{$this->igId}/media_publish", [
                'creation_id' => $creationId,
                'access_token' => $this->accessToken,
            ]);

            if (!$publish->successful() || !$publish->json('id')) {
                Log::error('Instagram story publish failed: ' . $publish->body());
                return null;
            }

            return $publish->json('id');
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}
