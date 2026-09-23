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
     * Perfil de la cuenta (seguidores, total de medios, foto).
     */
    public function fetchProfile(): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->igId}", [
                'fields' => 'username,followers_count,media_count,profile_picture_url',
                'access_token' => $this->accessToken,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Medios recientes con likes/comentarios y enlace público.
     */
    public function fetchRecentMedia(int $limit = 12): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->igId}/media", [
                'fields' => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp,like_count,comments_count',
                'limit' => max(1, min(50, $limit)),
                'access_token' => $this->accessToken,
            ]);

            return $response->successful() ? ($response->json('data', [])) : [];
        } catch (\Exception $e) {
            report($e);
            return [];
        }
    }

    /**
     * Insights de un medio (alcance, vistas, guardados, compartidos).
     * Se pide una métrica por request para aislar errores de permiso.
     */
    public function fetchMediaInsights(string $mediaId): array
    {
        $default = ['reach' => 0, 'views' => 0, 'likes' => 0, 'comments' => 0, 'saves' => 0, 'shares' => 0];

        if (!$this->isConfigured()) {
            return $default;
        }

        try {
            $insights = $default;

            foreach (['reach', 'views', 'likes', 'comments', 'saved', 'shares'] as $metric) {
                $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$mediaId}/insights", [
                    'metric' => $metric,
                    'access_token' => $this->accessToken,
                ]);

                if (!$response->successful()) {
                    continue;
                }

                foreach ($response->json('data', []) as $row) {
                    $value = $row['values'][0]['value'] ?? 0;
                    $field = $metric === 'saved' ? 'saves' : $metric;
                    $insights[$field] += is_array($value) ? (int) array_sum($value) : (int) $value;
                }
            }

            return $insights;
        } catch (\Exception $e) {
            report($e);
            return $default;
        }
    }

    /**
     * Publica una historia en Instagram. La API exige URL pública (no bytes):
     * crea el contenedor, espera a que quede FINISHED y lo publica.
     *
     * @return string|null id del medio publicado, o null si falla.
     */
    public function fetchStoryInsights(string $storyId): array
    {
        // En stories de IG las "reacciones" (likes rápidos) cuentan como replies.
        $default = ['views' => 0, 'impressions' => 0, 'reach' => 0, 'replies' => 0, 'reactions' => 0, 'shares' => 0];

        if (!$this->isConfigured()) {
            return $default;
        }

        try {
            // impressions está deprecated desde jul-2024 y removido en abr-2025;
            // para stories vigentes (<24h) usar views/reach/replies.
            // Se pide una métrica por request para aislar errores de permiso.
            $insights = $default;

            foreach (['views' => 'views', 'reach' => 'reach', 'replies' => 'replies'] as $metric => $field) {
                $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$storyId}/insights", [
                    'metric' => $metric,
                    'access_token' => $this->accessToken,
                ]);

                if (!$response->successful()) {
                    $error = $response->json('error.message', '');
                    if (str_contains($error, 'permission')) {
                        Log::warning("Instagram insights: falta permiso 'instagram_manage_insights'. {$error}");
                    } else {
                        Log::info("Instagram story insights '{$metric}' no disponibles para {$storyId}: {$error}");
                    }
                    continue;
                }

                foreach ($response->json('data', []) as $row) {
                    $value = $row['values'][0]['value'] ?? 0;
                    $insights[$field] += is_array($value) ? (int) array_sum($value) : (int) $value;
                }
            }

            $insights['impressions'] = $insights['views'];
            // Las reacciones rápidas de IG llegan como replies (DMs).
            $insights['reactions'] = $insights['replies'];

            return $insights;
        } catch (\Exception $e) {
            report($e);
            return $default;
        }
    }

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
