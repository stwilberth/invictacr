<?php

namespace App\Services;

use App\Models\FacebookInsight;
use App\Models\FacebookPost;
use Illuminate\Support\Facades\Http;

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
}
