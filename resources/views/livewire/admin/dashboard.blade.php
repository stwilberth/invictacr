<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Dashboard</h1>
        <button wire:click="syncData" wire:loading.attr="disabled" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors bg-[#00C4FF] text-white hover:bg-[#00a8d6] disabled:opacity-50">
            <span wire:loading.remove wire:target="syncData">Actualizar datos</span>
            <span wire:loading wire:target="syncData">Sincronizando...</span>
        </button>
    </div>

    @if(session('message'))
    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-sm font-bold text-emerald-700 dark:text-emerald-400">
        {{ session('message') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold text-red-700 dark:text-red-400">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-[#00C4FF]">{{ $stats['products'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Productos activos</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-green-500">₡{{ number_format($stats['monthly_revenue']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresos del mes</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-blue-500">{{ $stats['monthly_invoices'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Facturas del mes</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-emerald-500">₡{{ number_format($stats['avg_order_value']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ticket promedio</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-indigo-500">{{ number_format($stats['visitors_today']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Visitas hoy</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-green-500">{{ number_format($stats['whatsapp_clicks']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Clicks WhatsApp</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-purple-500">{{ $stats['monthly_subscribers'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Suscriptores nuevos</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-amber-500">{{ $stats['low_stock'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Stock bajo</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-red-500">{{ $stats['out_of_stock'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Agotados</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-violet-500">{{ $stats['upcoming'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Próximamente</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Google Analytics</h2>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Usuarios</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($gaSummary['total_users'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Sesiones</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($gaSummary['total_sessions'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Pageviews</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($gaSummary['total_pageviews'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Bounce Rate</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($gaSummary['avg_bounce_rate'] ?? 0, 1) }}%</p>
                </div>
            </div>
            @if(count($trafficSources) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Fuentes de tráfico</h3>
            <div class="space-y-1">
                @php $maxUsers = max(array_column($trafficSources, 'users')); @endphp
                @foreach($trafficSources as $src)
                <div class="flex items-center gap-2 text-xs">
                    <span class="text-gray-700 dark:text-gray-300 w-28 truncate">{{ $src['source'] }}</span>
                    <div class="flex-1 h-1.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-[#00C4FF] rounded-full" style="width: {{ $maxUsers > 0 ? ($src['users'] / $maxUsers) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-gray-500 w-16 text-right">{{ number_format($src['users']) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Últimas facturas</h2>
            @if(count($recentInvoices) > 0)
            <div class="space-y-3">
                @foreach($recentInvoices as $inv)
                <div class="flex items-center justify-between text-sm pb-3 border-b border-gray-100 dark:border-white/5 last:border-0 last:pb-0">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-gray-900 dark:text-white truncate">{{ $inv['client'] }}</p>
                        <p class="text-xs text-gray-400">{{ $inv['created_at'] }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xs font-bold px-2 py-1 rounded
                            {{ $inv['status'] === 'paid' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : '' }}
                            {{ $inv['status'] === 'pending' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                            {{ $inv['status'] === 'cancelled' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '' }}
                            {{ !in_array($inv['status'], ['paid', 'pending', 'cancelled']) ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' : '' }}
                        ">{{ $inv['status'] }}</span>
                        <span class="font-bold text-gray-900 dark:text-white">₡{{ number_format($inv['total']) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin facturas recientes.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Colecciones más vendidas</h2>
            @if(count($topCollections) > 0)
            <div class="space-y-2">
                @foreach($topCollections as $i => $col)
                <div class="flex items-center gap-3 text-sm">
                    <span class="w-5 text-center font-bold text-gray-400 text-xs">#{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-gray-800 dark:text-gray-200 truncate font-medium">{{ $col['name'] }}</p>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $col['total_qty'] }} uds</span>
                    <span class="text-[#00C4FF] font-bold w-28 text-right">₡{{ number_format($col['total_revenue']) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin ventas registradas.</p>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Últimas publicaciones Facebook</h2>
            @if(count($recentFbPosts) > 0)
            <div class="space-y-3">
                @foreach($recentFbPosts as $post)
                <div class="text-xs pb-3 border-b border-gray-100 dark:border-white/5 last:border-0 last:pb-0">
                    <p class="text-gray-700 dark:text-gray-300 truncate">{{ $post['message'] }}</p>
                    <p class="text-gray-500 mt-1 flex gap-3">
                        <span>{{ $post['posted_at'] }}</span>
                        <span>❤️ {{ $post['likes'] }}</span>
                        <span>💬 {{ $post['comments'] }}</span>
                        <span>🔄 {{ $post['shares'] }}</span>
                    </p>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin publicaciones recientes.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Nuevos suscriptores</h2>
            @if(count($recentSubscribers) > 0)
            <div class="space-y-2">
                @foreach($recentSubscribers as $sub)
                <div class="flex items-center justify-between text-sm pb-2 border-b border-gray-100 dark:border-white/5 last:border-0 last:pb-0">
                    <span class="text-gray-700 dark:text-gray-300 truncate">{{ $sub['email'] }}</span>
                    <span class="text-xs text-gray-400 shrink-0 ml-3">{{ $sub['created_at'] }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin suscriptores nuevos.</p>
            @endif
        </div>

        @if(count($recentSyncs) > 0)
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Sincronizaciones recientes</h2>
            <div class="space-y-2">
                @foreach($recentSyncs as $log)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ $log['type'] }} - {{ $log['message'] }}</span>
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
