<div>
    {{-- ==================== LISTA DE ESPERA (inicio) ==================== --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-bell-concierge text-amber-500"></i>
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Lista de espera</h2>
                <span class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $waitlistPendientes > 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' : 'bg-gray-100 text-gray-500 dark:bg-white/5' }}">{{ $waitlistPendientes }} en espera</span>
                @if($waitlistUnread > 0)
                <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">{{ $waitlistUnread }} aviso(s)</span>
                @endif
            </div>
            <a href="{{ route('admin.waitlist') }}" class="text-xs font-extrabold uppercase text-[#00C4FF] hover:underline">Gestionar <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
        </div>

        @if($waitlistUnread > 0)
        <div class="space-y-2 mb-3">
            @foreach($waitlistNotifications as $notif)
            @if(!$notif['leida'])
            <div class="flex items-start gap-2 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 text-sm">
                <i class="fa-solid fa-bell mt-0.5 text-amber-500"></i>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 dark:text-white text-xs">{{ $notif['titulo'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $notif['mensaje'] }}</p>
                </div>
                <button wire:click="marcarWaitlistLeida({{ $notif['id'] }})" class="shrink-0 text-[10px] font-extrabold uppercase text-amber-700 dark:text-amber-300 hover:underline">Leída</button>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        @if(count($waitlistResumen) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[10px] uppercase tracking-wider text-gray-400 border-b border-gray-100 dark:border-white/5">
                        <th class="py-1.5 pr-2">Contacto</th>
                        <th class="py-1.5 pr-2">Modelo</th>
                        <th class="py-1.5">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($waitlistResumen as $w)
                    <tr class="border-b border-gray-50 dark:border-white/5 last:border-0">
                        <td class="py-2 pr-2"><span class="font-bold text-gray-900 dark:text-white text-xs">{{ $w['nombre'] }}</span> <span class="text-xs text-gray-400">{{ $w['telefono'] ?? '' }}</span></td>
                        <td class="py-2 pr-2 font-mono font-bold text-xs text-[#00C4FF]">{{ $w['modelo'] }}</td>
                        <td class="py-2">
                            @if($w['estado'] === 'pendiente')
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">En espera</span>
                            @elseif($w['estado'] === 'notificado')
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Avisar</span>
                            @else
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-lg bg-gray-100 text-gray-500 dark:bg-white/5">{{ $w['estado'] }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-gray-500">Nadie en lista de espera. <a href="{{ route('admin.waitlist') }}" class="font-bold text-[#00C4FF] hover:underline">Agregar contacto</a></p>
        @endif
    </div>

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Dashboard</h1>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <button wire:click="$set('period', '7d')" class="flex-1 sm:flex-none px-3 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $period === '7d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">7 días</button>
                <button wire:click="$set('period', '30d')" class="flex-1 sm:flex-none px-3 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $period === '30d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">30 días</button>
                <button wire:click="$set('period', '90d')" class="flex-1 sm:flex-none px-3 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $period === '90d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">90 días</button>
                <button wire:click="$set('period', '365d')" class="flex-1 sm:flex-none px-3 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $period === '365d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">1 año</button>
            </div>
            <button wire:click="syncData" wire:loading.attr="disabled" class="w-full sm:w-auto px-4 py-2 rounded-xl text-sm font-bold text-center transition-colors bg-[#00C4FF] text-white hover:bg-[#00a8d6] disabled:opacity-50">
                <span wire:loading.remove wire:target="syncData">Actualizar datos</span>
                <span wire:loading wire:target="syncData">Sincronizando...</span>
            </button>
        </div>
    </div>

    @if(($daysSinceLastGaSync !== null && $daysSinceLastGaSync > 2) || ($daysSinceLastAdsSync !== null && $daysSinceLastAdsSync > 2))
    <div class="mb-6 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 flex items-center gap-3 text-sm">
        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
        <p class="text-red-700 dark:text-red-400">
            <span class="font-black">Datos desactualizados:</span>
            @if($daysSinceLastGaSync !== null && $daysSinceLastGaSync > 2)
                Google Analytics no sincroniza hace {{ $daysSinceLastGaSync }} días.
            @endif
            @if($daysSinceLastAdsSync !== null && $daysSinceLastAdsSync > 2)
                Google Ads no sincroniza hace {{ $daysSinceLastAdsSync }} días.
            @endif
            Las métricas de tráfico/publicidad de este período pueden no ser confiables — no es necesariamente una caída real.
        </p>
    </div>
    @endif

    @if($topCeoRecommendation)
    @php
        $ceoIcon = match($topCeoRecommendation['category']) {
            'urgente' => '🔴',
            'oportunidad' => '🟡',
            default => '🔵',
        };
    @endphp
    <a href="{{ route('admin.ceo-advisor') }}" class="block mb-6 p-4 rounded-2xl border border-[#00C4FF]/20 bg-gradient-to-br from-[#0a0f1c] to-[#0f172a] hover:-translate-y-0.5 transition-transform">
        <div class="flex items-center gap-3">
            <span class="text-xl">🎯</span>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-black text-[#00C4FF] uppercase tracking-wider">El CEO IA dice</p>
                <p class="text-sm text-white font-bold truncate">{{ $ceoIcon }} {{ $topCeoRecommendation['title'] }}</p>
                <p class="text-xs text-white/50 truncate">{{ $topCeoRecommendation['action'] }}</p>
            </div>
            <i class="fa-solid fa-arrow-right text-[#00C4FF] shrink-0"></i>
        </div>
    </a>
    @endif

    {{-- ==================== GESTIÓN INTERNA (ADMIN) ==================== --}}
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Gestión interna</h2>
        <span class="text-xs font-bold px-2 py-1 rounded bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400 uppercase tracking-wider">Admin</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        {{-- Usuarios --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios</p>
                    <p class="text-base sm:text-2xl font-black text-[#00C4FF] mt-1">{{ number_format($userStats['total'] ?? 0) }}</p>
                </div>
                <i class="fa-solid fa-users text-gray-300 dark:text-gray-600 text-xl"></i>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex flex-wrap justify-between items-center text-xs text-gray-500">
                <span>+{{ $userStats['new_this_month'] ?? 0 }} este mes</span>
                <a href="{{ route('admin.users') }}" class="font-bold text-[#00C4FF] hover:underline">Gestionar <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
        </div>

        {{-- Roles y accesos --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between mb-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">Roles y accesos</p>
                <i class="fa-solid fa-user-shield text-gray-300 dark:text-gray-600 text-xl"></i>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-2">
                    <p class="text-lg font-black text-violet-500">{{ $userStats['admins'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Admins</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-2">
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ $userStats['clients'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Clientes</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-2">
                    <p class="text-lg font-black text-indigo-500">{{ number_format($userStats['visitors_today'] ?? 0) }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Accesos hoy</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-2">
                    <p class="text-lg font-black text-green-500">{{ number_format($userStats['whatsapp_clicks'] ?? 0) }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">WhatsApp</p>
                </div>
            </div>
        </div>

        {{-- Próximos relojes --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Próximos relojes</p>
                    <p class="text-base sm:text-2xl font-black text-amber-500 mt-1">{{ $upcomingCount }}</p>
                </div>
                <i class="fa-solid fa-clock text-gray-300 dark:text-gray-600 text-xl"></i>
            </div>
            @if(count($upcomingProducts) > 0)
            <div class="space-y-2">
                @foreach($upcomingProducts as $prod)
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gray-50 dark:bg-white/5 rounded-lg overflow-hidden shrink-0">
                        <img src="{{ $prod['image'] }}" alt="{{ $prod['name'] }}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate">{{ $prod['name'] }}</p>
                    </div>
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 shrink-0">Próximamente</span>
                </div>
                @endforeach
            </div>
            @if($upcomingCount > count($upcomingProducts))
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex justify-end">
                <a href="{{ route('admin.upcoming') }}" class="text-xs font-bold text-[#00C4FF] hover:underline">Ver todos ({{ $upcomingCount }}) <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            @endif
            @else
            <p class="text-sm text-gray-500">No hay próximos relojes programados.</p>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-10">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Servidor</h2>
            <span class="text-xs font-bold px-2 py-1 rounded {{ $serverMetricsAvailable ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">{{ $serverMetricsAvailable ? 'netdata activo' : 'netdata no disponible' }}</span>
        </div>

        @if($serverMetricsAvailable)
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
            <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">CPU ahora</p>
                <p class="text-xl font-black text-[#00C4FF]">{{ $serverStats['cpu_pct'] ?? 0 }}%</p>
                <div class="mt-2 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-[#00C4FF] rounded-full" style="width: {{ min($serverStats['cpu_pct'] ?? 0, 100) }}%"></div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">RAM ahora</p>
                <p class="text-xl font-black text-gray-900 dark:text-white">{{ $serverStats['ram_pct'] ?? 0 }}%</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ number_format($serverStats['ram_used_mib'] ?? 0) }} / {{ number_format($serverStats['ram_total_mib'] ?? 0) }} MiB</p>
                <div class="mt-2 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full {{ ($serverStats['ram_pct'] ?? 0) > 90 ? 'bg-red-500' : 'bg-emerald-500' }} rounded-full" style="width: {{ min($serverStats['ram_pct'] ?? 0, 100) }}%"></div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pico CPU (7d)</p>
                <p class="text-xl font-black text-amber-500">{{ $serverPeak['cpu_pct'] !== null ? $serverPeak['cpu_pct'] . '%' : '—' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Load: {{ number_format($serverStats['load1'] ?? 0, 2) }} / {{ number_format($serverStats['load5'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pico RAM (7d)</p>
                <p class="text-xl font-black text-emerald-500">{{ $serverPeak['ram_used_mib'] !== null ? number_format($serverPeak['ram_used_mib']) . ' MiB' : '—' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $serverStats['cores'] ?? 0 }} cores · uptime {{ gmdate('d\d H\h', (int) ($serverStats['uptime'] ?? 0)) }}</p>
            </div>
        </div>

        <div wire:ignore>
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Últimas 24 horas</p>
                <a href="http://127.0.0.1:19999" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-[#00C4FF] hover:underline">Panel completo netdata <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
            </div>
            <div style="position:relative;height:200px">
                <canvas id="serverChart" style="height:100%"></canvas>
            </div>
        </div>
        @else
        <p class="text-sm text-gray-500">No se puede conectar con netdata. Verificar que el servicio esté instalado y activo en el puerto 19999.</p>
        @endif
    </div>

    {{-- ==================== MÉTRICAS DE NEGOCIO (ANALYTICS) ==================== --}}
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Métricas de negocio</h2>
        <span class="text-xs font-bold px-2 py-1 rounded bg-[#00C4FF]/10 text-[#00C4FF] uppercase tracking-wider">Analytics</span>
    </div>

    @if(count($correlationNotes) > 0)
    <div class="mb-6 space-y-2">
        @foreach($correlationNotes as $note)
        <div class="px-4 py-3 rounded-xl text-sm font-bold
            {{ $note['type'] === 'warning' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : '' }}
            {{ $note['type'] === 'info' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : '' }}
            {{ $note['type'] === 'external' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : '' }}
        ">
            <span class="font-black">{{ $note['title'] }}</span>: {{ $note['description'] }}
        </div>
        @endforeach
    </div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Ingresos --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ingresos</p>
                    <p class="text-base sm:text-2xl font-black text-[#00C4FF] mt-1">₡{{ number_format($revenueData['total_revenue'] ?? 0) }}</p>
                </div>
                @if(isset($growth['revenue']) && $growth['revenue'] != 0)
                <span class="text-xs font-bold px-2 py-1 rounded {{ $growth['revenue'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $growth['revenue'] > 0 ? '+' : '' }}{{ $growth['revenue'] }}%
                </span>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex flex-wrap gap-4 text-xs text-gray-500">
                <span>{{ $revenueData['total_invoices'] ?? 0 }} facturas</span>
                <span>₡{{ number_format($revenueData['avg_order_value'] ?? 0) }} x orden</span>
            </div>
        </div>

        {{-- Utilidad --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Utilidad estimada</p>
                    <p class="text-base sm:text-2xl font-black text-emerald-500 mt-1">₡{{ number_format($revenueData['total_utility'] ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex flex-wrap gap-4 text-xs text-gray-500">
                <span>{{ $revenueData['total_invoices'] ?? 0 }} facturas</span>
                <span>{{ ($revenueData['total_revenue'] ?? 0) > 0 ? number_format((($revenueData['total_utility'] ?? 0) / $revenueData['total_revenue']) * 100, 1) : 0 }}% margen</span>
            </div>
        </div>

        {{-- Tráfico web --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tráfico web</p>
                    <p class="text-base sm:text-2xl font-black text-green-500 mt-1">{{ number_format($analyticsSummary['total_users'] ?? 0) }} usuarios</p>
                </div>
                <div class="flex flex-col items-end gap-1">
                    @if(isset($growth['ga_users']) && $growth['ga_users'] != 0)
                    <span class="text-xs font-bold px-2 py-1 rounded {{ $growth['ga_users'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $growth['ga_users'] > 0 ? '+' : '' }}{{ $growth['ga_users'] }}%
                    </span>
                    @endif
                    @if($realtimeUsers !== null)
                    <span class="text-xs font-bold px-2 py-1 rounded bg-green-100 text-green-600">{{ $realtimeUsers }} ahora</span>
                    @endif
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex flex-wrap gap-4 text-xs text-gray-500">
                <span>{{ number_format($analyticsSummary['total_sessions'] ?? 0) }} sesiones</span>
                <span>{{ number_format($analyticsSummary['avg_bounce_rate'] ?? 0, 1) }}% rebote</span>
            </div>
        </div>

        {{-- Campañas activas --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Campañas activas</p>
                    <p class="text-base sm:text-2xl font-black text-blue-500 mt-1">{{ count($adsPerformance['by_campaign'] ?? []) + count($fbAdsPerformance['by_campaign'] ?? []) }}</p>
                </div>
                @if(isset($growth['ads_clicks']) && $growth['ads_clicks'] != 0)
                <span class="text-xs font-bold px-2 py-1 rounded {{ $growth['ads_clicks'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $growth['ads_clicks'] > 0 ? '+' : '' }}{{ $growth['ads_clicks'] }}%
                </span>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex flex-wrap gap-4 text-xs text-gray-500">
                <span>₡{{ number_format(($adsPerformance['total_cost'] ?? 0) + ($fbAdsPerformance['total_spend'] ?? 0)) }} gastado</span>
                <span>{{ number_format(($adsPerformance['total_clicks'] ?? 0) + ($fbAdsPerformance['total_clicks'] ?? 0)) }} clics</span>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    @if(count($trafficSources) > 0)
    <div class="mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6" wire:ignore>
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Tráfico por fuente</h2>
            <div style="position:relative;height:220px;max-width:420px;margin-left:auto;margin-right:auto">
                <canvas id="trafficChart" style="height:100%"></canvas>
            </div>
        </div>
    </div>
    @endif

    {{-- Google Analytics + Search Console --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Google Analytics</h2>
                <div class="flex items-center gap-2">
                    @if($realtimeUsers !== null)
                    <span class="text-xs font-bold px-2 py-1 rounded bg-green-100 text-green-600">{{ $realtimeUsers }} ahora</span>
                    @endif
                    <button wire:click="testGaConnection" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-200 dark:border-white/10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="testGaConnection">Probar conexion</span>
                        <span wire:loading wire:target="testGaConnection">...</span>
                    </button>
                    <button wire:click="syncGoogleAnalytics" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="syncGoogleAnalytics">Actualizar datos</span>
                        <span wire:loading wire:target="syncGoogleAnalytics">...</span>
                    </button>
                </div>
            </div>
            @if($gaConnectionTest)
            <div class="mb-3 px-3 py-2 rounded-lg text-xs font-bold {{ $gaConnectionTest['ok'] ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' }}">
                {{ $gaConnectionTest['message'] }}
            </div>
            @endif
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Sesiones</p>
                    <p class="text-base sm:text-xl font-black text-gray-900 dark:text-white">{{ number_format($analyticsSummary['total_sessions'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Usuarios</p>
                    <p class="text-base sm:text-xl font-black text-gray-900 dark:text-white">{{ number_format($analyticsSummary['total_users'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Nuevos</p>
                    <p class="text-base sm:text-xl font-black text-gray-900 dark:text-white">{{ number_format($analyticsSummary['total_new_users'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Páginas/sesión</p>
                    <p class="text-base sm:text-xl font-black text-gray-900 dark:text-white">
                        @php $sessions = $analyticsSummary['total_sessions'] ?? 0; @endphp
                        {{ $sessions > 0 ? number_format(($analyticsSummary['total_pageviews'] ?? 0) / $sessions, 1) : 0 }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Bounce Rate</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($analyticsSummary['avg_bounce_rate'] ?? 0, 1) }}%</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Duración media</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ gmdate('i:s', (int) ($analyticsSummary['avg_session_duration'] ?? 0)) }}</p>
                </div>
            </div>

            @if(count($deviceBreakdown) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Dispositivos</h3>
            <div class="flex gap-2 mb-4">
                @foreach($deviceBreakdown as $dev)
                @php $total = collect($deviceBreakdown)->sum('users'); @endphp
                @php $pct = $total > 0 ? round(($dev['users'] / $total) * 100) : 0; @endphp
                <div class="flex-1 text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                    <p class="text-xs text-gray-500">{{ $dev['category'] }}</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $dev['users'] }}</p>
                    <p class="text-xs text-gray-400">{{ $pct }}%</p>
                </div>
                @endforeach
            </div>
            @endif

            @if(count($topPages) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Páginas</h3>
            <div class="space-y-1 max-h-32 overflow-y-auto">
                @foreach($topPages as $page)
                <div class="flex justify-between text-xs">
                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-[220px]">{{ $page['path'] }}</span>
                    <span class="text-gray-500 font-medium">{{ $page['views'] }} vistas</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Search Console</h2>
                <div class="flex items-center gap-2">
                    <button wire:click="testScConnection" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-200 dark:border-white/10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="testScConnection">Probar conexion</span>
                        <span wire:loading wire:target="testScConnection">...</span>
                    </button>
                    <button wire:click="syncSearchConsole" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="syncSearchConsole">Actualizar datos</span>
                        <span wire:loading wire:target="syncSearchConsole">...</span>
                    </button>
                </div>
            </div>
            @if($scConnectionTest)
            <div class="mb-3 px-3 py-2 rounded-lg text-xs font-bold {{ $scConnectionTest['ok'] ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' }}">
                {{ $scConnectionTest['message'] }}
            </div>
            @endif
            <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Clics</p>
                    <p class="text-base sm:text-xl font-black text-gray-900 dark:text-white">{{ number_format($searchConsoleSummary['total_clicks'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Impresiones</p>
                    <p class="text-base sm:text-xl font-black text-gray-900 dark:text-white">{{ number_format($searchConsoleSummary['total_impressions'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Posición</p>
                    <p class="text-base sm:text-xl font-black text-gray-900 dark:text-white">{{ number_format($searchConsoleSummary['avg_position'] ?? 0, 1) }}</p>
                </div>
            </div>

            @if(count($searchConsoleByDevice) > 0)
            <div class="flex gap-2 mb-4">
                @foreach($searchConsoleByDevice as $device => $data)
                <div class="flex-1 text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                    <p class="text-xs text-gray-500 mb-1">{{ $device ? ucfirst($device) : 'N/A' }}</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $data['clicks'] }}</p>
                    <p class="text-xs text-gray-400">pos {{ number_format($data['avg_position'] ?? 0, 1) }}</p>
                </div>
                @endforeach
            </div>
            @endif

            @if(count($searchConsoleByCountry) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Países</h3>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($searchConsoleByCountry as $country => $data)
                <span class="text-xs px-2 py-1 bg-gray-50 dark:bg-white/5 rounded-lg text-gray-700 dark:text-gray-300">
                    {{ $country ?: 'Desconocido' }} <strong>{{ $data['clicks'] }}</strong>
                </span>
                @endforeach
            </div>
            @endif

            @if(count($searchConsoleSummary['top_queries'] ?? []) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Top consultas</h3>
            <div class="space-y-1 max-h-32 overflow-y-auto">
                @foreach($searchConsoleSummary['top_queries'] as $query => $data)
                <div class="flex justify-between text-xs">
                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-[200px]">{{ $query }}</span>
                    <span class="text-gray-500">{{ $data['clicks'] }} clics · pos {{ number_format($data['avg_position'], 1) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Campañas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Google Ads</h2>
                <div class="flex items-center gap-2">
                    <button wire:click="testAdsConnection" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-200 dark:border-white/10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="testAdsConnection">Probar conexion</span>
                        <span wire:loading wire:target="testAdsConnection">...</span>
                    </button>
                    <button wire:click="syncGoogleAds" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="syncGoogleAds">Actualizar datos</span>
                        <span wire:loading wire:target="syncGoogleAds">...</span>
                    </button>
                </div>
            </div>
            @if($adsConnectionTest)
            <div class="mb-3 px-3 py-2 rounded-lg text-xs font-bold {{ $adsConnectionTest['ok'] ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' }}">
                {{ $adsConnectionTest['message'] }}
            </div>
            @endif
            @if(count($adsPerformance['by_campaign'] ?? []) > 0)
            <div class="space-y-3">
                @foreach($adsPerformance['by_campaign'] as $name => $campaign)
                <div class="border-b border-gray-100 dark:border-white/5 pb-3 last:border-0 last:pb-0">
                    <p class="font-bold text-sm text-gray-900 dark:text-white truncate">{{ $name }}</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-xs text-gray-500">
                        <span>{{ number_format($campaign['impressions']) }} imp.</span>
                        <span>{{ number_format($campaign['clicks']) }} clics</span>
                        <span>₡{{ number_format($campaign['cost'], 0) }}</span>
                        <span>{{ number_format($campaign['conversions'], 1) }} conv.</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin campañas activas o sin datos sincronizados.</p>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Meta Ads</h2>
                <div class="flex items-center gap-2">
                    <button wire:click="testFbConnection" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-200 dark:border-white/10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="testFbConnection">Probar conexion</span>
                        <span wire:loading wire:target="testFbConnection">...</span>
                    </button>
                    <button wire:click="syncMetaAds" wire:loading.attr="disabled" class="text-[10px] font-bold px-2 py-1 rounded-lg bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="syncMetaAds">Actualizar datos</span>
                        <span wire:loading wire:target="syncMetaAds">...</span>
                    </button>
                </div>
            </div>
            @if($fbConnectionTest)
            <div class="mb-3 px-3 py-2 rounded-lg text-xs font-bold {{ $fbConnectionTest['ok'] ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' }}">
                {{ $fbConnectionTest['message'] }}
            </div>
            @endif
            @if(count($fbAdsPerformance['by_campaign'] ?? []) > 0)
            <div class="space-y-3">
                @foreach($fbAdsPerformance['by_campaign'] as $name => $campaign)
                <div class="border-b border-gray-100 dark:border-white/5 pb-3 last:border-0 last:pb-0">
                    <p class="font-bold text-sm text-gray-900 dark:text-white truncate">{{ $name }}</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-xs text-gray-500">
                        <span>{{ number_format($campaign['impressions']) }} imp.</span>
                        <span>{{ number_format($campaign['clicks']) }} clics</span>
                        <span>₡{{ number_format($campaign['spend'], 0) }}</span>
                        <span>{{ number_format($campaign['reach']) }} alcance</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin campañas activas o sin datos sincronizados.</p>
            @endif
        </div>
    </div>

    {{-- ==================== REDES SOCIALES + SEARCH CONSOLE ==================== --}}
    <div class="flex items-center gap-3 mb-4 mt-8">
        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Redes Sociales</h2>
        <span class="text-xs font-bold px-2 py-1 rounded bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400 uppercase tracking-wider">Search Console</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Instagram --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400 flex items-center justify-center text-white">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Instagram</h3>
                        <a href="https://instagram.com/invictacr_" target="_blank" rel="noopener noreferrer" class="text-[10px] text-[#00C4FF] hover:underline">@invictacr_ <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i></a>
                    </div>
                </div>
            </div>
            @if(isset($socialPropertyStats['instagram']) && ($socialPropertyStats['instagram']['clicks'] > 0 || $socialPropertyStats['instagram']['impressions'] > 0))
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Clics</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['instagram']['clicks']) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Impresiones</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['instagram']['impressions']) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">CTR</p>
                    <p class="text-lg font-black text-[#00C4FF]">{{ number_format($socialPropertyStats['instagram']['avg_ctr'] ?? 0, 1) }}%</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Posición</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['instagram']['avg_position'] ?? 0, 1) }}</p>
                </div>
            </div>
            @if(count($socialPropertyStats['instagram']['top_queries'] ?? []) > 0)
            <h4 class="font-bold text-[10px] text-gray-500 uppercase tracking-wider mb-1.5">Top consultas</h4>
            <div class="space-y-1">
                @foreach($socialPropertyStats['instagram']['top_queries'] as $query => $data)
                <div class="flex justify-between text-[11px]">
                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-[160px]">{{ $query }}</span>
                    <span class="text-gray-500">{{ $data['clicks'] }} clics</span>
                </div>
                @endforeach
            </div>
            @endif
            @else
            <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4">Sin datos de Search Console sincronizados aún.</p>
            @endif
        </div>

        {{-- TikTok --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-white">
                        <i class="fa-brands fa-tiktok text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">TikTok</h3>
                        <a href="https://tiktok.com/@invictacr" target="_blank" rel="noopener noreferrer" class="text-[10px] text-[#00C4FF] hover:underline">@invictacr <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i></a>
                    </div>
                </div>
            </div>
            @if(isset($socialPropertyStats['tiktok']) && ($socialPropertyStats['tiktok']['clicks'] > 0 || $socialPropertyStats['tiktok']['impressions'] > 0))
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Clics</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['tiktok']['clicks']) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Impresiones</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['tiktok']['impressions']) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">CTR</p>
                    <p class="text-lg font-black text-[#00C4FF]">{{ number_format($socialPropertyStats['tiktok']['avg_ctr'] ?? 0, 1) }}%</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Posición</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['tiktok']['avg_position'] ?? 0, 1) }}</p>
                </div>
            </div>
            @if(count($socialPropertyStats['tiktok']['top_queries'] ?? []) > 0)
            <h4 class="font-bold text-[10px] text-gray-500 uppercase tracking-wider mb-1.5">Top consultas</h4>
            <div class="space-y-1">
                @foreach($socialPropertyStats['tiktok']['top_queries'] as $query => $data)
                <div class="flex justify-between text-[11px]">
                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-[160px]">{{ $query }}</span>
                    <span class="text-gray-500">{{ $data['clicks'] }} clics</span>
                </div>
                @endforeach
            </div>
            @endif
            @else
            <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4">Sin datos de Search Console sincronizados aún.</p>
            @endif
        </div>

        {{-- YouTube --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white">
                        <i class="fa-brands fa-youtube text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">YouTube</h3>
                        <a href="https://youtube.com/@invicta_cr" target="_blank" rel="noopener noreferrer" class="text-[10px] text-[#00C4FF] hover:underline">@invicta_cr <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i></a>
                    </div>
                </div>
            </div>
            @if(isset($socialPropertyStats['youtube']) && ($socialPropertyStats['youtube']['clicks'] > 0 || $socialPropertyStats['youtube']['impressions'] > 0))
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Clics</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['youtube']['clicks']) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Impresiones</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['youtube']['impressions']) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">CTR</p>
                    <p class="text-lg font-black text-[#00C4FF]">{{ number_format($socialPropertyStats['youtube']['avg_ctr'] ?? 0, 1) }}%</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Posición</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($socialPropertyStats['youtube']['avg_position'] ?? 0, 1) }}</p>
                </div>
            </div>
            @if(count($socialPropertyStats['youtube']['top_queries'] ?? []) > 0)
            <h4 class="font-bold text-[10px] text-gray-500 uppercase tracking-wider mb-1.5">Top consultas</h4>
            <div class="space-y-1">
                @foreach($socialPropertyStats['youtube']['top_queries'] as $query => $data)
                <div class="flex justify-between text-[11px]">
                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-[160px]">{{ $query }}</span>
                    <span class="text-gray-500">{{ $data['clicks'] }} clics</span>
                </div>
                @endforeach
            </div>
            @endif
            @else
            <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4">Sin datos de Search Console sincronizados aún.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    let trafficChartInstance = null;

    function initCharts() {
        // Traffic Sources Doughnut
        const trafficCanvas = document.getElementById('trafficChart');
        if (trafficCanvas) {
            const srcLabels = @json(array_map(fn($s) => $s['source'] ?: '(direct)', $trafficSources));
            const srcData = @json(array_map(fn($s) => $s['users'], $trafficSources));
            const colors = [
                '#00C4FF', '#f97316', '#22c55e', '#ef4444', '#a855f7',
                '#eab308', '#06b6d4', '#ec4899'
            ];
            if (trafficChartInstance) trafficChartInstance.destroy();
            trafficChartInstance = new Chart(trafficCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: srcLabels,
                    datasets: [{
                        data: srcData,
                        backgroundColor: colors.slice(0, srcLabels.length),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 8,
                                boxWidth: 10,
                                font: { size: 10 },
                                color: '#9ca3af',
                            }
                        }
                    }
                }
            });
        }
    }

    let serverChartInstance = null;

    function initServerChart() {
        const serverCanvas = document.getElementById('serverChart');
        if (!serverCanvas) return;

        const series = @json($serverSeries);
        const labels = series.map(p => new Date(p.time * 1000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
        const cpuData = series.map(p => p.cpu);
        const ramData = series.map(p => p.ram);

        if (serverChartInstance) serverChartInstance.destroy();
        serverChartInstance = new Chart(serverCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'CPU %',
                        data: cpuData,
                        borderColor: '#00C4FF',
                        backgroundColor: 'rgba(0,196,255,0.08)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0,
                        borderWidth: 2,
                        yAxisID: 'y',
                    },
                    {
                        label: 'RAM MiB',
                        data: ramData,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.06)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0,
                        borderWidth: 2,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 8,
                            boxWidth: 10,
                            font: { size: 10 },
                            color: '#9ca3af',
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#9ca3af', maxTicksLimit: 8, font: { size: 10 } },
                        grid: { display: false }
                    },
                    y: {
                        position: 'left',
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: '#9ca3af', font: { size: 10 }, callback: v => v + '%' },
                        grid: { color: 'rgba(156,163,175,0.1)' }
                    },
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        ticks: { color: '#9ca3af', font: { size: 10 }, callback: v => v + 'M' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    document.addEventListener('livewire:init', () => {
        initCharts();
        setTimeout(initServerChart, 50);
    });
    document.addEventListener('livewire:updated', () => {
        setTimeout(initCharts, 100);
        setTimeout(initServerChart, 150);
    });
</script>
@endpush
