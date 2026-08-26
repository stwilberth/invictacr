<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                <span>🎯</span> Asesor CEO IA
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                El plan de acción: qué hacer ahora, según los datos del negocio.
                @if($generatedAt)
                    <span class="text-gray-400">· Generado {{ $generatedAt }}</span>
                @endif
            </p>
        </div>
        <button wire:click="generatePlan" wire:loading.attr="disabled" class="shrink-0 px-5 py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-tight bg-[#00C4FF] hover:bg-[#00a3d6] text-[#0a0f1c] transition-all hover:-translate-y-0.5 active:scale-95 shadow-sm hover:shadow-md disabled:opacity-50 disabled:hover:translate-y-0">
            <span wire:loading.remove wire:target="generatePlan">🤖 {{ count($recommendations) > 0 ? 'Regenerar plan' : 'Generar plan de acción' }}</span>
            <span wire:loading wire:target="generatePlan">Analizando el negocio...</span>
        </button>
    </div>

    @if(session('message'))
    <div class="mb-4 p-3 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 text-sm text-green-700 dark:text-green-400">
        {{ session('message') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 text-sm text-red-600 dark:text-red-400">
        {{ session('error') }}
    </div>
    @endif

    @if(count($recommendations) === 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-12 text-center">
        <div class="text-4xl mb-3">🧠</div>
        <p class="text-gray-600 dark:text-gray-300 font-bold">Todavía no hay un plan de acción generado.</p>
        <p class="text-sm text-gray-400 mt-1">Presiona "Generar plan de acción" para que la IA analice ingresos, tráfico, ads, inventario y factores externos, y te diga cuál es el camino a seguir.</p>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
        @foreach($recommendations as $rec)
        @php
            $categoryStyles = [
                'urgente' => ['label' => 'Urgente', 'badge' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400', 'border' => 'border-red-200 dark:border-red-800/30', 'icon' => '🔴'],
                'oportunidad' => ['label' => 'Oportunidad', 'badge' => 'bg-[#00C4FF]/10 text-[#00a3d6] dark:text-[#00C4FF]', 'border' => 'border-[#00C4FF]/20', 'icon' => '🟡'],
                'estrategia' => ['label' => 'Estrategia', 'badge' => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300', 'border' => 'border-gray-200 dark:border-white/10', 'icon' => '🔵'],
            ];
            $cs = $categoryStyles[$rec['category']] ?? $categoryStyles['estrategia'];

            $areaStyles = [
                'marketing' => ['label' => 'Marketing', 'badge' => 'bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400', 'icon' => '📢'],
                'programacion' => ['label' => 'Programación', 'badge' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400', 'icon' => '💻'],
                'inventario' => ['label' => 'Inventario', 'badge' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400', 'icon' => '📦'],
                'finanzas' => ['label' => 'Finanzas', 'badge' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', 'icon' => '💰'],
                'seo' => ['label' => 'SEO', 'badge' => 'bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400', 'icon' => '🔍'],
                'ventas' => ['label' => 'Ventas', 'badge' => 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400', 'icon' => '🛒'],
                'soporte' => ['label' => 'Soporte', 'badge' => 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400', 'icon' => '🎧'],
                'operaciones' => ['label' => 'Operaciones', 'badge' => 'bg-slate-100 text-slate-600 dark:bg-slate-900/30 dark:text-slate-400', 'icon' => '⚙️'],
                'legal' => ['label' => 'Legal', 'badge' => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400', 'icon' => '⚖️'],
                'rrhh' => ['label' => 'RRHH', 'badge' => 'bg-teal-100 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400', 'icon' => '👥'],
            ];
            $as = $areaStyles[$rec['area']] ?? ['label' => ucfirst($rec['area'] ?? 'General'), 'badge' => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300', 'icon' => '📌'];

            $priorityBadge = match($rec['priority']) {
                'alta' => 'bg-red-500 text-white',
                'media' => 'bg-gray-400 text-white dark:bg-white/20',
                default => 'bg-gray-200 text-gray-500 dark:bg-white/10 dark:text-gray-400',
            };

            $statusStyles = [
                'pendiente' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800/30 text-amber-700 dark:text-amber-400',
                'hecho' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/30 text-green-700 dark:text-green-400',
                'descartado' => 'bg-gray-100 dark:bg-white/5 border-gray-200 dark:border-white/10 text-gray-400',
            ];
        @endphp
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border {{ $cs['border'] }} p-5 flex flex-col {{ $rec['status'] === 'descartado' ? 'opacity-60' : '' }}">
            <div class="flex items-center gap-2 mb-3 flex-wrap">
                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg {{ $cs['badge'] }}">
                    {{ $cs['icon'] }} {{ $cs['label'] }}
                </span>
                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg {{ $as['badge'] }}">
                    {{ $as['icon'] }} {{ $as['label'] }}
                </span>
                <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded {{ $priorityBadge }}">
                    Prioridad {{ $rec['priority'] }}
                </span>
                <span class="ml-auto text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full border {{ $statusStyles[$rec['status']] ?? $statusStyles['pendiente'] }}">
                    {{ $rec['status'] }}
                </span>
            </div>

            <h3 class="font-black text-gray-900 dark:text-white text-sm mb-2 leading-snug">{{ $rec['title'] }}</h3>

            @if($rec['rationale'])
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-3">{{ $rec['rationale'] }}</p>
            @endif

            <div class="mt-auto pt-3 border-t border-gray-100 dark:border-white/10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">🎯 Acción a tomar</p>
                <p class="text-sm text-gray-700 dark:text-gray-200 font-medium leading-relaxed mb-3">{{ $rec['action'] }}</p>

                <div class="flex gap-2">
                    <button wire:click="updateStatus({{ $rec['id'] }}, 'hecho')" class="flex-1 px-3 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-tight transition-colors {{ $rec['status'] === 'hecho' ? 'bg-green-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600' }}">
                        ✓ Hecho
                    </button>
                    <button wire:click="updateStatus({{ $rec['id'] }}, 'descartado')" class="flex-1 px-3 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-tight transition-colors {{ $rec['status'] === 'descartado' ? 'bg-gray-400 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10' }}">
                        ✕ Descartar
                    </button>
                    @if($rec['status'] !== 'pendiente')
                    <button wire:click="updateStatus({{ $rec['id'] }}, 'pendiente')" class="px-3 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-tight bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                        ↺
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(count($history) > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 dark:border-white/5">
            <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-xs">Historial de planes anteriores</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5">
                        <th class="text-left px-5 py-2 font-bold">Fecha</th>
                        <th class="text-right px-5 py-2 font-bold">Total</th>
                        <th class="text-right px-5 py-2 font-bold">Hechas</th>
                        <th class="text-right px-5 py-2 font-bold">Descartadas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $h)
                    <tr class="border-b border-gray-50 dark:border-white/5">
                        <td class="px-5 py-2 text-gray-600 dark:text-gray-300">{{ $h['generated_at'] }}</td>
                        <td class="px-5 py-2 text-right text-gray-600 dark:text-gray-300">{{ $h['total'] }}</td>
                        <td class="px-5 py-2 text-right text-green-600 dark:text-green-400 font-bold">{{ $h['hechas'] }}</td>
                        <td class="px-5 py-2 text-right text-gray-400">{{ $h['descartadas'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
