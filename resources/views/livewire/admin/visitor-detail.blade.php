<div>
    <a href="{{ route('admin.visitors') }}" class="text-sm text-gray-500 hover:text-[#00C4FF] transition-colors mb-4 inline-block">
        <i class="fa-solid fa-arrow-left mr-1"></i> Volver a visitantes
    </a>

    {{-- Profile header --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6 mt-2">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#00C4FF]/10 flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $visitor->device_icon }} text-2xl text-[#00C4FF]"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl font-black text-gray-900 dark:text-white">{{ $visitor->name ?: 'Visitante anónimo' }}</h2>
                        @if($visitor->user_id)
                            <span class="px-2 py-0.5 rounded-lg text-xs font-black bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 uppercase">Registrado</span>
                        @endif
                        @if($visitor->whatsapp_clicked_at)
                            <span class="px-2 py-0.5 rounded-lg text-xs font-black bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 uppercase">
                                <i class="fa-brands fa-whatsapp mr-1"></i> WhatsApp {{ $visitor->whatsapp_clicked_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 space-y-0.5">
                        @if($visitor->email)<div><i class="fa-solid fa-envelope w-4 mr-1"></i> {{ $visitor->email }}</div>@endif
                        @if($visitor->phone)<div><i class="fa-solid fa-phone w-4 mr-1"></i> {{ $visitor->phone }}</div>@endif
                        @if($visitor->user)<div><i class="fa-solid fa-user w-4 mr-1"></i> Cuenta: {{ $visitor->user->email }}</div>@endif
                        <div class="text-xs text-gray-400 font-mono">{{ $visitor->uuid }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                <div class="bg-gray-50 dark:bg-[#0a0f1c] rounded-xl p-3">
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $visitor->visits_count }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Visitas</p>
                </div>
                <div class="bg-gray-50 dark:bg-[#0a0f1c] rounded-xl p-3">
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $visitor->pageviews_count }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Páginas</p>
                </div>
                <div class="bg-gray-50 dark:bg-[#0a0f1c] rounded-xl p-3">
                    <p class="text-xl font-black text-[#00C4FF]">{{ $visitor->total_time_human }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Tiempo total</p>
                </div>
                <div class="bg-gray-50 dark:bg-[#0a0f1c] rounded-xl p-3">
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ count($productsSeen) }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Relojes</p>
                </div>
            </div>
        </div>

        {{-- Technical + attribution --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-white/5 text-sm">
            <div class="space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Técnico</h3>
                <div class="text-gray-600 dark:text-gray-400 space-y-1 text-xs">
                    <div><span class="text-gray-400">IP:</span> {{ $visitor->ip ?: '—' }}</div>
                    <div><span class="text-gray-400">Dispositivo:</span> {{ $visitor->device_type }} · {{ $visitor->browser }} · {{ $visitor->platform }}</div>
                    <div><span class="text-gray-400">Primera visita:</span> {{ $visitor->first_seen_at?->format('d/m/Y H:i') }}</div>
                    <div><span class="text-gray-400">Última actividad:</span> {{ $visitor->last_seen_at?->format('d/m/Y H:i') }} ({{ $visitor->last_seen_at?->diffForHumans() }})</div>
                </div>
            </div>
            <div class="space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Origen</h3>
                <div class="text-gray-600 dark:text-gray-400 space-y-1 text-xs break-all">
                    @if($visitor->utm_source)
                        <div><span class="text-gray-400">UTM:</span> {{ $visitor->utm_source }} / {{ $visitor->utm_medium }} / {{ $visitor->utm_campaign }}</div>
                    @endif
                    <div><span class="text-gray-400">Referrer:</span> {{ $visitor->referrer ?: 'Directo' }}</div>
                    <div><span class="text-gray-400">Landing:</span> {{ Str::limit($visitor->landing_page, 80) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            {{-- Relojes vistos --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">
                    <i class="fa-solid fa-clock text-[#00C4FF] mr-2"></i>Relojes que vio
                </h3>
                <div class="space-y-3">
                    @forelse($productsSeen as $item)
                        @if(!empty($item['product']))
                        <a href="{{ route('products.show', $item['product']['slug']) }}" target="_blank" class="flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 dark:bg-[#0a0f1c] shrink-0 flex items-center justify-center">
                                @if(!empty($item['product']['imagen']))
                                    <img src="{{ $item['product']['imagen'] }}" class="w-full h-full object-contain" loading="lazy" />
                                @else
                                    <i class="fa-solid fa-image text-gray-300 text-xs"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-[#00C4FF] transition-colors">{{ $item['product']['title'] }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $item['views'] }}x ·
                                    @if($item['total_seconds'] >= 60)
                                        {{ intdiv($item['total_seconds'], 60) }}m {{ $item['total_seconds'] % 60 }}s
                                    @else
                                        {{ $item['total_seconds'] }}s
                                    @endif
                                    · {{ \Carbon\Carbon::parse($item['last_viewed_at'])->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                        @endif
                    @empty
                        <p class="text-sm text-gray-400">Aún no vio ningún reloj.</p>
                    @endforelse
                </div>
            </div>

            {{-- Facturas --}}
            @if(count($invoices) > 0)
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">
                    <i class="fa-solid fa-file-invoice text-green-500 mr-2"></i>Facturas asociadas
                </h3>
                <div class="space-y-2">
                    @foreach($invoices as $inv)
                    <a href="{{ route('admin.invoices.detail', $inv['id']) }}" class="flex justify-between items-center text-sm hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg px-2 py-1.5 transition-colors">
                        <span class="font-bold text-gray-900 dark:text-white">{{ $inv['invoice_number'] }}</span>
                        <span class="text-gray-500">₡{{ number_format($inv['total'], 0) }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">
                    <i class="fa-solid fa-timeline text-[#00C4FF] mr-2"></i>Timeline de actividad
                </h3>
                <div class="space-y-1">
                    @forelse($events as $event)
                    <div class="flex items-start gap-3 py-2 border-b border-gray-50 dark:border-white/5 last:border-0">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-[#0a0f1c] flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid {{ $event->type_icon }} text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $event->type_label }}</span>
                                @if($event->product)
                                    <a href="{{ route('products.show', $event->product->slug) }}" target="_blank" class="text-xs text-[#00C4FF] hover:underline font-bold">{{ $event->product->modelo }}</a>
                                @endif
                                @if($event->type === 'search' && !empty($event->meta['query']))
                                    <span class="text-xs text-amber-600 dark:text-amber-400 font-bold">"{{ $event->meta['query'] }}"</span>
                                @endif
                                @if($event->duration_seconds)
                                    <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 dark:bg-white/5 text-gray-500 font-bold">{{ $event->duration_human }}</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400 truncate">{{ Str::limit($event->page_title ?: $event->url, 80) }}</div>
                        </div>
                        <div class="text-xs text-gray-400 whitespace-nowrap shrink-0" title="{{ $event->created_at?->format('d/m/Y H:i:s') }}">
                            {{ $event->created_at?->diffForHumans(null, null, true) }}
                        </div>
                    </div>
                    @empty
                        <p class="text-sm text-gray-400 py-4 text-center">Sin actividad registrada.</p>
                    @endforelse
                </div>

                <div class="mt-4">{{ $events->links() }}</div>
            </div>
        </div>
    </div>
</div>
