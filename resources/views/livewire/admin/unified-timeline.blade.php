<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-2">
            <button wire:click="$set('period', '7d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '7d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">7 días</button>
            <button wire:click="$set('period', '30d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '30d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">30 días</button>
            <button wire:click="$set('period', '90d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '90d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">90 días</button>
            <button wire:click="$set('period', '365d')" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors {{ $period === '365d' ? 'bg-[#00C4FF] text-white' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">1 año</button>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">{{ count($events) }} eventos</span>
            <button wire:click="generateConclusions" wire:loading.attr="disabled" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors bg-gradient-to-r from-purple-600 to-blue-500 text-white hover:opacity-90 disabled:opacity-50">
                <span wire:loading.remove wire:target="generateConclusions">🤖 Generar conclusiones</span>
                <span wire:loading wire:target="generateConclusions">Analizando...</span>
            </button>
        </div>
    </div>

    @if(count($globalConclusion) > 0)
    <div class="mb-6 p-5 bg-gradient-to-br from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-2xl border border-purple-200 dark:border-purple-800/30">
        <div class="flex items-center gap-2 mb-3">
            <span class="text-lg">🧠</span>
            <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Análisis General</h2>
            @if($globalConclusion['generated_at'])
            <span class="text-xs text-gray-400 ml-auto">{{ $globalConclusion['generated_at']->format('d/m/Y H:i') }}</span>
            @endif
        </div>
        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $globalConclusion['conclusion'] }}</p>
        @if($globalConclusion['advice'])
        <div class="mt-3 pt-3 border-t border-purple-200 dark:border-purple-800/30">
            <h3 class="font-bold text-xs text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">🎯 Consejo futuro</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $globalConclusion['advice'] }}</p>
        </div>
        @endif
    </div>
    @endif

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="text-left px-4 py-3 font-bold">Periodo</th>
                        <th class="text-left px-4 py-3 font-bold">Fecha</th>
                        <th class="text-left px-4 py-3 font-bold">Fuente</th>
                        <th class="text-left px-4 py-3 font-bold">Evento</th>
                        <th class="text-right px-4 py-3 font-bold">Ingresos</th>
                        <th class="text-left px-4 py-3 font-bold w-96">Conclusiones IA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $lastPeriod = null; $lastDate = null; @endphp
                    @forelse($events as $event)
                    @php
                        $rev = $dailyRevenue[$event['date']] ?? null;
                        $isNewPeriod = $event['period_key'] !== $lastPeriod;
                        $isNewDate = $event['date'] !== $lastDate;
                        $lastPeriod = $event['period_key'];
                        $lastDate = $event['date'];
                        $conclusionText = $conclusions[$event['period_key']] ?? null;
                    @endphp

                    @if($isNewPeriod)
                    <tr class="bg-gray-50/50 dark:bg-white/5">
                        <td class="px-4 py-2 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider" colspan="4">
                            {{ $event['period_label'] }}
                        </td>
                        <td class="px-4 py-2 text-right text-xs font-bold text-gray-700 dark:text-gray-300">
                            ₡{{ number_format($periodTotals[$event['period_key']] ?? 0) }}
                        </td>
                        <td class="px-4 py-2 text-xs border-l border-gray-200 dark:border-white/10">
                            @if($conclusionText)
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $conclusionText }}</p>
                            @else
                            <span class="text-gray-400 italic">🤖 Pendiente...</span>
                            @endif
                        </td>
                    </tr>
                    @endif

                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td></td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap font-mono text-xs">
                            {{ $isNewDate ? \Carbon\Carbon::parse($event['date'])->format('d/m/Y') : '' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2 py-1 rounded-lg
                                {{ $event['color'] === 'blue' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $event['color'] === 'red' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                {{ $event['color'] === 'orange' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400' : '' }}
                                {{ $event['color'] === 'green' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                {{ $event['color'] === 'cyan' ? 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400' : '' }}
                                {{ $event['color'] === 'gray' ? 'bg-gray-100 text-gray-600 dark:bg-gray-900/30 dark:text-gray-400' : '' }}
                                {{ $event['color'] === 'amber' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                            ">
                                <i class="fa-solid {{ $event['icon'] }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $event['source'])) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $event['title'] }}</p>
                            @if($event['detail'])
                            <p class="text-xs text-gray-500 mt-0.5">{{ $event['detail'] }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            @if($isNewDate && $event['source'] !== 'sistema')
                                @if($rev !== null)
                                <span class="font-bold {{ $rev > 0 ? 'text-green-500' : 'text-red-400' }}">₡{{ number_format($rev) }}</span>
                                @else
                                <span class="text-gray-400">—</span>
                                @endif
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                            No hay eventos en este período.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($events) > 0)
    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
        <span>Mostrando {{ count($events) }} eventos</span>
        <div class="flex gap-3">
            <span><span class="inline-block w-2 h-2 rounded-full bg-green-500"></span> Ingresos</span>
            <span><span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Orgánico</span>
            <span><span class="inline-block w-2 h-2 rounded-full bg-red-500"></span> Publicidad</span>
            <span><span class="inline-block w-2 h-2 rounded-full bg-gray-500"></span> Código</span>
        </div>
    </div>
    @endif
</div>
