<?php

namespace App\Services;

use App\Models\FacebookInsight;
use App\Models\FacebookPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookBusinessService
{
    protected string $accessToken;
    protected string $pageId;
    protected string $apiVersion;
    protected ?string $pageToken = null;

    public function __construct()
    {
        $this->accessToken = config('services.facebook.access_token');
        $this->pageId = config('services.facebook.page_id');
        $this->apiVersion = 'v22.0';
    }

    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && !empty($this->pageId);
    }

    protected function getPageToken(): ?string
    {
        if ($this->pageToken) return $this->pageToken;

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}", [
                'fields' => 'access_token',
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                $this->pageToken = $response->json('access_token');
                return $this->pageToken;
            }
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    public function fetchPageInsights(\DateTime $date): ?FacebookInsight
    {
        if (!$this->isConfigured()) return null;

        $dateStr = $date->format('Y-m-d');
        $since = $date->format('Y-m-d');
        $until = $date->format('Y-m-d');

        $pageToken = $this->getPageToken();
        if (!$pageToken) return null;

        try {
            $metrics = 'page_views_total,page_total_actions,page_daily_follows,page_media_view,page_total_media_view_unique';

            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/insights", [
                'metric' => $metrics,
                'period' => 'day',
                'since' => $since,
                'until' => $until,
                'access_token' => $pageToken,
            ]);

            if (!$response->successful()) return null;

            $data = $response->json('data', []);
            $insights = [];

            foreach ($data as $metric) {
                $values = $metric['values'][0]['value'] ?? 0;
                $insights[$metric['name']] = is_array($values) ? array_sum($values) : $values;
            }

            return FacebookInsight::updateOrCreate(
                ['report_date' => $dateStr, 'page_id' => $this->pageId],
                [
                    'page_name' => config('services.facebook.page_name', ''),
                    'page_impressions' => $insights['page_views_total'] ?? 0,
                    'page_engaged_users' => $insights['page_total_actions'] ?? 0,
                    'page_follows' => $insights['page_daily_follows'] ?? 0,
                    'page_reactions' => $insights['page_total_media_view_unique'] ?? 0,
                    'page_comments' => 0,
                    'page_shares' => 0,
                    'page_views' => $insights['page_views_total'] ?? 0,
                    'raw_data' => $data,
                ]
            );
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    public function fetchPosts(\DateTime $since, int $limit = 20): int
    {
        if (!$this->isConfigured()) return 0;

        $pageToken = $this->getPageToken();
        if (!$pageToken) return 0;

        $count = 0;

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/posts", [
                'fields' => 'id,message,permalink_url,created_time,shares,reactions.limit(0).summary(true),comments.limit(0).summary(true)',
                'since' => $since->format('Y-m-d'),
                'limit' => $limit,
                'access_token' => $pageToken,
            ]);

            if (!$response->successful()) return 0;

            $posts = $response->json('data', []);

            foreach ($posts as $post) {
                $reactions = $post['reactions']['summary']['total_count'] ?? 0;
                $comments = $post['comments']['summary']['total_count'] ?? 0;
                $shares = $post['shares']['count'] ?? 0;

                FacebookPost::updateOrCreate(
                    ['post_id' => $post['id']],
                    [
                        'message' => $post['message'] ?? null,
                        'link' => $post['permalink_url'] ?? null,
                        'media_type' => null,
                        'posted_at' => $post['created_time'] ?? null,
                        'likes' => $reactions,
                        'comments' => $comments,
                        'shares' => $shares,
                        'reach' => 0,
                        'impressions' => 0,
                        'raw_data' => $post,
                    ]
                );

                $count++;
            }
        } catch (\Exception $e) {
            report($e);
        }

        return $count;
    }

    /**
     * Publica una foto con texto (y enlace al producto) en la página de Facebook.
     *
     * @return string|null id del post creado, o null si falla.
     */
    public function publishPhotoPost(string $imageUrl, string $message, ?string $link = null): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $pageToken = $this->getPageToken();
        if (!$pageToken) {
            Log::error('Facebook publish failed: no page token.');
            return null;
        }

        try {
            $response = Http::post("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/photos", [
                'url' => $imageUrl,
                'caption' => $message,
                'message' => $message,
                'link' => $link,
                'access_token' => $pageToken,
            ]);

            if (!$response->successful()) {
                Log::error('Facebook publish failed: ' . $response->body());
                return null;
            }

            return $this->recordPost($response->json('id'), $message, $link, $response->json());
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Publica una imagen ya generada (bytes, ej.: el PNG del canva de campaña
     * de AdImageController) subiendola directo a Facebook por multipart.
     * Así no depende de una URL pública ni de la consistencia de R2.
     *
     * @return string|null id del post creado, o null si falla.
     */
    public function publishPhotoContents(string $imageBytes, string $message, ?string $link = null, string $filename = 'anuncio.png'): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $pageToken = $this->getPageToken();
        if (!$pageToken) {
            Log::error('Facebook publish failed: no page token.');
            return null;
        }

        try {
            $response = Http::attach('source', $imageBytes, $filename)
                ->post("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/photos", [
                    'message' => $message,
                    'access_token' => $pageToken,
                ]);

            if (!$response->successful()) {
                Log::error('Facebook publish failed: ' . $response->body());
                return null;
            }

            return $this->recordPost($response->json('id'), $message, $link, $response->json());
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Insights de una historia de Facebook.
     *
     * La API de stories NO acepta métricas de posts (post_impressions_*):
     * hay que consultar el post_id (no el media_id) con las métricas de
     * Stories Insights: impresiones, respuestas, reacciones lightweight
     * (likes de la historia) y compartidos.
     *
     * @param string $postId post_id de la historia (columna post_id).
     */
    public function fetchStoryInsights(string $postId): array
    {
        $default = ['views' => 0, 'impressions' => 0, 'reach' => 0, 'replies' => 0, 'reactions' => 0, 'shares' => 0];

        if (!$this->isConfigured()) {
            return $default;
        }

        $pageToken = $this->getPageToken();
        if (!$pageToken) {
            return $default;
        }

        try {
            $insights = $default;

            // Una métrica por request: en lote la API devuelve error (#1).
            $map = [
                'PAGE_STORY_IMPRESSIONS_BY_STORY_ID' => 'impressions',
                'PAGE_STORY_IMPRESSIONS_BY_STORY_ID_UNIQUE' => 'reach',
                'PAGES_FB_STORY_REPLIES' => 'replies',
                'PAGES_FB_STORY_THREAD_LIGHTWEIGHT_REACTIONS' => 'reactions',
                'PAGES_FB_STORY_SHARES' => 'shares',
            ];

            foreach ($map as $metric => $field) {
                $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$postId}/insights", [
                    'metric' => $metric,
                    'access_token' => $pageToken,
                ]);

                if (!$response->successful()) {
                    Log::info("Facebook story insights '{$metric}' no disponible para {$postId}: " . $response->body());
                    continue;
                }

                foreach ($response->json('data', []) as $row) {
                    $value = $row['values'][0]['value'] ?? 0;
                    $insights[$field] += is_array($value) ? (int) array_sum($value) : (int) $value;
                }
            }

            // Vistas = alcance único (las stories no tienen "vistas" como tal).
            $insights['views'] = $insights['reach'];

            return $insights;
        } catch (\Exception $e) {
            report($e);
            return $default;
        }
    }

    /**
     * Resuelve el post_id de una historia a partir de su media_id
     * usando el listado /{page-id}/stories (las historias expiran a las 24h,
     * así que solo funciona para historias vigentes).
     */
    public function resolveStoryPostId(string $mediaId): ?string
    {
        if (!$this->isConfigured()) return null;

        $pageToken = $this->getPageToken();
        if (!$pageToken) return null;

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/stories", [
                'access_token' => $pageToken,
            ]);

            if (!$response->successful()) return null;

            foreach ($response->json('data', []) as $story) {
                if (($story['media_id'] ?? null) === $mediaId && !empty($story['post_id'])) {
                    return $story['post_id'];
                }
            }
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    /**
     * Publica una historia (story) con imagen en la página de Facebook.
     * Flujo en 2 pasos: subir la foto sin publicar y luego publicarla como
     * historia con /photo_stories. La API de historias no acepta caption ni
     * enlace, así que toda la info debe ir quemada en la imagen.
     *
     * @return array|null ['post_id' => ..., 'media_id' => ...] o null si falla.
     */
    public function publishPhotoStory(string $imageBytes, string $filename = 'historia.png'): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $pageToken = $this->getPageToken();
        if (!$pageToken) {
            Log::error('Facebook story failed: no page token.');
            return null;
        }

        try {
            $upload = Http::attach('source', $imageBytes, $filename)
                ->post("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/photos", [
                    'published' => 'false',
                    'access_token' => $pageToken,
                ]);

            if (!$upload->successful() || !$upload->json('id')) {
                Log::error('Facebook story upload failed: ' . $upload->body());
                return null;
            }

            $mediaId = $upload->json('id');

            $story = Http::post("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/photo_stories", [
                'photo_id' => $mediaId,
                'access_token' => $pageToken,
            ]);

            if (!$story->successful()) {
                Log::error('Facebook story publish failed: ' . $story->body());
                return null;
            }

            // /photo_stories devuelve post_id (para insights) además del id del medio.
            $postId = $story->json('post_id') ?? $story->json('id');

            $this->recordPost($postId ?? $mediaId, '', null, $story->json(), 'story');

            return ['post_id' => $postId, 'media_id' => $mediaId];
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    private function recordPost(?string $postId, string $message, ?string $link, mixed $raw, string $mediaType = 'photo'): ?string
    {
        if (!$postId) {
            return null;
        }

        FacebookPost::updateOrCreate(
            ['post_id' => $postId],
            [
                'message' => $message,
                'link' => $link,
                'media_type' => $mediaType,
                'posted_at' => now(),
                'likes' => 0,
                'comments' => 0,
                'shares' => 0,
                'reach' => 0,
                'impressions' => 0,
                'raw_data' => $raw,
            ]
        );

        return $postId;
    }
}
