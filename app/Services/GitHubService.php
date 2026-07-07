<?php

namespace App\Services;

use App\Models\GitHubCommit;
use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected string $token;
    protected string $repo;
    protected string $owner;

    public function __construct()
    {
        $this->token = config('services.github.token');
        $this->repo = config('services.github.repo');
        $this->owner = config('services.github.owner');
    }

    public function isConfigured(): bool
    {
        return !empty($this->token) && !empty($this->repo) && !empty($this->owner);
    }

    public function fetchCommits(string $branch = 'main', int $limit = 50): int
    {
        if (!$this->isConfigured()) return 0;

        $count = 0;

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Accept' => 'application/vnd.github.v3+json',
            ])->get("https://api.github.com/repos/{$this->owner}/{$this->repo}/commits", [
                'sha' => $branch,
                'per_page' => $limit,
            ]);

            if (!$response->successful()) return 0;

            $commits = $response->json();

            foreach ($commits as $commit) {
                $sha = $commit['sha'] ?? '';

                // Get detailed commit stats
                $detail = Http::withHeaders([
                    'Authorization' => "Bearer {$this->token}",
                    'Accept' => 'application/vnd.github.v3+json',
                ])->get("https://api.github.com/repos/{$this->owner}/{$this->repo}/commits/{$sha}");

                $stats = $detail->successful() ? $detail->json() : [];
                $files = $stats['files'] ?? [];

                $filesSummary = collect($files)->map(fn($f) => [
                    'filename' => $f['filename'] ?? '',
                    'status' => $f['status'] ?? '',
                    'additions' => $f['additions'] ?? 0,
                    'deletions' => $f['deletions'] ?? 0,
                ]);

                GitHubCommit::updateOrCreate(
                    ['sha' => $sha],
                    [
                        'message' => $commit['commit']['message'] ?? '',
                        'author_name' => $commit['commit']['author']['name'] ?? '',
                        'author_email' => $commit['commit']['author']['email'] ?? '',
                        'branch' => $branch,
                        'repository' => "{$this->owner}/{$this->repo}",
                        'committed_at' => $commit['commit']['author']['date'] ?? null,
                        'additions' => $stats['stats']['additions'] ?? 0,
                        'deletions' => $stats['stats']['deletions'] ?? 0,
                        'files_changed' => $stats['stats']['total'] ?? count($files),
                        'files_summary' => $filesSummary->toJson(),
                        'raw_data' => $commit,
                    ]
                );

                $count++;
            }
        } catch (\Exception $e) {
            report($e);
        }

        return $count;
    }

    public function getDeployMarkers(): array
    {
        return GitHubCommit::where('message', 'like', '%deploy%')
            ->orWhere('message', 'like', '%release%')
            ->orWhere('message', 'like', '%production%')
            ->latest('committed_at')
            ->take(10)
            ->get()
            ->toArray();
    }
}
