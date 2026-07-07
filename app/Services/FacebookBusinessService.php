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

    public function fetchPageInsights(\DateTime $date): ?FacebookInsight
    {
        if (!$this->isConfigured()) return null;

        $dateStr = $date->format('Y-m-d');
        $since = $date->format('Y-m-d');
        $until = $date->format('Y-m-d');

        try {
            $metrics = 'page_impressions,page_engaged_users,page_fans,page_actions_post_reactions_like_total,page_actions_post_reactions_comment_total,page_actions_post_reactions_share_total,page_views_total';

            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/insights", [
                'metric' => $metrics,
                'period' => 'day',
                'since' => $since,
                'until' => $until,
                'access_token' => $this->accessToken,
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
                    'page_impressions' => $insights['page_impressions'] ?? 0,
                    'page_engaged_users' => $insights['page_engaged_users'] ?? 0,
                    'page_follows' => $insights['page_fans'] ?? 0,
                    'page_reactions' => $insights['page_actions_post_reactions_like_total'] ?? 0,
                    'page_comments' => $insights['page_actions_post_reactions_comment_total'] ?? 0,
                    'page_shares' => $insights['page_actions_post_reactions_share_total'] ?? 0,
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

        $count = 0;

        try {
            $response = Http::get("https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/posts", [
                'fields' => 'id,message,link,permalink_url,created_time,shares,reactions.limit(0).summary(true),comments.limit(0).summary(true),insights.metric(post_impressions,post_reach_by_action_type)',
                'since' => $since->format('Y-m-d'),
                'limit' => $limit,
                'access_token' => $this->accessToken,
            ]);

            if (!$response->successful()) return 0;

            $posts = $response->json('data', []);

            foreach ($posts as $post) {
                $reactions = $post['reactions']['summary']['total_count'] ?? 0;
                $comments = $post['comments']['summary']['total_count'] ?? 0;
                $shares = $post['shares']['count'] ?? 0;

                $insights = $post['insights']['data'] ?? [];
                $impressions = 0;
                $reach = 0;

                foreach ($insights as $i) {
                    if ($i['name'] === 'post_impressions') {
                        $impressions = $i['values'][0]['value'] ?? 0;
                    }
                    if ($i['name'] === 'post_reach_by_action_type') {
                        $reach = array_sum($i['values'][0]['value'] ?? []);
                    }
                }

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
                        'reach' => $reach,
                        'impressions' => $impressions,
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
