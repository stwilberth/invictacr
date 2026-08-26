<div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Total</p>
            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $total }}</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Activos</p>
            <p class="text-2xl font-black text-green-500">{{ $activeCount }}</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Inactivos</p>
            <p class="text-2xl font-black text-red-500">{{ $inactiveCount }}</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input wire:model.live="search" type="text" placeholder="Buscar por email..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm w-full sm:w-80" />
        <select wire:model.live="filterStatus" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
            <option value="all">Todos</option>
            <option value="active">Activos</option>
            <option value="inactive">Inactivos</option>
        </select>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-center px-4 py-3">Estado</th>
                    <th class="text-left px-4 py-3">Registrado</th>
                    <th class="text-right px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $subscriber->email }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($subscriber->active)
                            <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full text-xs font-bold">
                                <i class="fa-solid fa-circle text-[6px]"></i> Activo
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-full text-xs font-bold">
                                <i class="fa-solid fa-circle text-[6px]"></i> Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $subscriber->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button wire:click="toggle({{ $subscriber->id }})" wire:confirm="¿Cambiar estado?" class="text-xs font-bold px-3 py-1.5 rounded-lg {{ $subscriber->active ? 'text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20' : 'text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20' }} transition-colors">
                                {{ $subscriber->active ? 'Desactivar' : 'Activar' }}
                            </button>
                            <button wire:click="delete({{ $subscriber->id }})" wire:confirm="¿Eliminar este suscriptor?" class="text-xs font-bold px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                        <i class="fa-solid fa-inbox text-2xl mb-2 block text-gray-300 dark:text-gray-600"></i>
                        No se encontraron suscriptores.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $subscribers->links() }}</div>
</div>
