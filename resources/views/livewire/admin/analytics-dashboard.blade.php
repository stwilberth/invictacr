<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-2">
            <button wire:click="$set('period', '7d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '7d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">7 días</button>
            <button wire:click="$set('period', '30d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '30d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">30 días</button>
            <button wire:click="$set('period', '90d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '90d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">90 días</button>
            <button wire:click="$set('period', '365d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '365d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">1 año</button>
        </div>
        <span class="text-sm text-gray-500">Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-[#00C4FF]">₡{{ number_format($revenueData['total_revenue'] ?? 0) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresos ({{ $revenueData['total_invoices'] ?? 0 }} facturas)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-green-500">{{ $analyticsSummary['total_users'] ?? 0 }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Usuarios (Google Analytics)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-blue-500">{{ $adsPerformance['total_clicks'] ?? 0 }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Clics en anuncios</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-purple-500">{{ $searchConsoleSummary['total_clicks'] ?? 0 }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Clics búsqueda orgánica</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Revenue Chart --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Ingresos diarios</h2>
            @if(count($revenueData['daily'] ?? []) > 0)
            <div class="space-y-1">
                @foreach($revenueData['daily'] as $date => $amount)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</span>
                    <div class="flex-1 mx-3">
                        <div class="h-2 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                            @php $max = max($revenueData['daily']->toArray()); @endphp
                            <div class="h-full bg-green-500 rounded-full" style="width: {{ $max > 0 ? ($amount / $max) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">₡{{ number_format($amount) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Sin datos de ingresos en este período.</p>
            @endif
        </div>

        {{-- Google Analytics --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Google Analytics</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sesiones</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $analyticsSummary['total_sessions'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pageviews</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $analyticsSummary['total_pageviews'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Bounce Rate</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($analyticsSummary['avg_bounce_rate'] ?? 0, 1) }}%</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Duración media</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ gmdate('i:s', $analyticsSummary['avg_session_duration'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ads & Search Console --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Google Ads</h2>
            @if(count($adsPerformance['by_campaign'] ?? []) > 0)
            <div class="space-y-3">
                @foreach($adsPerformance['by_campaign'] as $name => $campaign)
                <div class="border-b border-gray-100 dark:border-white/5 pb-3">
                    <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $name }}</p>
                    <div class="grid grid-cols-4 gap-2 mt-1 text-xs text-gray-500">
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
            <div class="mt-4 grid grid-cols-2 gap-4 pt-4 border-t border-gray-100 dark:border-white/5">
                <div>
                    <p class="text-xs text-gray-500">Inversión total</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">₡{{ number_format($adsPerformance['total_cost'] ?? 0, 0) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">ROAS</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">
                        @php $cost = $adsPerformance['total_cost'] ?? 0; @endphp
                        @php $value = $adsPerformance['total_conversion_value'] ?? 0; @endphp
                        {{ $cost > 0 ? number_format($value / $cost, 2) : 'N/A' }}x
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Search Console</h2>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500">Clics</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $searchConsoleSummary['total_clicks'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Impresiones</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($searchConsoleSummary['total_impressions'] ?? 0) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Posición media</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($searchConsoleSummary['avg_position'] ?? 0, 1) }}</p>
                </div>
            </div>
            @if(count($searchConsoleSummary['top_queries'] ?? []) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Top consultas</h3>
            <div class="space-y-1 max-h-40 overflow-y-auto">
                @foreach($searchConsoleSummary['top_queries'] as $query => $data)
                <div class="flex justify-between text-xs">
                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-[200px]">{{ $query }}</span>
                    <span class="text-gray-500">{{ $data['clicks'] }} clics | pos {{ number_format($data['avg_position'], 1) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Facebook & GitHub --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Facebook / Meta Business</h2>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500">Alcance</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_impressions'] ?? 0) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Interacciones</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($facebookSummary['total_engagement'] ?? 0) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Posts</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $facebookSummary['posts_count'] ?? 0 }}</p>
                </div>
            </div>
            @if(count($facebookSummary['recent_posts'] ?? []) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Últimos posts</h3>
            <div class="space-y-2 max-h-48 overflow-y-auto">
                @foreach($facebookSummary['recent_posts'] as $post)
                <div class="text-xs border-b border-gray-100 dark:border-white/5 pb-2">
                    <p class="text-gray-700 dark:text-gray-300 truncate">{{ $post['message'] ?? 'Sin texto' }}</p>
                    <p class="text-gray-500 mt-1">
                        {{ $post['posted_at'] ? \Carbon\Carbon::parse($post['posted_at'])->format('d/m/Y') : '' }}
                        · ❤️ {{ $post['likes'] ?? 0 }} · 💬 {{ $post['comments'] ?? 0 }} · 🔄 {{ $post['shares'] ?? 0 }}
                        · 👁️ {{ number_format($post['reach'] ?? 0) }}
                    </p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">GitHub - Cambios del código</h2>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500">Commits</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $gitHubSummary['total_commits'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">+ Líneas</p>
                    <p class="text-xl font-black text-green-500">{{ number_format($gitHubSummary['total_additions'] ?? 0) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">- Líneas</p>
                    <p class="text-xl font-black text-red-500">{{ number_format($gitHubSummary['total_deletions'] ?? 0) }}</p>
                </div>
            </div>
            @if(count($gitHubSummary['recent_commits'] ?? []) > 0)
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Últimos cambios</h3>
            <div class="space-y-2 max-h-48 overflow-y-auto">
                @foreach($gitHubSummary['recent_commits'] as $commit)
                <div class="text-xs border-b border-gray-100 dark:border-white/5 pb-2">
                    <p class="text-gray-700 dark:text-gray-300 truncate">{{ $commit['message'] }}</p>
                    <p class="text-gray-500">
                        {{ $commit['author_name'] }} · {{ $commit['committed_at'] ? \Carbon\Carbon::parse($commit['committed_at'])->format('d/m/Y H:i') : '' }}
                        · +{{ $commit['additions'] }}/-{{ $commit['deletions'] }} · {{ $commit['files_changed'] }} archivos
                        @if(str_contains(strtolower($commit['message']), 'deploy') || str_contains(strtolower($commit['message']), 'release'))
                        <span class="text-green-500 font-bold ml-1">🚀 DEPLOY</span>
                        @endif
                    </p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- External Factors --}}
    @if(count($externalFactors) > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Factores externos en este período</h2>
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
