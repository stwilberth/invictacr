<div>
    <h2 class="text-xl font-black mb-4">Marketing</h2>
    <div class="flex flex-wrap gap-2 mb-6">
        <button wire:click="$set('activeTab', 'dashboard')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'dashboard' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Dashboard</button>
        <button wire:click="$set('activeTab', 'unificado')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'unificado' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Unificado</button>
        <button wire:click="$set('activeTab', 'tasks')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'tasks' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Tareas</button>
        <button wire:click="$set('activeTab', 'content')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'content' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Contenido AI</button>
    </div>

    @if($activeTab === 'unificado')
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-[#00C4FF]">{{ number_format($stats['totales']['site']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Visitas sitio (14d)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-green-500">${{ number_format($stats['totales']['fb'] + $stats['totales']['gads'], 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Gasto ads (14d)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-purple-500">{{ number_format($stats['totales']['wa']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Clics WhatsApp</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-amber-500">{{ number_format($stats['totales']['leads']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Leads ({{ $stats['totales']['leads_fb'] }} de anuncio)</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 text-center">
            <p class="text-2xl font-black text-gray-900 dark:text-white">₡{{ number_format($stats['totales']['total']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Ventas ({{ $stats['totales']['inv'] }})</p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-white/5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3" title="Usuarios medidos por Google Analytics (requiere JS + consentimiento; filtra bots y adblockers)">GA users ⓘ</th>
                    <th class="px-4 py-3">GA sesiones</th>
                    <th class="px-4 py-3" title="Navegadores que el propio servidor vio ese día (sin filtros de consentimiento ni adblock; incluye navegadores in-app de FB/IG y prefetch). Es un techo, no la cifra exacta de personas.">Sitio ⓘ</th>
                    <th class="px-4 py-3">Clics WA</th>
                    <th class="px-4 py-3">Meta $</th>
                    <th class="px-4 py-3">G-Ads $</th>
                    <th class="px-4 py-3">Leads</th>
                    <th class="px-4 py-3" title="Facturas creadas ese día, sin canceladas. Incluye apartados.">Facturas ⓘ</th>
                    <th class="px-4 py-3" title="Suma de total de esas facturas (apartados incluidos, canceladas fuera)">₡ Ventas ⓘ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['dias'] as $d)
                <tr class="border-b border-gray-50 dark:border-white/5">
                    <td class="px-4 py-2.5 font-bold">{{ \Carbon\Carbon::parse($d['fecha'])->format('d/m') }}</td>
                    <td class="px-4 py-2.5 text-gray-500">{{ $d['ga_users'] }}</td>
                    <td class="px-4 py-2.5 text-gray-500">{{ $d['ga_sessions'] }}</td>
                    <td class="px-4 py-2.5">{{ $d['site'] }}</td>
                    <td class="px-4 py-2.5 {{ $d['wa'] > 0 ? 'text-purple-500 font-bold' : 'text-gray-400' }}">{{ $d['wa'] }}</td>
                    <td class="px-4 py-2.5 {{ $d['fb'] > 0 ? 'text-rose-500 font-bold' : 'text-gray-400' }}">{{ number_format($d['fb'], 2) }}</td>
                    <td class="px-4 py-2.5 text-gray-400">{{ number_format($d['gads'], 2) }}</td>
                    <td class="px-4 py-2.5 {{ $d['leads'] > 0 ? 'text-amber-500 font-bold' : 'text-gray-400' }}">{{ $d['leads'] }}@if($d['leads_fb']) <span class="text-[10px]">📣{{ $d['leads_fb'] }}</span>@endif</td>
                    <td class="px-4 py-2.5">{{ $d['inv'] }}</td>
                    <td class="px-4 py-2.5 font-bold">₡{{ number_format($d['total']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-400 mt-2">No esperes que GA y Sitio coincidan: GA solo cuenta con JavaScript y consentimiento (filtra adblockers), Sitio cuenta todo lo que el servidor ve (incluye navegadores in-app de FB/IG y prefetch). ₡ Ventas = facturas del día sin canceladas (apartados incluidos). Corte del día: 00:00 UTC = 6pm CR. Datos ads: sync diario 3am UTC.</p>
    @elseif($activeTab === 'tasks')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <form wire:submit="createTask" class="flex gap-3">
            <input wire:model="taskTitle" type="text" placeholder="Nueva tarea..." class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
            <button type="submit" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-5 py-2.5 rounded-xl text-sm">Crear</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h3 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Pendientes ({{ $pendingTasks->count() }})</h3>
            <div class="space-y-2">
                @foreach($pendingTasks as $task)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-xl">
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $task->title }}</span>
                    <button wire:click="completeTask({{ $task->id }})" class="text-green-500 hover:text-green-400"><i class="fa-solid fa-check"></i></button>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h3 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Completadas ({{ $completedTasks->count() }})</h3>
            <div class="space-y-2">
                @foreach($completedTasks as $task)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-xl opacity-60">
                    <span class="text-sm text-gray-500 line-through">{{ $task->title }}</span>
                    <button wire:click="deleteTask({{ $task->id }})" class="text-red-400 hover:text-red-500"><i class="fa-solid fa-trash"></i></button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @elseif($activeTab === 'content')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <p class="text-gray-500 dark:text-gray-400 text-sm">Generación de contenido con IA (próximamente).</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 text-center">
            <p class="text-3xl font-black text-[#00C4FF]">{{ $pendingTasks->count() }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tareas pendientes</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 text-center">
            <p class="text-3xl font-black text-green-500">{{ $completedTasks->count() }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tareas completadas</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 text-center">
            <p class="text-3xl font-black text-blue-500">{{ $tasks->count() }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total tareas</p>
        </div>
    </div>
    @endif
</div>