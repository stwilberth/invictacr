<div>
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Dashboard</h1>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex gap-2">
                <button wire:click="$set('period', '7d')" class="px-3 py-2 rounded-xl text-xs font-bold transition-colors {{ $period === '7d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">7 días</button>
                <button wire:click="$set('period', '30d')" class="px-3 py-2 rounded-xl text-xs font-bold transition-colors {{ $period === '30d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">30 días</button>
                <button wire:click="$set('period', '90d')" class="px-3 py-2 rounded-xl text-xs font-bold transition-colors {{ $period === '90d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">90 días</button>
                <button wire:click="$set('period', '365d')" class="px-3 py-2 rounded-xl text-xs font-bold transition-colors {{ $period === '365d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">1 año</button>
            </div>
            <button wire:click="syncData" wire:loading.attr="disabled" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors bg-[#00C4FF] text-white hover:bg-[#00a8d6] disabled:opacity-50">
                <span wire:loading.remove wire:target="syncData">Actualizar datos</span>
                <span wire:loading wire:target="syncData">Sincronizando...</span>
            </button>
        </div>
    </div>

    {{-- ==================== GESTIÓN INTERNA (ADMIN) ==================== --}}
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Gestión interna</h2>
        <span class="text-xs font-bold px-2 py-1 rounded bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400 uppercase tracking-wider">Admin</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Usuarios --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios</p>
                    <p class="text-2xl font-black text-[#00C4FF] mt-1">{{ number_format($userStats['total'] ?? 0) }}</p>
                </div>
                <i class="fa-solid fa-users text-gray-300 dark:text-gray-600 text-xl"></i>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex justify-between items-center text-xs text-gray-500">
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

        {{-- Inventario --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Inventario</p>
                    <p class="text-2xl font-black text-blue-500 mt-1">{{ number_format($inventory['active'] ?? 0) }}</p>
                </div>
                <i class="fa-solid fa-boxes-stacked text-gray-300 dark:text-gray-600 text-xl"></i>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex justify-between items-center text-xs text-gray-500">
                <span>{{ $inventory['upcoming'] ?? 0 }} próximos · ₡{{ number_format($inventory['value'] ?? 0) }} en stock</span>
                <a href="{{ route('admin.products') }}" class="font-bold text-[#00C4FF] hover:underline">Ver <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
        </div>

        {{-- Alertas de stock --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between mb-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">Alertas de stock</p>
                <i class="fa-solid fa-triangle-exclamation text-gray-300 dark:text-gray-600 text-xl"></i>
            </div>
            <div class="flex gap-2 mb-3">
                <div class="flex-1 text-center bg-amber-50 dark:bg-amber-900/10 rounded-xl p-2">
                    <p class="text-lg font-black text-amber-500">{{ $stockAlerts['low'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Stock bajo</p>
                </div>
                <div class="flex-1 text-center bg-red-50 dark:bg-red-900/10 rounded-xl p-2">
                    <p class="text-lg font-black text-red-500">{{ $stockAlerts['out'] ?? 0 }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Agotados</p>
                </div>
            </div>
            @if(count($stockAlerts['products'] ?? []) > 0)
            <div class="space-y-1">
                @foreach($stockAlerts['products'] as $alert)
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-700 dark:text-gray-300 truncate">{{ $alert['name'] }}</span>
                    <span class="font-bold shrink-0 ml-2 {{ $alert['agotado'] ? 'text-red-500' : 'text-amber-500' }}">
                        {{ $alert['agotado'] ? 'Agotado' : $alert['stock'] . ' uds' }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-emerald-500 font-bold">Sin alertas. Inventario saludable.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
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
    </div>

    @if(count($recentSyncs) > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
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

    @if($devToolsMessage)
    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-sm font-bold text-emerald-700 dark:text-emerald-400">
        {{ $devToolsMessage }}
    </div>
    @endif
    @if($devToolsError)
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold text-red-700 dark:text-red-400">
        {{ $devToolsError }}
    </div>
    @endif

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-10">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Herramientas de desarrollo</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-white/5">
                <div>
                    <p class="font-bold text-gray-900 dark:text-white text-sm">Code Server <span class="text-xs font-normal text-gray-500">(IDE en navegador)</span></p>
                    <a href="https://code.invictacostarica.com" target="_blank" rel="noopener noreferrer" class="text-xs text-[#00C4FF] hover:underline">code.invictacostarica.com <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                    <p class="text-xs mt-1">
                        Estado: <span class="font-bold {{ $codeServerStatus === 'active' ? 'text-emerald-600' : 'text-red-500' }}">{{ $codeServerStatus }}</span>
                    </p>
                </div>
                <button wire:click="toggleDevTool('code-server@bitnami')" wire:loading.attr="disabled" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $codeServerStatus === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400' : 'bg-emerald-100 text-emerald-600 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400' }} disabled:opacity-50">
                    <span wire:loading.remove wire:target="toggleDevTool('{{ $codeServerStatus === 'active' ? 'stop' : 'start' }}')">{{ $codeServerStatus === 'active' ? 'Detener' : 'Iniciar' }}</span>
                    <span wire:loading wire:target="toggleDevTool('code-server@bitnami')">...</span>
                </button>
            </div>
            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-white/5">
                <div>
                    <p class="font-bold text-gray-900 dark:text-white text-sm">OpenCode Web <span class="text-xs font-normal text-gray-500">(agente IA en navegador)</span></p>
                    <a href="https://ide.invictacostarica.com" target="_blank" rel="noopener noreferrer" class="text-xs text-[#00C4FF] hover:underline">ide.invictacostarica.com <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                    <p class="text-xs mt-1">
                        Estado: <span class="font-bold {{ $opencodeWebStatus === 'active' ? 'text-emerald-600' : 'text-red-500' }}">{{ $opencodeWebStatus }}</span>
                    </p>
                </div>
                <button wire:click="toggleDevTool('opencode-web')" wire:loading.attr="disabled" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $opencodeWebStatus === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400' : 'bg-emerald-100 text-emerald-600 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400' }} disabled:opacity-50">
                    <span wire:loading.remove wire:target="toggleDevTool('opencode-web')">{{ $opencodeWebStatus === 'active' ? 'Detener' : 'Iniciar' }}</span>
                    <span wire:loading wire:target="toggleDevTool('opencode-web')">...</span>
                </button>
            </div>
        </div>
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
                    <p class="text-2xl font-black text-[#00C4FF] mt-1">₡{{ number_format($revenueData['total_revenue'] ?? 0) }}</p>
                </div>
                @if(isset($growth['revenue']) && $growth['revenue'] != 0)
                <span class="text-xs font-bold px-2 py-1 rounded {{ $growth['revenue'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $growth['revenue'] > 0 ? '+' : '' }}{{ $growth['revenue'] }}%
                </span>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex gap-4 text-xs text-gray-500">
                <span>{{ $revenueData['total_invoices'] ?? 0 }} facturas</span>
                <span>₡{{ number_format($revenueData['avg_order_value'] ?? 0) }} x orden</span>
            </div>
        </div>

        {{-- Utilidad --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Utilidad estimada</p>
                    <p class="text-2xl font-black text-emerald-500 mt-1">₡{{ number_format($revenueData['total_utility'] ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex gap-4 text-xs text-gray-500">
                <span>{{ $revenueData['total_invoices'] ?? 0 }} facturas</span>
                <span>{{ ($revenueData['total_revenue'] ?? 0) > 0 ? number_format((($revenueData['total_utility'] ?? 0) / $revenueData['total_revenue']) * 100, 1) : 0 }}% margen</span>
            </div>
        </div>

        {{-- Tráfico web --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tráfico web</p>
                    <p class="text-2xl font-black text-green-500 mt-1">{{ number_format($analyticsSummary['total_users'] ?? 0) }} usuarios</p>
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
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex gap-4 text-xs text-gray-500">
                <span>{{ number_format($analyticsSummary['total_sessions'] ?? 0) }} sesiones</span>
                <span>{{ number_format($analyticsSummary['avg_bounce_rate'] ?? 0, 1) }}% rebote</span>
            </div>
        </div>

        {{-- Campañas activas --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Campañas activas</p>
                    <p class="text-2xl font-black text-blue-500 mt-1">{{ count($adsPerformance['by_campaign'] ?? []) + count($fbAdsPerformance['by_campaign'] ?? []) }}</p>
                </div>
                @if(isset($growth['ads_clicks']) && $growth['ads_clicks'] != 0)
                <span class="text-xs font-bold px-2 py-1 rounded {{ $growth['ads_clicks'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $growth['ads_clicks'] > 0 ? '+' : '' }}{{ $growth['ads_clicks'] }}%
                </span>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex gap-4 text-xs text-gray-500">
                <span>₡{{ number_format(($adsPerformance['total_cost'] ?? 0) + ($fbAdsPerformance['total_spend'] ?? 0)) }} gastado</span>
                <span>{{ number_format(($adsPerformance['total_clicks'] ?? 0) + ($fbAdsPerformance['total_clicks'] ?? 0)) }} clics</span>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    @if(count($revenueData['weekly'] ?? []) > 0 || count($trafficSources) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 lg:col-span-2" wire:ignore>
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Ingresos semanales</h2>
            @if(count($revenueData['weekly'] ?? []) > 0)
            <div style="position:relative;height:220px">
                <canvas id="weeklyRevenueChart" style="height:100%"></canvas>
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin datos de ingresos en este período.</p>
            @endif
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6" wire:ignore>
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Tráfico por fuente</h2>
            @if(count($trafficSources) > 0)
            <div style="position:relative;height:220px">
                <canvas id="trafficChart" style="height:100%"></canvas>
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin datos de tráfico en este período.</p>
            @endif
        </div>
    </div>
    @endif

    {{-- Ventas por producto + campañas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Ventas por producto</h2>
                <span class="text-xs text-gray-500">{{ count($topProducts) }} productos</span>
            </div>
            @if(count($topProducts) > 0)
            <div class="space-y-2">
                @foreach($topProducts as $i => $product)
                <div class="flex items-center gap-3 text-sm">
                    <span class="w-5 text-center font-bold text-gray-400 text-xs">#{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-gray-800 dark:text-gray-200 truncate font-medium">{{ $product['product_name'] }}</p>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $product['total_qty'] }} uds</span>
                    <span class="text-[#00C4FF] font-bold w-24 text-right">₡{{ number_format($product['total_revenue']) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin ventas registradas en este período.</p>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Google Ads</h2>
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
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Meta Ads</h2>
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    let weeklyChartInstance = null;
    let trafficChartInstance = null;

    function initCharts() {
        // Weekly Revenue Bar Chart
        const revCanvas = document.getElementById('weeklyRevenueChart');
        if (revCanvas) {
            const weekly = @json($revenueData['weekly'] ?? []);
            const revLabels = weekly.map(w => w.label);
            const revData = weekly.map(w => w.total);
            if (weeklyChartInstance) weeklyChartInstance.destroy();
            weeklyChartInstance = new Chart(revCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: revLabels,
                    datasets: [{
                        label: 'Ingresos (₡)',
                        data: revData,
                        backgroundColor: 'rgba(0, 196, 255, 0.6)',
                        borderColor: 'rgba(0, 196, 255, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => '₡' + (v / 1000).toFixed(0) + 'k',
                                color: '#9ca3af',
                            },
                            grid: { color: 'rgba(255,255,255,0.05)' }
                        },
                        x: {
                            ticks: {
                                maxRotation: 0,
                                color: '#9ca3af',
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

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

    document.addEventListener('livewire:init', initCharts);
    document.addEventListener('livewire:updated', () => {
        setTimeout(initCharts, 100);
    });
</script>
@endpush
