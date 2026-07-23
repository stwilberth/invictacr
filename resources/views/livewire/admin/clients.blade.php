<div>
    <div class="flex justify-between items-center mb-6">
        <input wire:model.live="search" type="text" placeholder="Buscar clientes..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm w-80" />
        <div class="flex gap-2">
            <button wire:click="extractFromInvoices" wire:loading.attr="disabled" class="bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-all flex items-center gap-2">
                <i wire:loading.remove wire:target="extractFromInvoices" class="fa-solid fa-file-import"></i>
                <i wire:loading wire:target="extractFromInvoices" class="fa-solid fa-spinner fa-spin"></i>
                Extraer de facturas
            </button>
            <button wire:click="create" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-5 py-2.5 rounded-xl text-sm transition-all uppercase tracking-wider">+ Nuevo Cliente</button>
        </div>
    </div>

    @if($extractedCount > 0)
    <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl mb-6 text-sm font-bold flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        {{ $extractedCount }} cliente(s) importado(s) desde facturas exitosamente.
    </div>
    @endif

    @if($showForm)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                <input wire:model="name" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input wire:model="email" type="email" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                <input wire:model="phone" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Notas</label>
                <textarea wire:model="notes" rows="1" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"></textarea>
            </div>
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-6 py-2.5 rounded-xl text-sm">Guardar</button>
                <button type="button" wire:click="$set('showForm', false)" class="bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-bold px-6 py-2.5 rounded-xl text-sm">Cancelar</button>
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3">Nombre</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Teléfono</th>
                    <th class="text-right px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $client->name }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $client->email }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $client->phone }}</td>
                    <td class="px-4 py-3 text-right">
                        <button wire:click="edit({{ $client->id }})" class="text-[#00C4FF] hover:underline text-xs font-bold">Editar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $clients->links() }}</div>
</div>