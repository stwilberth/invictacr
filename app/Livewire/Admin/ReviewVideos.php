<?php

namespace App\Livewire\Admin;

use App\Models\ReviewVideo;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ReviewVideos extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $nombre = '';
    public $archivo;

    public $uploadStatus = "";
    public $uploadMessage = "";

    public function save()
    {
        $this->uploadStatus = "";
        $this->uploadMessage = "";

        $this->validate([
            "archivo" => "required|file|mimetypes:video/mp4,video/quicktime,video/webm,video/x-m4v,video/3gpp|max:51200",
        ], [
            "archivo.required" => "Selecciona un archivo de video.",
            "archivo.mimetypes" => "El archivo debe ser un video (MP4, MOV, WEBM, M4V).",
            "archivo.max" => "El video no debe superar 50MB.",
        ]);

        $tempPath = $this->archivo->getRealPath();
        $originalName = $this->archivo->getClientOriginalName();

        try {
            $accountId = config("services.cloudflare.account_id");
            $apiToken = config("services.cloudflare.api_token");

            if (!$accountId || !$apiToken) {
                $this->uploadStatus = "error";
                $this->uploadMessage = "Faltan credenciales de Cloudflare Stream en services.cloudflare.";
                return;
            }

            $watermarkUid = config("services.cloudflare.stream_watermark_uid");

            $directUpload = Http::withHeaders([
                "Authorization" => "Bearer " . $apiToken,
            ])
                ->timeout(120)
                ->asJson()
                ->post("https://api.cloudflare.com/client/v4/accounts/{$accountId}/stream/direct_upload", $watermarkUid ? [
                    "watermark" => ["uid" => $watermarkUid],
                ] : []);

            if (!$directUpload->successful()) {
                $this->uploadStatus = "error";
                $this->uploadMessage = "Cloudflare no generó el enlace de subida (HTTP " . $directUpload->status() . "): " . substr($directUpload->body(), 0, 200);
                return;
            }

            $uploadURL = $directUpload->json("result.uploadURL");
            if (!$uploadURL) {
                $this->uploadStatus = "error";
                $this->uploadMessage = "Cloudflare no devolvió un uploadURL.";
                return;
            }

            $upload = Http::attach("file", fopen($tempPath, "r"), $originalName)
                ->timeout(600)
                ->post($uploadURL);

            if (!$upload->successful()) {
                $this->uploadStatus = "error";
                $this->uploadMessage = "Error al subir el archivo (HTTP " . $upload->status() . "): " . substr($upload->body(), 0, 200);
                return;
            }

            $uid = $upload->json("result.uid");
            if (!$uid) {
                $this->uploadStatus = "error";
                $this->uploadMessage = "Cloudflare no devolvió un uid.";
                return;
            }

            $maxOrden = ReviewVideo::max("orden") ?? 0;

            ReviewVideo::create([
                "stream_uid" => $uid,
                "nombre" => trim($this->nombre) ?: null,
                "activo" => true,
                "orden" => $maxOrden + 1,
            ]);

            $this->archivo = null;
            $this->nombre = "";
            $this->uploadStatus = "ok";
            $this->uploadMessage = "Video subido a Cloudflare Stream correctamente.";
        } catch (\Exception $e) {
            $this->uploadStatus = "error";
            $this->uploadMessage = "Error al subir: " . $e->getMessage();
        }
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
