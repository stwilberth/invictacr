<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Genera y guarda las escenas de video de los productos:
 * - Escena principal (video_thumb_time) siempre en el segundo 1 (minuto 0,
 *   segundo 1), nunca en 0: el frame del segundo 0 suele salir negro.
 * - 3 escenas extra repartidas por la duración real (20%, 45%, 70%),
 *   consultada a Cloudflare Stream.
 *
 * Es la misma lógica que pre-llena el formulario de editar, pero guardada
 * en lote para que la galería muestre las escenas como imágenes.
 */
class GenerateVideoScenes extends Command
{
    protected $signature = 'invicta:generate-video-scenes
        {--force : Regenerar escenas aunque ya las tengan}
        {--dry-run : Solo mostrar qué se haría}';

    protected $description = 'Guarda escena principal (segundo 1) + 3 escenas extra para productos con video sin escenas';

    public function handle(): int
    {
        $accountId = config('services.cloudflare.account_id');
        $apiToken = config('services.cloudflare.api_token');
        if (!$accountId || !$apiToken) {
            $this->error('Faltan credenciales de Cloudflare (account_id/api_token).');
            return self::FAILURE;
        }

        $force = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');

        $query = Product::whereNotNull('video_uid')->where('video_uid', '!=', '');
        if (!$force) {
            // Solo los que no tienen escena principal NI extras guardadas
            $query->where(function ($q) {
                $q->whereNull('video_thumb_time')
                  ->where(function ($q2) {
                      $q2->whereNull('video_extra_scenes')
                         ->orWhere('video_extra_scenes', '=', '[]')
                         ->orWhere('video_extra_scenes', '=', 'null');
                  });
            });
        }

        $products = $query->orderBy('id')->get();
        $this->info('Productos a procesar: ' . $products->count());
        $this->newLine();

        $stats = ['ok' => 0, 'sin_duracion' => 0, 'saltados' => 0, 'errores' => 0];
        $rows = [];

        foreach ($products as $product) {
            $uid = $product->video_uid;

            $duration = $this->fetchVideoDuration($accountId, $apiToken, $uid);

            // Escena principal: SIEMPRE segundo 1 (no 0)
            $primary = 1;

            if ($duration > 0) {
                $candidates = [
                    (int) round($duration * 0.2),
                    (int) round($duration * 0.45),
                    (int) round($duration * 0.7),
                ];
            } else {
                $candidates = [2, 5, 10];
                $stats['sin_duracion']++;
            }

            $extras = [];
            foreach ($candidates as $s) {
                $s = max(1, $s);
                if ($s !== $primary && !in_array($s, $extras, true)) {
                    $extras[] = $s;
                }
            }

            if (!$dryRun) {
                $product->update([
                    'video_thumb_time' => $primary,
                    'video_extra_scenes' => $extras,
                ]);
            }

            $stats['ok']++;
            $rows[] = [
                $product->id,
                $product->modelo,
                substr((string) $uid, 0, 8) . '...',
                (string) round($duration) . 's',
                "principal {$primary}s",
                implode(', ', $extras) ?: '-',
            ];
        }

        $this->table(['ID', 'Modelo', 'Video', 'Duración', 'Principal', 'Extras'], $rows);
        $this->newLine();

        foreach ($stats as $k => $v) {
            $this->line(str_pad($k, 14) . ': ' . $v);
        }

        if ($dryRun) {
            $this->warn('DRY-RUN: nada se guardó. Quitar --dry-run para aplicar.');
        }

        return self::SUCCESS;
    }

    private function fetchVideoDuration(string $accountId, string $apiToken, string $uid): float
    {
        try {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
                ->timeout(10)
                ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                ->get("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/{$uid}");
            if (!$response->successful()) {
                return 0;
            }
            return (float) ($response->json('result.duration', 0));
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
