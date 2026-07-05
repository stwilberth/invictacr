<div>
    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-[#00C4FF]/10 to-[#00C4FF]/5 rounded-2xl border border-[#00C4FF]/20 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#00C4FF]/20 flex items-center justify-center">
                    <i class="fa-solid fa-images text-[#00C4FF]"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-[#00C4FF]">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">Total con imagen</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500/10 to-green-500/5 rounded-2xl border border-green-500/20 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-green-500">{{ $stats['optimized'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">Optimizados</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-amber-500/10 to-amber-500/5 rounded-2xl border border-amber-500/20 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-amber-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-amber-500">{{ $stats['unoptimized'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500/10 to-purple-500/5 rounded-2xl border border-purple-500/20 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-hard-drive text-purple-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-purple-500">{{ number_format($items->sum(fn($i) => ($i['original']['size'] ?? 0) + ($i['large']['size'] ?? 0) + ($i['medium']['size'] ?? 0) + ($i['thumb']['size'] ?? 0)) / 1048576, 1) }}MB</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">Total en página</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Action card --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Optimizar imágenes</h2>
                <p class="text-xs text-gray-400 mt-1">
                    Genera WebP en 3 tamaños: <span class="text-indigo-500 font-bold">grande 1200px</span> · <span class="text-blue-500 font-bold">mediano 600px</span> · <span class="text-emerald-500 font-bold">miniatura 200px</span>
                </p>
            </div>
            <button
                wire:click="optimizeAll"
                wire:loading.attr="disabled"
                wire:target="optimizeAll"
                class="bg-gradient-to-r from-[#00C4FF] to-cyan-500 hover:from-[#00b0e6] hover:to-cyan-600 disabled:opacity-50 disabled:cursor-not-allowed text-[#0a0f1c] font-black px-6 py-3 rounded-xl text-sm transition-all flex items-center gap-2 shadow-lg shadow-cyan-500/25"
            >
                <i wire:loading.remove wire:target="optimizeAll" class="fa-solid fa-wand-magic-sparkles"></i>
                <i wire:loading wire:target="optimizeAll" class="fa-solid fa-spinner fa-spin"></i>
                <span wire:loading.remove wire:target="optimizeAll">Optimizar todas</span>
                <span wire:loading wire:target="optimizeAll">Optimizando {{ $processed }}...</span>
            </button>
        </div>

        @if($optimizing)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800/30 rounded-xl p-4">
            <div class="flex items-center gap-3 mb-2">
                <i class="fa-solid fa-spinner fa-spin text-blue-500"></i>
                <span class="text-sm font-bold text-blue-700 dark:text-blue-300">
                    Procesando... {{ $processed }} / {{ $stats['total'] }}
                </span>
            </div>
            @if($currentModelo)
            <p class="text-xs text-blue-600 dark:text-blue-400 ml-7">
                Actual: <span class="font-bold">{{ $currentModelo }}</span>
                ({{ $successCount }} OK, {{ $failCount }} fallos)
            </p>
            @endif
            <div class="mt-3 w-full bg-blue-200 dark:bg-blue-900/50 rounded-full h-2.5 overflow-hidden">
                @php $pct = min(100, $stats['total'] > 0 ? round(($processed / $stats['total']) * 100) : 0); @endphp
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
            </div>
        </div>
        @endif

        @if($lastResult)
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border border-green-200 dark:border-green-800/30 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm font-bold mt-4 flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-500"></i> {{ $lastResult }}
        </div>
        @endif

        @if($lastError)
        <div class="bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/30 dark:to-rose-900/30 border border-red-200 dark:border-red-800/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-bold mt-4 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ $lastError }}
        </div>
        @endif
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input wire:model.live="search" type="text" placeholder="Buscar por modelo o título..." class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-[#00C4FF]/30 focus:border-[#00C4FF] outline-none" />
            </div>
        </div>
        <select wire:model.live="filterStatus" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#00C4FF]/30 focus:border-[#00C4FF] outline-none">
            <option value="all">Todos</option>
            <option value="pending">Pendientes</option>
            <option value="optimized">Optimizados</option>
        </select>
        <span class="text-xs text-gray-400 font-semibold">
            {{ $products->total() }} productos
        </span>
    </div>

    {{-- Products table --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-900 to-gray-800 dark:from-[#0a0f1c] dark:to-[#1a2332] text-white text-left text-xs uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold w-12">#</th>
                        <th class="px-4 py-3 font-bold">Producto</th>
                        <th class="px-4 py-3 font-bold">Original</th>
                        <th class="px-4 py-3 font-bold text-indigo-300">Large 1200</th>
                        <th class="px-4 py-3 font-bold text-blue-300">Medium 600</th>
                        <th class="px-4 py-3 font-bold text-emerald-300">Thumb 200</th>
                        <th class="px-4 py-3 font-bold text-center">Estado</th>
                        <th class="px-4 py-3 font-bold text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                    @php
                        $rowNum = ($products->currentPage() - 1) * $products->perPage() + $i + 1;
                    @endphp
                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors {{ $item['needs_optimization'] ? '' : 'bg-green-50/30 dark:bg-green-900/5' }}">
                        <td class="px-4 py-3">
                            <span class="text-gray-400 dark:text-gray-500 font-mono text-xs">{{ $rowNum }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg border border-gray-200 dark:border-white/10 overflow-hidden bg-gray-50 dark:bg-[#0a0f1c] flex-shrink-0">
                                    @if($item['imagen'])
                                    <img src="{{ $item['imagen'] }}" alt="" class="w-full h-full object-contain" loading="lazy" onerror="this.style.display='none'" />
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-solid fa-image text-xs"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ $item['modelo'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[180px]">{{ $item['title'] ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($item['original'] && $item['original']['exists'])
                                <div class="text-xs">
                                    <span class="text-gray-800 dark:text-white font-bold">{{ $item['original']['width'] }}×{{ $item['original']['height'] }}</span>
                                    <span class="text-gray-400 ml-1.5">{{ number_format($item['original']['size'] / 1024, 0) }}KB</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($item['large']['exists'])
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-check-circle text-indigo-500 text-xs"></i>
                                    <span class="text-xs font-bold text-gray-800 dark:text-white">{{ number_format($item['large']['size'] / 1024, 0) }}KB</span>
                                    @if($item['original']['exists'] && $item['original']['size'] > 0)
                                    <span class="text-[10px] text-indigo-400 font-bold">(-{{ 100 - round(($item['large']['size'] / $item['original']['size']) * 100) }}%)</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-xs"><i class="fa-solid fa-xmark"></i></span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($item['medium']['exists'])
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-check-circle text-blue-500 text-xs"></i>
                                    <span class="text-xs font-bold text-gray-800 dark:text-white">{{ number_format($item['medium']['size'] / 1024, 0) }}KB</span>
                                    @if($item['original']['exists'] && $item['original']['size'] > 0)
                                    <span class="text-[10px] text-blue-400 font-bold">(-{{ 100 - round(($item['medium']['size'] / $item['original']['size']) * 100) }}%)</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-xs"><i class="fa-solid fa-xmark"></i></span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($item['thumb']['exists'])
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-check-circle text-emerald-500 text-xs"></i>
                                    <span class="text-xs font-bold text-gray-800 dark:text-white">{{ number_format($item['thumb']['size'] / 1024, 0) }}KB</span>
                                    @if($item['original']['exists'] && $item['original']['size'] > 0)
                                    <span class="text-[10px] text-emerald-400 font-bold">(-{{ 100 - round(($item['thumb']['size'] / $item['original']['size']) * 100) }}%)</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-xs"><i class="fa-solid fa-xmark"></i></span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($item['needs_optimization'])
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-bold">
                                <i class="fa-solid fa-hourglass-half"></i> Pendiente
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] font-bold">
                                <i class="fa-solid fa-check"></i> Listo
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button
                                wire:click="optimizeProduct({{ $item['id'] }})"
                                @disabled(!$item['needs_optimization'] || $optimizingProductId)
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all
                                    {{ $item['needs_optimization'] && !$optimizingProductId
                                        ? 'bg-indigo-500 hover:bg-indigo-600 text-white shadow-sm hover:shadow-md active:scale-95'
                                        : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed' }}"
                            >
                                @if($optimizingProductId === $item['id'])
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                <span>...</span>
                                @else
                                <i class="fa-solid {{ $item['needs_optimization'] ? 'fa-wand-magic-sparkles' : 'fa-check' }}"></i>
                                <span>{{ $item['needs_optimization'] ? 'Optimizar' : 'Hecho' }}</span>
                                @endif
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa-solid fa-image text-gray-300 dark:text-gray-600 text-4xl"></i>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-bold">No hay productos con imagen</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $products->links(data: ['scrollTo' => false]) }}
    </div>
</div>
