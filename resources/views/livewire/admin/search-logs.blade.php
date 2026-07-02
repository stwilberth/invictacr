<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar por consulta..."
                class="w-full sm:w-64 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"
            />
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span class="font-bold text-gray-900 dark:text-white">{{ $logs->total() }}</span> búsquedas registradas
        </p>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200" wire:click="sortBy('created_at')">
                            Fecha
                            @if($sortField === 'created_at') <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200" wire:click="sortBy('query')">
                            Consulta
                            @if($sortField === 'query') <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th class="px-4 py-3">Filtros Detectados</th>
                        <th class="px-4 py-3 text-center">IA</th>
                        <th class="px-4 py-3 text-center">Resultados</th>
                        <th class="px-4 py-3">Respuesta IA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            @if($log->user)
                                <span class="font-medium">{{ $log->user->name ?: $log->user->email }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white max-w-[200px] truncate" title="{{ $log->query }}">
                            "{{ $log->query }}"
                        </td>
                        <td class="px-4 py-3">
                            @if($log->parsed_filters && count($log->parsed_filters) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($log->parsed_filters as $key => $val)
                                        @if($key !== 'q')
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-[#00C4FF]/10 text-[#00C4FF] rounded text-[10px] font-bold uppercase tracking-wider">
                                            {{ $key }}: {{ $val }}
                                        </span>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($log->used_ai)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded text-[10px] font-bold">
                                    <i class="fa-solid fa-robot"></i> AI
                                </span>
                            @else
                                <span class="text-gray-300 dark:text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 font-bold">
                            {{ $log->results_count }}
                        </td>
                        <td class="px-4 py-3 max-w-[200px]">
                            @if($log->ai_response)
                                <button
                                    onclick="alert({{ json_encode($log->ai_response) }})"
                                    class="text-[#00C4FF] hover:underline text-xs font-bold"
                                >
                                    Ver respuesta
                                </button>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
                            No hay búsquedas registradas aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
