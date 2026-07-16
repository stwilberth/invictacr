<div>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-[#00C4FF]">{{ number_format($totalSearches) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Búsquedas totales</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-purple-500">{{ number_format($aiSearches) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Con IA (Claude)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-amber-500">{{ number_format($aiSkippedSearches) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">IA saltada (anti-falsos)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-red-500">{{ number_format($noResultsSearches) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sin resultados</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-green-500">{{ number_format($uniqueQueries) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Consultas únicas</p>
        </div>
    </div>

    {{-- Top Searches Chart --}}
    @if(count($topQueries) > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">
            <i class="fa-solid fa-chart-bar text-[#00C4FF] mr-2"></i> Búsquedas más frecuentes
        </h2>
        <div class="relative" style="height: 250px;">
            <canvas id="topQueriesChart"></canvas>
        </div>
    </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar por consulta..."
                class="w-full sm:w-64 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"
            />
            <select
                wire:model.live="filterResults"
                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300"
            >
                <option value="">Todos los resultados</option>
                <option value="with_results">Con resultados</option>
                <option value="no_results">Sin resultados</option>
            </select>
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
                        <th class="px-4 py-3">IP Real</th>
                        <th class="px-4 py-3 text-center">Dispositivo</th>
                        <th class="px-4 py-3 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200" wire:click="sortBy('query')">
                            Consulta
                            @if($sortField === 'query') <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th class="px-4 py-3">Filtros Detectados</th>
                        <th class="px-4 py-3 text-center">IA</th>
                        <th class="px-4 py-3 text-center">Resultados</th>
                        <th class="px-4 py-3 text-center">Sugerencias</th>
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
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs whitespace-nowrap" title="CF: {{ $log->ip_address }}">
                            {{ $log->real_ip ?? $log->ip_address }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $deviceIcon = match ($log->device_type) {
                                    'mobile' => 'fa-mobile',
                                    'tablet' => 'fa-tablet',
                                    default => 'fa-desktop',
                                };
                                $deviceColor = match ($log->device_type) {
                                    'mobile' => 'text-green-500',
                                    'tablet' => 'text-blue-500',
                                    default => 'text-gray-500',
                                };
                            @endphp
                            <span class="{{ $deviceColor }}" title="{{ $log->user_agent ?? '' }}">
                                <i class="fa-solid {{ $deviceIcon }}"></i>
                                @if($log->device_type)
                                    <span class="text-[10px] font-bold ml-0.5">{{ ucfirst($log->device_type) }}</span>
                                @endif
                            </span>
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
                                    <i class="fa-solid fa-robot"></i> Claude
                                </span>
                            @elseif($log->ai_skipped_reason)
                                @php
                                    $skipLabel = match($log->ai_skipped_reason) {
                                        'model_number_query' => 'N° modelo',
                                        'no_api_key' => 'Sin API key',
                                        default => $log->ai_skipped_reason,
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded text-[10px] font-bold" title="IA saltada para evitar falsas coincidencias">
                                    <i class="fa-solid fa-shield-halved"></i> {{ $skipLabel }}
                                </span>
                            @else
                                <span class="text-gray-300 dark:text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 font-bold">
                            {{ $log->results_count }}
                        </td>
                        <td class="px-4 py-3 max-w-[200px]">
                            @if($log->suggestions && count($log->suggestions) > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded text-[10px] font-bold" title="{{ collect($log->suggestions)->pluck('modelo')->implode(', ') }}">
                                    <i class="fa-solid fa-lightbulb"></i> {{ count($log->suggestions) }} sug.
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
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
                        <td colspan="11" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:init', function () {
            const labels = @json(array_column($topQueries, 'query'));
            const data = @json(array_column($topQueries, 'count'));

            const ctx = document.getElementById('topQueriesChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Búsquedas',
                        data: data,
                        backgroundColor: 'rgba(0, 196, 255, 0.7)',
                        borderColor: 'rgba(0, 196, 255, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: {
                                stepSize: 1,
                                color: '#9ca3af',
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.05)'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 11 }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</div>
