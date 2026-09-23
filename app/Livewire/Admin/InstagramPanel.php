<?php

namespace App\Livewire\Admin;

use App\Services\InstagramService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class InstagramPanel extends Component
{
    public $profile = null;
    public $media = [];
    public $configured = false;
    public $error = null;
    public $cachedAt = null;
    public $insightsLoaded = false;

    public function mount()
    {
        $this->loadData(false);
    }

    public function refresh()
    {
        Cache::forget('ig_panel_data');
        $this->loadData(false);
        session()->flash('message', 'Datos de Instagram actualizados.');
    }

    private function loadData(bool $force): void
    {
        $service = app(InstagramService::class);
        $this->configured = $service->isConfigured();

        if (!$this->configured) {
            $this->error = 'Instagram no está configurado. Revisá META_ACCESS_TOKEN e IG_ACCOUNT_ID.';
            return;
        }

        try {
            $data = $force
                ? $this->fetchFresh($service)
                : Cache::remember('ig_panel_data', now()->addMinutes(30), fn () => $this->fetchFresh($service));

            $this->profile = $data['profile'];
            $this->media = $data['media'];
            $this->cachedAt = $data['cached_at'];
            $this->insightsLoaded = (bool) ($data['insights_loaded'] ?? false);
            $this->error = $this->profile ? null : 'No se pudo leer la cuenta de Instagram. Revisá el token y los permisos.';
        } catch (\Throwable $e) {
            report($e);
            $this->error = 'Error al consultar Instagram: ' . $e->getMessage();
        }
    }

    private function fetchFresh(InstagramService $service): array
    {
        // Rápido: perfil + lista. Los insights (72 requests) van aparte
        // con el botón "Cargar alcance" para no colgar la página.
        $profile = $service->fetchProfile();
        $media = $service->fetchRecentMedia(12);

        foreach ($media as &$item) {
            $item['insights'] = ['reach' => 0, 'views' => 0, 'likes' => 0, 'comments' => 0, 'saves' => 0, 'shares' => 0];
            // Miniatura para videos/reels, imagen directa para fotos/carouseles.
            $item['thumb'] = $item['thumbnail_url'] ?? $item['media_url'] ?? null;
            $item['short_caption'] = mb_substr((string) ($item['caption'] ?? ''), 0, 90);
        }
        unset($item);

        return [
            'profile' => $profile,
            'media' => $media,
            'cached_at' => now()->format('d/m H:i'),
            'insights_loaded' => false,
        ];
    }

    public function loadInsights()
    {
        $service = app(InstagramService::class);

        foreach ($this->media as &$item) {
            $item['insights'] = $service->fetchMediaInsights($item['id']);
        }
        unset($item);

        $this->insightsLoaded = true;
        Cache::put('ig_panel_data', [
            'profile' => $this->profile,
            'media' => $this->media,
            'cached_at' => $this->cachedAt,
            'insights_loaded' => true,
        ], now()->addMinutes(30));
    }

    public function render()
    {
        return view('livewire.admin.instagram-panel')
            ->layout('components.admin-layout', ['title' => 'Instagram']);
    }
}
