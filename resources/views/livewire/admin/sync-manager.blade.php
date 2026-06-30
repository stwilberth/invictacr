<div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-[#00C4FF]">{{ $this->stats['total'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total activos</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-blue-500">{{ $this->stats['from_variedades'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">De VariedadesCR</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-amber-500">{{ $this->stats['propios'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Propios (bloqueados)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-green-500">{{ $this->stats['with_stock'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Con stock</p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Sincronizar con VariedadesCR</h2>
                @if($this->lastSuccess)
                <p class="text-xs text-gray-400 mt-1">Última sincronización exitosa: {{ $this->lastSuccess->created_at->format('d/m/Y H:i') }} — {{ $this->lastSuccess->message }}</p>
                @else
                <p class="text-xs text-gray-400 mt-1">Nunca se ha sincronizado</p>
                @endif
            </div>
            <button
                wire:click="triggerSync"
                wire:loading.attr="disabled"
                class="bg-[#00C4FF] hover:bg-[#00b0e6] disabled:opacity-50 disabled:cursor-not-allowed text-[#0a0f1c] font-black px-6 py-3 rounded-xl text-sm transition-all flex items-center gap-2"
            >
                <i wire:loading.remove wire:target="triggerSync" class="fa-solid fa-rotate"></i>
                <i wire:loading wire:target="triggerSync" class="fa-solid fa-spinner fa-spin"></i>
                <span wire:loading.remove wire:target="triggerSync">Sincronizar ahora</span>
                <span wire:loading wire:target="triggerSync">Sincronizando...</span>
            </button>
        </div>

        @if($lastResult)
        <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm font-bold">
            <i class="fa-solid fa-circle-check"></i> {{ $lastResult }}
        </div>
        @endif

        @if($lastError)
        <div class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-bold">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $lastError }}
        </div>
        @endif
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Historial de sincronización</h2>

        @if($this->recentLogs->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm">No hay sincronizaciones registradas.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-white/5">
                        <th class="pb-3 pr-4 font-semibold">Estado</th>
                        <th class="pb-3 pr-4 font-semibold">Detalle</th>
                        <th class="pb-3 pr-4 font-semibold">Creados</th>
                        <th class="pb-3 pr-4 font-semibold">Activados</th>
                        <th class="pb-3 pr-4 font-semibold">Stock</th>
                        <th class="pb-3 pr-4 font-semibold">Precios</th>
                        <th class="pb-3 pr-4 font-semibold">Referencia</th>
                        <th class="pb-3 font-semibold">Agotados</th>
                        <th class="pb-3 font-semibold">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->recentLogs as $log)
                    <tr class="border-b border-gray-100 dark:border-white/5">
                        <td class="py-3 pr-4">
                            @if($log->status === 'completed')
                            <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400"><i class="fa-solid fa-circle-check text-xs"></i> OK</span>
                            @elseif($log->status === 'failed')
                            <span class="inline-flex items-center gap-1 text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-xmark text-xs"></i> Falló</span>
                            @elseif($log->status === 'warning')
                            <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400"><i class="fa-solid fa-triangle-exclamation text-xs"></i> Adv</span>
                            @else
                            <span class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400"><i class="fa-solid fa-spinner text-xs"></i> {{ ucfirst($log->status) }}</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4 text-gray-700 dark:text-gray-300 max-w-xs truncate" title="{{ $log->message }}">{{ $log->message ?? '—' }}</td>
                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $log->details['creados'] ?? '—' }}</td>
                        <td class="py-3 pr-4 text-amber-600 dark:text-amber-400">{{ $log->details['activados'] ?? '—' }}</td>
                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $log->details['stock_actualizado'] ?? '—' }}</td>
                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $log->details['precio_recalculado'] ?? '—' }}</td>
                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $log->details['referencia_actualizada'] ?? '—' }}</td>
                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $log->details['marcados_agotados'] ?? '—' }}</td>
                        <td class="py-3 text-gray-400 text-xs whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
