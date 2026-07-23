<div>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-[#00C4FF]">{{ number_format($totalVisitors) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Visitantes totales</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-blue-500">{{ number_format($todayVisitors) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Activos hoy</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-amber-500">{{ number_format($activeNow) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">En el sitio ahora</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-green-500">{{ number_format($whatsappRecent) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Click WhatsApp 24h</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-purple-500">{{ number_format($registeredCount) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Registrados</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3 flex-wrap">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Nombre, email, teléfono, IP..."
                class="w-full sm:w-64 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"
            />
            <select
                wire:model.live="filter"
                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300"
            >
                <option value="">Todos</option>
                <option value="whatsapp">🟢 Contactaron recientemente (24h)</option>
                <option value="with_contact">Con datos de contacto</option>
                <option value="registered">Registrados</option>
                <option value="ads">Vinieron de anuncios</option>
            </select>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span class="font-bold text-gray-900 dark:text-white">{{ $visitors->total() }}</span> perfiles
        </p>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Visitante</th>
                        <th class="px-4 py-3 text-center">Dispositivo</th>
                        <th class="px-4 py-3">Origen</th>
                        <th class="px-4 py-3 text-center cursor-pointer hover:text-gray-700 dark:hover:text-gray-200" wire:click="sortBy('visits_count')">
                            Visitas
                            @if($sortField === 'visits_count') <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th class="px-4 py-3 text-center cursor-pointer hover:text-gray-700 dark:hover:text-gray-200" wire:click="sortBy('total_time_seconds')">
                            Tiempo
                            @if($sortField === 'total_time_seconds') <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th class="px-4 py-3 text-center">Relojes</th>
                        <th class="px-4 py-3 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200" wire:click="sortBy('last_seen_at')">
                            Última actividad
                            @if($sortField === 'last_seen_at') <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($visitors as $visitor)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.visitors.detail', $visitor->id) }}" class="block group">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-gray-900 dark:text-white group-hover:text-[#00C4FF] transition-colors">
                                        {{ $visitor->name ?: 'Anónimo' }}
                                    </span>
                                    @if($visitor->whatsapp_clicked_at && $visitor->whatsapp_clicked_at->gte(now()->subHours(24)))
                                        <i class="fa-brands fa-whatsapp text-green-500" title="Click WhatsApp {{ $visitor->whatsapp_clicked_at->diffForHumans() }}"></i>
                                    @endif
                                    @if($visitor->user_id)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 uppercase">Cuenta</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $visitor->email ?: $visitor->phone ?: Str::limit($visitor->uuid, 18, '…') }}
                                </div>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <i class="fa-solid {{ $visitor->device_icon }} text-gray-400" title="{{ $visitor->device_type }} · {{ $visitor->browser }} · {{ $visitor->platform }}"></i>
                            <div class="text-[10px] text-gray-400">{{ $visitor->browser }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if($visitor->utm_source)
                                <span class="px-2 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $visitor->utm_source }}{{ $visitor->utm_campaign ? ' / ' . $visitor->utm_campaign : '' }}
                                </span>
                            @elseif($visitor->referrer)
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ parse_url($visitor->referrer, PHP_URL_HOST) }}</span>
                            @else
                                <span class="text-xs text-gray-400">Directo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-gray-900 dark:text-white">{{ $visitor->visits_count }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $visitor->total_time_human }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold {{ $visitor->products_seen > 0 ? 'text-[#00C4FF]' : 'text-gray-400' }}">{{ $visitor->products_seen }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $visitor->last_seen_at?->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No hay visitantes registrados todavía</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $visitors->links() }}</div>
</div>
