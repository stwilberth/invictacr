<div>
    <div class="flex justify-between items-center mb-6">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Total gastos: <span class="font-black text-gray-900 dark:text-white text-lg">₡{{ number_format($total, 0) }}</span>
        </div>
        <button wire:click="create" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-5 py-2.5 rounded-xl text-sm transition-all uppercase tracking-wider">+ Nuevo Gasto</button>
    </div>

    @if($showForm)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Descripción *</label>
                <input wire:model="description" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Monto *</label>
                <input wire:model="amount" type="number" step="0.01" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                <input wire:model="category" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Fecha *</label>
                <input wire:model="expense_date" type="date" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                @error('expense_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                    <th class="text-left px-4 py-3">Descripción</th>
                    <th class="text-left px-4 py-3">Categoría</th>
                    <th class="text-right px-4 py-3">Monto</th>
                    <th class="text-right px-4 py-3">Fecha</th>
                    <th class="text-right px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $expense->description }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-gray-100 dark:bg-white/5 rounded-lg text-xs text-gray-600 dark:text-gray-400">{{ $expense->category ?? 'Sin categoría' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-red-600">₡{{ number_format($expense->amount, 0) }}</td>
                    <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ $expense->expense_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <button wire:click="delete({{ $expense->id }})" class="text-red-500 hover:underline text-xs font-bold">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $expenses->links() }}</div>
</div>