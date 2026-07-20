<?php

namespace App\Services;

use App\Models\AiTimelineInsight;
use Illuminate\Support\Facades\Http;

class TimelineAiService
{
    protected array $groupedEvents;
    protected array $dailyRevenue;
    protected string $periodType;

    public function __construct(array $groupedEvents, array $dailyRevenue, string $periodType)
    {
        $this->groupedEvents = $groupedEvents;
        $this->dailyRevenue = $dailyRevenue;
        $this->periodType = $periodType;
    }

    public function generateAll(): int
    {
        $existing = AiTimelineInsight::whereIn('period_key', array_keys($this->groupedEvents))->count();
        $hasGlobal = AiTimelineInsight::where('period_key', 'global')->exists();
        if ($existing >= count($this->groupedEvents) && $hasGlobal) {
            return 0;
        }

        $json = $this->callAi();
        if (!$json) return 0;

        $count = 0;

        if (isset($json['periods']) && is_array($json['periods'])) {
            foreach ($json['periods'] as $key => $conclusion) {
                if (!isset($this->groupedEvents[$key])) continue;
                AiTimelineInsight::updateOrCreate(
                    ['period_key' => $key],
                    [
                        'period_label' => $this->groupedEvents[$key]['label'],
                        'conclusion' => $conclusion,
                        'is_final' => false,
                        'generated_at' => now(),
                    ]
                );
                $count++;
            }
        }

        if (!$hasGlobal) {
            AiTimelineInsight::updateOrCreate(
                ['period_key' => 'global'],
                [
                    'period_label' => 'Análisis General',
                    'conclusion' => $json['global_conclusion'] ?? '',
                    'advice' => $json['advice'] ?? '',
                    'is_final' => true,
                    'generated_at' => now(),
                ]
            );
            $count++;
        }

        return $count;
    }

    protected function buildSummary(): string
    {
        $lines = [];
        $lines[] = "Resumen de " . count($this->groupedEvents) . " períodos (" . $this->periodType . "s):";
        $lines[] = "";

        foreach ($this->groupedEvents as $key => $group) {
            $lines[] = "--- {$group['label']} (key: {$key}) ---";
            $lines[] = "Ingresos: ₡" . number_format($group['revenue']);
            $lines[] = "Días con datos: " . count($group['days']);
            $lines[] = "Eventos:";

            $bySource = [];
            foreach ($group['events'] as $e) {
                $bySource[$e['source']][] = $e;
            }

            foreach ($bySource as $src => $evts) {
                $lines[] = "  {$src} (" . count($evts) . ")";
                foreach (array_slice($evts, 0, 3) as $e) {
                    $lines[] = "    - {$e['title']}";
                }
                if (count($evts) > 3) {
                    $lines[] = "    ... y " . (count($evts) - 3) . " más";
                }
            }
            $lines[] = "";
        }

        return implode("\n", $lines);
    }

    protected function callAi(): ?array
    {
        $apiKey = config('services.anthropic.key');
        $model = config('services.anthropic.model', 'claude-haiku-4-5');
        $timeout = (int) config('services.anthropic.timeout', 30);
        if (!$apiKey) return null;

        $summary = $this->buildSummary();
        $periodKeys = json_encode(array_keys($this->groupedEvents));

        $systemPrompt = "Eres un analista de negocios experto en e-commerce (relojería Invicta Costa Rica).";
        $systemPrompt .= " Devuelves SOLO un JSON válido sin markdown ni texto adicional.";

        $userPrompt = "Analiza estos datos de varios períodos y devuelve un JSON con:
1. \"periods\": objeto donde cada key es el period_key y el valor es una conclusión corta (máx 3 oraciones) explicando qué afectó los ingresos en ese período.
2. \"global_conclusion\": análisis general de todos los períodos (máx 5 oraciones).
3. \"advice\": consejo estratégico futuro accionable (máx 5 oraciones).

Períodos a analizar (keys): {$periodKeys}

Datos:
{$summary}

Responde SOLO con el JSON.";

        try {
            $response = Http::timeout($timeout)->withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => 1000,
                'temperature' => 0.2,
                'system' => $systemPrompt,
                'messages' => [
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

            $data = $response->json();
            $text = $data['content'][0]['text'] ?? null;
            if (!$text) return null;

            $text = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($text));

            $decoded = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) return null;

            return $decoded;
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }
}
