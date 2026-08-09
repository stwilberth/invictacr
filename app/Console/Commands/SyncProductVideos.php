<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncProductVideos extends Command
{
    protected $signature = 'invicta:sync-product-videos {--replace : Re-subir y reemplazar videos existentes} {--brand-id=67 : Solo sincronizar esta marca_id (0 = todas)} {--dry-run : Solo mostrar qué se haría}';

    protected $description = 'Copia los videos de producto de variedadescr (R2/CDN) a Cloudflare Stream con marca de agua y los asigna por modelo';

    public function handle(): int
    {
        $accountId = config('services.cloudflare.account_id');
        $apiToken = config('services.cloudflare.api_token');
        $watermarkUid = config('services.cloudflare.stream_watermark_uid');
        $cdn = config('services.variedadescr.cdn_url');
        $replace = (bool) $this->option('replace');
        $dryRun = (bool) $this->option('dry-run');
        $brandId = (int) $this->option('brand-id');

        if (!$accountId || !$apiToken || !$watermarkUid) {
            $this->error('Faltan credenciales de Cloudflare Stream (account_id/api_token/watermark_uid).');
            return self::FAILURE;
        }

        $videos = DB::connection('variedadescr')
            ->table('video_productos as v')
            ->leftJoin('productos as p', 'p.id', '=', 'v.producto_id')
            ->leftJoin('marcas as mc', 'mc.id', '=', 'p.marca_id')
            ->select('v.id', 'v.nombre_archivo', 'v.ruta', 'p.modelo', 'p.marca_id', 'mc.nombre as marca')
            ->orderBy('v.id')
            ->get();

        $this->info("Videos en variedadescr: " . $videos->count() . ($brandId ? " (solo marca_id: {$brandId})" : ''));
        $this->newLine();

        $stats = ['subidos' => 0, 'reemplazados' => 0, 'ya_tienen' => 0, 'otra_marca' => 0, 'sin_match' => 0, 'errores' => 0];
        $rows = [];

        foreach ($videos as $video) {
            $marca = trim($video->marca ?? '');
            $modelo = trim($video->modelo ?? pathinfo($video->nombre_archivo, PATHINFO_FILENAME));

            if ($brandId && (int) $video->marca_id !== $brandId) {
                $stats['otra_marca']++;
                $rows[] = [$video->id, $modelo, 'OTRA MARCA', '-', $marca ?: 'sin marca'];
                continue;
            }

            $product = Product::where('modelo', $modelo)->first();

            if (!$product) {
                $stats['sin_match']++;
                $rows[] = [$video->id, $modelo, 'SIN MATCH', '-', 'no existe en invictacr'];
                continue;
            }

            if ($product->video_uid && !$replace) {
                $stats['ya_tienen']++;
                $rows[] = [$video->id, $modelo, 'YA TIENE', $product->video_uid, 'usar --replace para re-subir'];
                continue;
            }

            $sourceUrl = $cdn . '/' . $video->ruta;

            if ($dryRun) {
                $rows[] = [$video->id, $modelo, 'DRY-RUN', $product->video_uid ?? '-', $sourceUrl];
                continue;
            }

            $this->line("  Subiendo {$modelo} -> {$sourceUrl} ...");

            try {
                $response = Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
                    ->timeout(120)
                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                    ->asJson()
                    ->post("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/copy", [
                        'input' => $sourceUrl,
                        'name' => $modelo,
                        'maxDurationSeconds' => 3600,
                        'requireSignedURLs' => false,
                        'creator' => 'invictacr-sync',
                        'watermark' => ['uid' => $watermarkUid],
                        'meta' => [
                            'proyecto' => 'invictacr',
                            'tipo' => 'producto',
                            'modelo' => $modelo,
                        ],
                    ]);

                if (!$response->successful()) {
                    $stats['errores']++;
                    $rows[] = [$video->id, $modelo, 'ERROR', '-', 'HTTP ' . $response->status() . ': ' . substr($response->body(), 0, 120)];
                    continue;
                }

                $data = $response->json();
                if (empty($data['success']) || empty($data['result']['uid'])) {
                    $stats['errores']++;
                    $rows[] = [$video->id, $modelo, 'ERROR', '-', $data['errors'][0]['message'] ?? 'sin uid'];
                    continue;
                }

                $newUid = $data['result']['uid'];
                $oldUid = $product->video_uid;
                $product->update(['video_uid' => $newUid]);

                if ($oldUid && $oldUid !== $newUid) {
                    $this->deleteStreamVideo($accountId, $apiToken, $oldUid);
                    $stats['reemplazados']++;
                    $rows[] = [$video->id, $modelo, 'REEMPLAZADO', $newUid, 'old ' . $oldUid];
                } else {
                    $stats['subidos']++;
                    $rows[] = [$video->id, $modelo, 'SUBIDO', $newUid, $sourceUrl];
                }

                usleep(1200000);
            } catch (\Throwable $e) {
                $stats['errores']++;
                $rows[] = [$video->id, $modelo, 'ERROR', '-', $e->getMessage()];
            }
        }

        $this->newLine();
        $this->table(['ID', 'Modelo', 'Estado', 'Uid', 'Detalle'], $rows);
        $this->newLine();

        foreach ($stats as $k => $v) {
            $this->line(str_pad($k, 15) . ': ' . $v);
        }

        return self::SUCCESS;
    }

    private function deleteStreamVideo(string $accountId, string $apiToken, string $uid): void
    {
        try {
            Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
                ->timeout(60)
                ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                ->delete("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/{$uid}");
        } catch (\Throwable $e) {
            $this->warn("  No se pudo borrar el video viejo {$uid}: " . $e->getMessage());
        }
    }
}
