<?php

namespace App\Console\Commands;

use App\Models\ReviewVideo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CopyResenasToStream extends Command
{
    protected $signature = 'resenas:copy-to-stream {--dry-run : Solo listar sin copiar} {--force : Re-copiar aunque ya exista en BD (borra el video Stream anterior y aplica watermark)}';
    protected $description = 'Copia los videos de R2 resennas/ a Cloudflare Stream y los registra en review_videos (R2 queda intacto)';

    public function handle(): int
    {
        $accountId = config('services.cloudflare.account_id');
        $apiToken = config('services.cloudflare.api_token');

        if (!$accountId || !$apiToken) {
            $this->error('Faltan credenciales de Cloudflare Stream.');
            return 1;
        }

        try {
            $files = Storage::disk('r2')->files('resennas');
        } catch (\Exception $e) {
            $this->error('No se pudo listar R2: ' . $e->getMessage());
            return 1;
        }

        $mp4 = array_values(array_filter($files, fn($f) => str_ends_with(strtolower($f), '.mp4')));
        sort($mp4, SORT_NATURAL | SORT_FLAG_CASE);

        if (empty($mp4)) {
            $this->warn('No hay mp4 en R2 resennas/.');
            return 0;
        }

        $this->info(count($mp4) . ' videos en R2.');

        if ($this->option('dry-run')) {
            foreach ($mp4 as $f) {
                $this->line(' - ' . $f);
            }
            return 0;
        }

        $maxOrden = ReviewVideo::max('orden') ?? 0;
        $copiados = 0;
        $omitidos = 0;
        $errores = 0;

        foreach ($mp4 as $path) {
            $nombre = pathinfo($path, PATHINFO_FILENAME);

            $existente = ReviewVideo::where('nombre', $nombre)->first();

            if ($existente && !$this->option('force')) {
                $this->line("Omitido (ya existe): {$nombre}");
                $omitidos++;
                continue;
            }

            $encoded = implode('/', array_map('rawurlencode', explode('/', $path)));
            $url = "https://cdn.invictacostarica.com/{$encoded}";

            $payload = ['url' => $url, 'meta' => ['name' => $nombre]];
            $watermarkUid = config('services.cloudflare.stream_watermark_uid');
            if ($watermarkUid) {
                $payload['watermark'] = ['uid' => $watermarkUid];
            }

            try {
                $resp = Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
                    ->timeout(60)
                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                    ->asJson()
                    ->post(
                        "https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/copy",
                        $payload
                    );
            } catch (\Exception $e) {
                $this->error("Error red {$nombre}: " . $e->getMessage());
                $errores++;
                continue;
            }

            if (!$resp->successful() || !($resp->json('success') ?? false)) {
                $this->error("Error API {$nombre}: HTTP " . $resp->status() . ' ' . substr($resp->body(), 0, 150));
                $errores++;
                continue;
            }

            $uid = $resp->json('result.uid');
            if (!$uid) {
                $this->error("Sin uid {$nombre}");
                $errores++;
                continue;
            }

            $maxOrden++;
            if ($existente) {
                try {
                    Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
                        ->timeout(30)
                        ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                        ->delete("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/{$existente->stream_uid}");
                } catch (\Exception $e) {
                    // Se reemplaza el registro aunque falle el borrado anterior
                }
                $existente->update(['stream_uid' => $uid, 'activo' => true]);
            } else {
                ReviewVideo::create([
                    'stream_uid' => $uid,
                    'nombre' => $nombre,
                    'activo' => true,
                    'orden' => $maxOrden,
                ]);
            }

            $this->info("Copiado: {$nombre} -> {$uid}");
            $copiados++;
            sleep(1);
        }

        $this->info("Listo. Copiados: {$copiados}, omitidos: {$omitidos}, errores: {$errores}.");

        return $errores > 0 ? 1 : 0;
    }
}
