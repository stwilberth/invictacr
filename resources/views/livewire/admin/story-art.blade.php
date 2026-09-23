<div>
    <h2 class="text-xl font-black mb-1">Artes para Estados e Historias</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Descargá el arte vertical del día y subilo a tu estado de WhatsApp en segundos.</p>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totals->total }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Total historias</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $totals->facebook }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Facebook</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-pink-600 dark:text-pink-400">{{ $totals->instagram }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Instagram</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($totals->views) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Vistas totales</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ number_format($totals->reactions) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Reacciones totales</p>
        </div>
    </div>

    <div class="flex items-center gap-3 mb-4 flex-wrap">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Canal:</span>
            <select wire:model.live="channelFilter" class="text-xs font-bold bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2 text-gray-900 dark:text-white">
                <option value="">Todos</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Ordenar:</span>
            <select wire:model.live="sortBy" class="text-xs font-bold bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2 text-gray-900 dark:text-white">
                <option value="latest">Más recientes</option>
                <option value="views">Más vistas</option>
            </select>
        </div>
    </div>

    @if($stories->isEmpty())
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
            Todavía no hay historias publicadas. Aparecen aquí automáticamente después de cada publicación.
        </div>
    @else
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3 mb-6">
            @foreach($stories as $story)
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
                @if($story->product)
                    <img src="{{ route('admin.historias.png', $story->id) }}" alt="Historia {{ $story->model_code }}" class="w-full h-auto block" loading="lazy" />
                @else
                    <div class="w-full aspect-[9/16] max-h-96 flex items-center justify-center bg-gray-100 dark:bg-white/5 text-xs text-gray-400 p-4 text-center">
                        Producto eliminado ({{ $story->model_code }})
                    </div>
                @endif
                <div class="p-3">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="font-black text-sm text-gray-900 dark:text-white">{{ $story->model_code }}</span>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full {{ $story->channel === 'instagram' ? 'bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' }}">{{ $story->channel === 'instagram' ? 'IG' : 'FB' }}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $story->created_at->format('d/m/Y H:i') }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-3 flex-wrap">
                        <span class="flex items-center gap-1" title="Vistas"><i class="fa-solid fa-eye text-[10px]"></i> {{ number_format($story->views) }}</span>
                        <span class="flex items-center gap-1" title="Reacciones"><i class="fa-solid fa-heart text-[10px]"></i> {{ number_format($story->reactions ?? 0) }}</span>
                        <span class="flex items-center gap-1" title="Respuestas"><i class="fa-solid fa-reply text-[10px]"></i> {{ number_format($story->replies) }}</span>
                        @if(($story->shares ?? 0) > 0)
                            <span class="flex items-center gap-1" title="Compartidos"><i class="fa-solid fa-share text-[10px]"></i> {{ number_format($story->shares) }}</span>
                        @endif
                    </div>
                    @if($story->product)
                        <a href="{{ route('admin.historias.png', $story->id) }}" download class="block text-center bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-4 py-2.5 rounded-xl text-sm transition-all no-underline">
                            <i class="fa-solid fa-download mr-1"></i> Descargar
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div>{{ $stories->links() }}</div>
    @endif
</div>
