<div>
    {{-- Stats cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-[#00C4FF]">{{ $total }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total con imagen</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-green-500">{{ $optimized }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Optimizados</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-amber-500">{{ $unoptimized }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pendientes</p>
        </div>
    </div>

    {{-- Action card --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Optimizar imágenes</h2>
                <p class="text-xs text-gray-400 mt-1">
                    Genera versiones WebP en 3 tamaños: original, mediano (600px) y miniatura (200px)
                </p>
            </div>
            <button
                wire:click="optimizeAll"
                wire:loading.attr="disabled"
                wire:target="optimizeAll"
                class="bg-[#00C4FF] hover:bg-[#00b0e6] disabled:opacity-50 disabled:cursor-not-allowed text-[#0a0f1c] font-black px-6 py-3 rounded-xl text-sm transition-all flex items-center gap-2"
            >
                <i wire:loading.remove wire:target="optimizeAll" class="fa-solid fa-wand-magic-sparkles"></i>
                <i wire:loading wire:target="optimizeAll" class="fa-solid fa-spinner fa-spin"></i>
                <span wire:loading.remove wire:target="optimizeAll">Optimizar todas</span>
                <span wire:loading wire:target="optimizeAll">Optimizando...</span>
            </button>
        </div>

        @if($optimizing)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30 rounded-xl p-4">
            <div class="flex items-center gap-3 mb-2">
                <i class="fa-solid fa-spinner fa-spin text-blue-500"></i>
                <span class="text-sm font-bold text-blue-700 dark:text-blue-300">
                    Procesando... {{ $processed }} / {{ $total + $unoptimized }}
                </span>
            </div>
            @if($currentModelo)
            <p class="text-xs text-blue-600 dark:text-blue-400 ml-7">
                Actual: {{ $currentModelo }}
                ({{ $successCount }} OK, {{ $failCount }} fallos)
            </p>
            @endif
            <div class="mt-3 w-full bg-blue-200 dark:bg-blue-900/50 rounded-full h-2 overflow-hidden">
                @php
                    $totalToProcess = max($total, 1);
                    $percent = min(100, round(($processed / $totalToProcess) * 100));
                @endphp
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
            </div>
        </div>
        @endif

        @if($lastResult)
        <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm font-bold mt-4">
            <i class="fa-solid fa-circle-check"></i> {{ $lastResult }}
        </div>
        @endif

        @if($lastError)
        <div class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-bold mt-4">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $lastError }}
        </div>
        @endif
    </div>

    {{-- Unoptimized products list --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">
            Productos pendientes de optimizar
            @if($unoptimizedProducts)
                <span class="text-amber-500">({{ count($unoptimizedProducts) }})</span>
            @endif
        </h2>

        @if(empty($unoptimizedProducts))
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <i class="fa-solid fa-circle-check text-green-500 text-4xl mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-bold">Todos los productos están optimizados</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-white/5">
                        <th class="pb-3 pr-4 font-semibold">Modelo</th>
                        <th class="pb-3 pr-4 font-semibold">Título</th>
                        <th class="pb-3 font-semibold">Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unoptimizedProducts as $item)
                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="py-3 pr-4">
                            <span class="font-bold text-gray-900 dark:text-white">{{ $item['modelo'] }}</span>
                        </td>
                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $item['title'] }}</td>
                        <td class="py-3">
                            @if($item['imagen'])
                            <div class="w-10 h-10 rounded-lg border border-gray-200 dark:border-white/10 overflow-hidden bg-gray-50 dark:bg-[#0a0f1c]">
                                <img src="{{ $item['imagen'] }}" alt="" class="w-full h-full object-contain" loading="lazy" onerror="this.style.display='none'" />
                            </div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
