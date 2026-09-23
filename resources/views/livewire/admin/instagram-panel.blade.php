<div>
    <div class="flex items-center justify-between gap-2 mb-4">
        <h2 class="text-xl font-black flex items-center gap-2">
            <i class="fa-brands fa-instagram text-pink-500"></i> Instagram
            @if ($cachedAt)
                <span class="text-[11px] font-normal text-gray-400">· datos de {{ $cachedAt }}</span>
            @endif
        </h2>
        <div class="flex gap-2">
            @if (!$insightsLoaded && !empty($media))
                <button wire:click="loadInsights" wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-[#00C4FF]/50 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-eye" wire:loading.class="fa-spin"></i>
                    <span wire:loading.remove>Cargar alcance</span><span wire:loading>Cargando…</span>
                </button>
            @endif
            <button wire:click="refresh" wire:loading.attr="disabled"
                class="px-4 py-2 rounded-xl text-xs font-bold bg-[#00C4FF] text-[#0a0f1c] hover:brightness-110 transition-all flex items-center gap-2">
                <i class="fa-solid fa-rotate" wire:loading.class="fa-spin"></i> Actualizar
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm font-bold">
            {{ session('message') }}
        </div>
    @endif

    @if (!$configured || $error)
        <div class="p-4 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm font-bold">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ $error ?? 'Instagram no configurado.' }}
        </div>
    @else
        {{-- PERFIL --}}
        @if ($profile)
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-4 mb-4 flex items-center gap-4">
                @if (!empty($profile['profile_picture_url']))
                    <img src="{{ $profile['profile_picture_url'] }}" alt="@{{ $profile['username'] ?? '' }}"
                        class="w-16 h-16 rounded-full object-cover border-2 border-pink-500 shrink-0" />
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 flex items-center justify-center shrink-0">
                        <i class="fa-brands fa-instagram text-white text-2xl"></i>
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="font-black text-base truncate">@{{ $profile['username'] ?? '—' }}</p>
                    <div class="flex gap-4 mt-1 text-sm">
                        <span><strong>{{ number_format($profile['followers_count'] ?? 0) }}</strong> <span class="text-gray-400 text-xs">seguidores</span></span>
                        <span><strong>{{ number_format($profile['media_count'] ?? 0) }}</strong> <span class="text-gray-400 text-xs">posts</span></span>
                    </div>
                </div>
            </div>
        @endif

        {{-- POSTS RECIENTES --}}
        <h3 class="font-black text-sm mb-3 text-gray-500 dark:text-gray-400 uppercase tracking-wider">Publicaciones recientes</h3>
        @if (empty($media))
            <p class="text-sm text-gray-400">Sin publicaciones recientes.</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                @foreach ($media as $item)
                    <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 overflow-hidden">
                        @if (!empty($item['thumb']))
                            <a href="{{ $item['permalink'] ?? '#' }}" target="_blank" rel="noopener">
                                <img src="{{ $item['thumb'] }}" alt="{{ $item['short_caption'] }}" loading="lazy"
                                    class="w-full aspect-square object-cover hover:opacity-90 transition-opacity" />
                            </a>
                        @endif
                        <div class="p-2.5">
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                                <i class="fa-solid {{ ($item['media_type'] ?? '') === 'VIDEO' ? 'fa-clapperboard' : 'fa-image' }} mr-1"></i>{{ $item['short_caption'] ?: 'Sin texto' }}
                            </p>
                            <div class="flex items-center gap-3 mt-1.5 text-[11px] font-bold">
                                <span class="text-pink-500"><i class="fa-solid fa-heart"></i> {{ number_format($item['like_count'] ?? 0) }}</span>
                                <span class="text-[#00C4FF]"><i class="fa-solid fa-comment"></i> {{ number_format($item['comments_count'] ?? 0) }}</span>
                                <span class="text-gray-400"><i class="fa-solid fa-eye"></i> {{ number_format($item['insights']['reach'] ?? 0) }}</span>
                            </div>
                            @if (!empty($item['timestamp']))
                                <p class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($item['timestamp'])->format('d/m/Y') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="text-[11px] text-gray-400 mt-3">Alcance por post (ojo) requiere permiso <code>instagram_manage_insights</code>. Toca Actualizar para refrescar (caché 30 min).</p>
        @endif
    @endif
</div>
