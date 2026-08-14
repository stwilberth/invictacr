<?php

namespace App\Livewire\Admin;

use App\Models\ReviewVideo;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewVideos extends Component
{
    use WithPagination;

    public $nombre = '';

    public $uploadStatus = "";
    public $uploadMessage = "";

    public function getUploadUrl()
    {
        try {
            $accountId = config("services.cloudflare.account_id");
            $apiToken = config("services.cloudflare.api_token");

            if (!$accountId || !$apiToken) {
                return ["error" => "Faltan credenciales de Cloudflare Stream en services.cloudflare."];
            }

            $watermarkUid = config("services.cloudflare.stream_watermark_uid");

            $directUpload = Http::withHeaders([
                "Authorization" => "Bearer " . $apiToken,
            ])
                ->timeout(120)
                ->withOptions(["curl" => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                ->asJson()
                ->post("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/direct_upload", $watermarkUid ? [
                    "maxDurationSeconds" => 3600,
                    "watermark" => ["uid" => $watermarkUid],
                ] : ["maxDurationSeconds" => 3600]);

            if (!$directUpload->successful()) {
                return ["error" => "Cloudflare no generó el enlace de subida (HTTP " . $directUpload->status() . "): " . substr($directUpload->body(), 0, 200)];
            }

            $uploadURL = $directUpload->json("result.uploadURL");
            $uid = $directUpload->json("result.uid");

            if (!$uploadURL || !$uid) {
                return ["error" => "Cloudflare no devolvió un uploadURL."];
            }

            return ["uploadURL" => $uploadURL, "uid" => $uid];
        } catch (\Exception $e) {
            return ["error" => "Error al generar el enlace: " . $e->getMessage()];
        }
    }

    public function store($uid, $nombre = null)
    {
        if (!$uid) {
            $this->uploadStatus = "error";
            $this->uploadMessage = "No se recibió el identificador del video en Cloudflare.";
            return;
        }

        $maxOrden = ReviewVideo::max("orden") ?? 0;

        ReviewVideo::create([
            "stream_uid" => $uid,
            "nombre" => trim($nombre ?? "") ?: null,
            "activo" => true,
            "orden" => $maxOrden + 1,
        ]);

        $this->nombre = "";
        $this->uploadStatus = "ok";
        $this->uploadMessage = "Video subido a Cloudflare Stream correctamente.";
    }

    public function toggle($id)
    {
        $video = ReviewVideo::findOrFail($id);
        $video->update(["activo" => !$video->activo]);
    }

    public function updateNombre($id, $nombre)
    {
        $video = ReviewVideo::findOrFail($id);
        $video->update(["nombre" => trim($nombre) ?: null]);
    }

    public function moveUp($id)
    {
        $video = ReviewVideo::findOrFail($id);
        $prev = ReviewVideo::where("orden", "<", $video->orden)
            ->orderByDesc("orden")
            ->first();

        if (!$prev) return;

        $tmp = $video->orden;
        $video->update(["orden" => $prev->orden]);
        $prev->update(["orden" => $tmp]);
    }

    public function moveDown($id)
    {
        $video = ReviewVideo::findOrFail($id);
        $next = ReviewVideo::where("orden", ">", $video->orden)
            ->orderBy("orden")
            ->first();

        if (!$next) return;

        $tmp = $video->orden;
        $video->update(["orden" => $next->orden]);
        $next->update(["orden" => $tmp]);
    }

    public function delete($id)
    {
        $video = ReviewVideo::findOrFail($id);

        try {
            $accountId = config("services.cloudflare.account_id");
            $apiToken = config("services.cloudflare.api_token");

            if ($accountId && $apiToken) {
                Http::withHeaders([
                    "Authorization" => "Bearer " . $apiToken,
                ])
                    ->withOptions(["curl" => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                    ->delete("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/{$video->stream_uid}");
            }
        } catch (\Exception $e) {
            // Se borra de BD aunque falle la API
        }

        $video->delete();
    }

    public function render()
    {
        $videos = ReviewVideo::orderBy("orden")
            ->orderBy("id")
            ->paginate(20);

        $total = ReviewVideo::count();
        $activos = ReviewVideo::where("activo", true)->count();

        return view("livewire.admin.review-videos", compact("videos", "total", "activos"))
            ->layout("components.admin-layout", ["title" => "Videos de Reseñas"]);
    }
}
