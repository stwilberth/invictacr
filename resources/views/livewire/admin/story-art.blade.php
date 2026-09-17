<div>
    <h2 class="text-xl font-black mb-1">Artes para Estados e Historias</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Descargá el arte vertical del día y subilo a tu estado de WhatsApp en segundos.</p>

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
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $story->created_at->format('d/m/Y H:i') }}</p>
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
