<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-2">
            <button wire:click="$set('period', '7d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '7d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">7 días</button>
            <button wire:click="$set('period', '30d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '30d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">30 días</button>
            <button wire:click="$set('period', '90d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '90d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">90 días</button>
            <button wire:click="$set('period', '365d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '365d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">1 año</button>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="syncAll" wire:loading.attr="disabled" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors bg-[#00C4FF] text-white hover:bg-[#00a8d6] disabled:opacity-50">
                <span wire:loading.remove wire:target="syncAll">Actualizar</span>
                <span wire:loading wire:target="syncAll">Sincronizando...</span>
            </button>
            <span class="text-sm text-gray-500">Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    {{-- Correlation Notes --}}
    @if(count($correlationNotes) > 0)
    <div class="mb-8 space-y-2">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
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
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Utilidad estimada</p>
                    <p class="text-2xl font-black text-emerald-500 mt-1">₡{{ number_format($revenueData['total_utility'] ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex gap-4 text-xs text-gray-500">
                <span>{{ $revenueData['total_invoices'] ?? 0 }} facturas</span>
                <span>{{ $revenueData['total_revenue'] > 0 ? number_format(($revenueData['total_utility'] / $revenueData['total_revenue']) * 100, 1) : 0 }}% margen</span>
            </div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios (GA)</p>
                    <p class="text-2xl font-black text-green-500 mt-1">{{ $analyticsSummary['total_users'] ?? 0 }}</p>
                </div>
                @if(isset($growth['ga_users']) && $growth['ga_users'] != 0)
                <span class="text-xs font-bold px-2 py-1 rounded {{ $growth['ga_users'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $growth['ga_users'] > 0 ? '+' : '' }}{{ $growth['ga_users'] }}%
                </span>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex gap-4 text-xs text-gray-500">
                <span>{{ $analyticsSummary['total_sessions'] ?? 0 }} sesiones</span>
                <span>{{ number_format($analyticsSummary['avg_bounce_rate'] ?? 0, 1) }}% rebote</span>
            </div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tráfico de pago</p>
                    <p class="text-2xl font-black text-blue-500 mt-1">{{ $adsPerformance['total_clicks'] ?? 0 }}</p>
                </div>
                @if(isset($growth['ads_clicks']) && $growth['ads_clicks'] != 0)
                <span class="text-xs font-bold px-2 py-1 rounded {{ $growth['ads_clicks'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $growth['ads_clicks'] > 0 ? '+' : '' }}{{ $growth['ads_clicks'] }}%
                </span>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/5 flex gap-4 text-xs text-gray-500">
                <span>₡{{ number_format($adsPerformance['total_cost'] ?? 0) }} gastado</span>
                @php $cost = $adsPerformance['total_cost'] ?? 0; @endphp
                @php $value = $adsPerformance['total_conversion_value'] ?? 0; @endphp
                <span>ROAS {{ $cost > 0 ? number_format($value / $cost, 2) : 'N/A' }}x</span>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    @if(count($revenueData['weekly'] ?? []) > 0 || count($trafficSources) > 0 || count($deviceBreakdown) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
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

    {{-- GA + Devices --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Google Analytics</h2>
                @if($realtimeUsers !== null)
                <span class="text-xs font-bold px-2 py-1 rounded bg-green-100 text-green-600">{{ $realtimeUsers }} ahora</span>
                @endif
            </div>
            <div class="grid grid-cols-4 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Sesiones</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $analyticsSummary['total_sessions'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Usuarios</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $analyticsSummary['total_users'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Nuevos</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $analyticsSummary['total_new_users'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Páginas/sesión</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">
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
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ gmdate('i:s', $analyticsSummary['avg_session_duration'] ?? 0) }}</p>
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

            @if(count($trafficSources) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Tráfico</h3>
            <div class="space-y-1 mb-4">
                @php $maxUsers = max(array_column($trafficSources, 'users')); @endphp
                @foreach($trafficSources as $src)
                <div class="flex items-center gap-2 text-xs">
                    <span class="text-gray-700 dark:text-gray-300 w-24 truncate">{{ $src['source'] ?: '(direct)' }}</span>
                    <div class="flex-1 h-1.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $maxUsers > 0 ? ($src['users'] / $maxUsers) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-gray-500 w-16 text-right">{{ $src['users'] }}</span>
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
    </div>

    {{-- Ads + Search Console --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Google Ads</h2>
            @if(count($adsPerformance['by_campaign'] ?? []) > 0)
            <div class="space-y-3">
                @foreach($adsPerformance['by_campaign'] as $name => $campaign)
                <div class="border-b border-gray-100 dark:border-white/5 pb-3 last:border-0 last:pb-0">
                    <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $name }}</p>
                    <div class="flex gap-4 mt-1 text-xs text-gray-500">
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
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Search Console</h2>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Clics</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $searchConsoleSummary['total_clicks'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Impresiones</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($searchConsoleSummary['total_impressions'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Posición</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($searchConsoleSummary['avg_position'] ?? 0, 1) }}</p>
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

    {{-- Facebook + GitHub --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Facebook / Meta Business</h2>

            {{-- Organic --}}
            <div class="grid grid-cols-4 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Alcance</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_impressions'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Interacciones</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_engagement'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Vistas</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_views'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Posts</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ $facebookSummary['posts_count'] ?? 0 }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                    <p class="text-xs text-gray-500">Reacciones</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_reactions'] ?? 0) }}</p>
                </div>
                <div class="text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                    <p class="text-xs text-gray-500">Comentarios</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_comments'] ?? 0) }}</p>
                </div>
                <div class="text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                    <p class="text-xs text-gray-500">Compartidos</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_shares'] ?? 0) }}</p>
                </div>
            </div>

            {{-- Facebook Ads --}}
            @if(count($fbAdsPerformance['by_campaign'] ?? []) > 0)
            <div class="border-t border-gray-200 dark:border-white/10 pt-4 mb-4">
                <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-3">Gastos en publicidad</h3>
                <div class="grid grid-cols-4 gap-2 mb-3">
                    <div class="text-center p-2 bg-red-50 dark:bg-red-900/10 rounded-xl">
                        <p class="text-xs text-gray-500">Gastado</p>
                        <p class="text-sm font-black text-red-500">₡{{ number_format($fbAdsPerformance['total_spend'] ?? 0, 2) }}</p>
                    </div>
                    <div class="text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                        <p class="text-xs text-gray-500">Impresiones</p>
                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($fbAdsPerformance['total_impressions'] ?? 0) }}</p>
                    </div>
                    <div class="text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                        <p class="text-xs text-gray-500">Clics</p>
                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($fbAdsPerformance['total_clicks'] ?? 0) }}</p>
                    </div>
                    <div class="text-center p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                        <p class="text-xs text-gray-500">Alcance</p>
                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($fbAdsPerformance['total_reach'] ?? 0) }}</p>
                    </div>
                </div>
                @php $totalSpend = $fbAdsPerformance['total_spend'] ?? 0; @endphp
                <div class="grid grid-cols-3 gap-2 mb-3 text-xs text-gray-500 text-center">
                    <span>CPM: ₡{{ number_format($fbAdsPerformance['avg_cpm'] ?? 0, 2) }}</span>
                    <span>CPC: ₡{{ number_format($fbAdsPerformance['avg_cpc'] ?? 0, 2) }}</span>
                    <span>CTR: {{ number_format($fbAdsPerformance['avg_ctr'] ?? 0, 2) }}%</span>
                </div>
                @php $maxCamp = max(array_column($fbAdsPerformance['by_campaign'], 'spend')); @endphp
                @foreach($fbAdsPerformance['by_campaign'] as $name => $camp)
                <div class="flex items-center gap-2 text-xs mb-1">
                    <span class="text-gray-700 dark:text-gray-300 w-28 truncate">{{ $name }}</span>
                    <div class="flex-1 h-1.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-red-400 rounded-full" style="width: {{ $maxCamp > 0 ? ($camp['spend'] / $maxCamp) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-gray-500 w-16 text-right">₡{{ number_format($camp['spend'], 1) }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if(count($facebookSummary['recent_posts'] ?? []) > 0)
            <div class="border-t border-gray-200 dark:border-white/10 pt-4">
                <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Últimos posts</h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($facebookSummary['recent_posts'] as $post)
                    <div class="text-xs pb-2 border-b border-gray-100 dark:border-white/5 last:border-0">
                        <div class="flex items-start justify-between">
                            <p class="text-gray-700 dark:text-gray-300 truncate flex-1">{{ $post['message'] ?? 'Sin texto' }}</p>
                            @if($post['likes'] > 0 || $post['comments'] > 0 || $post['shares'] > 0)
                            <span class="text-xs text-gray-400 ml-2 shrink-0">{{ $post['likes'] + $post['comments'] + $post['shares'] }} int.</span>
                            @endif
                        </div>
                        <p class="text-gray-500 mt-1 flex gap-2 text-xs">
                            <span>{{ $post['posted_at'] ? \Carbon\Carbon::parse($post['posted_at'])->format('d/m/Y') : '' }}</span>
                            <span>❤️ {{ $post['likes'] ?? 0 }}</span>
                            <span>💬 {{ $post['comments'] ?? 0 }}</span>
                            <span>🔄 {{ $post['shares'] ?? 0 }}</span>
                            <span>👁️ {{ number_format($post['reach'] ?? 0) }}</span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">GitHub - Cambios del código</h2>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Commits</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $gitHubSummary['total_commits'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">+ Líneas</p>
                    <p class="text-xl font-black text-green-500">{{ number_format($gitHubSummary['total_additions'] ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                    <p class="text-xs text-gray-500">- Líneas</p>
                    <p class="text-xl font-black text-red-500">{{ number_format($gitHubSummary['total_deletions'] ?? 0) }}</p>
                </div>
            </div>
            @if(count($gitHubSummary['recent_commits'] ?? []) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Últimos cambios</h3>
            <div class="space-y-2 max-h-48 overflow-y-auto">
                @foreach($gitHubSummary['recent_commits'] as $commit)
                <div class="text-xs pb-2 border-b border-gray-100 dark:border-white/5 last:border-0">
                    <p class="text-gray-700 dark:text-gray-300 truncate">{{ $commit['message'] }}</p>
                    <p class="text-gray-500">
                        {{ $commit['author_name'] }} · {{ $commit['committed_at'] ? \Carbon\Carbon::parse($commit['committed_at'])->format('d/m/Y H:i') : '' }}
                        · +{{ $commit['additions'] }}/-{{ $commit['deletions'] }} · {{ $commit['files_changed'] }} archivos
                        @if(str_contains(strtolower($commit['message']), 'deploy') || str_contains(strtolower($commit['message']), 'release'))
                        <span class="text-green-500 font-bold ml-1">DEPLOY</span>
                        @endif
                    </p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Top Products --}}
    @if(count($topProducts) > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Productos más vendidos</h2>
            <span class="text-xs text-gray-500">{{ count($topProducts) }} productos</span>
        </div>
        <div class="space-y-2">
            @foreach($topProducts as $i => $product)
            <div class="flex items-center gap-3 text-sm">
                <span class="w-5 text-center font-bold text-gray-400 text-xs">#{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-gray-800 dark:text-gray-200 truncate font-medium">{{ $product['product_name'] }}</p>
                </div>
                <span class="font-bold text-gray-900 dark:text-white">{{ $product['total_qty'] }} uds</span>
                <span class="text-[#00C4FF] font-bold w-28 text-right">₡{{ number_format($product['total_revenue']) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- External Factors --}}
    @if(count($externalFactors) > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Factores externos</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($externalFactors as $factor)
            <div class="border border-gray-200 dark:border-white/10 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-black px-2 py-1 rounded
                        {{ $factor['impact_level'] === 'high' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '' }}
                        {{ $factor['impact_level'] === 'medium' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                        {{ $factor['impact_level'] === 'low' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                        {{ $factor['impact_level'] === 'positive' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : '' }}
                    ">{{ strtoupper($factor['impact_level']) }}</span>
                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($factor['event_date'])->format('d/m/Y') }}</span>
                </div>
                <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $factor['title'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $factor['description'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Fuente: {{ $factor['source'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    function initCharts() {
        // Weekly Revenue Bar Chart
        const revCanvas = document.getElementById('weeklyRevenueChart');
        if (revCanvas) {
            const weekly = @json($revenueData['weekly'] ?? []);
            const revLabels = weekly.map(w => w.label);
            const revData = weekly.map(w => w.total);
            const revCtx = revCanvas.getContext('2d');
            new Chart(revCtx, {
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
            const trafficCtx = trafficCanvas.getContext('2d');
            new Chart(trafficCtx, {
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
