<?php

namespace App\Console\Commands;

use App\Models\StoryHistory;
use App\Services\FacebookBusinessService;
use App\Services\InstagramService;
use Illuminate\Console\Command;

class FetchStoryInsights extends Command
{
    protected $signature = 'campaigns:fetch-story-insights {--hours=24 : Insights de las últimas N horas}';

    protected $description = 'Obtiene vistas/alcance de historias publicadas en Facebook e Instagram';

    public function handle(FacebookBusinessService $fb, InstagramService $ig): int
    {
        $since = now()->subHours((int) $this->option('hours'));

        $stories = StoryHistory::where('created_at', '>=', $since)
            ->whereNotNull('story_id')
            ->get();

        if ($stories->isEmpty()) {
            $this->info('No hay historias recientes para consultar.');
            return Command::SUCCESS;
        }

        $this->info("Consultando insights de {$stories->count()} historias...");
        $updated = 0;

        foreach ($stories as $story) {
            $service = $story->channel === 'instagram' ? $ig : $fb;

            if (!$service->isConfigured()) {
                $this->warn("{$story->channel} no está configurado, saltando {$story->model_code}.");
                continue;
            }

            $insights = $service->fetchStoryInsights($story->story_id);

            $story->update([
                'views' => $insights['views'],
                'impressions' => $insights['impressions'],
                'reach' => $insights['reach'],
                'replies' => $insights['replies'],
            ]);

            if ($insights['views'] === 0 && $insights['impressions'] === 0) {
                $this->warn("  {$story->model_code} ({$story->channel}): sin datos de insights aún");
            } else {
                $this->line("  {$story->model_code} ({$story->channel}): {$insights['views']} vistas, {$insights['impressions']} impresiones");
            }
            $updated++;
        }

        $this->info("Listo: {$updated} historias actualizadas.");
        return Command::SUCCESS;
    }
}