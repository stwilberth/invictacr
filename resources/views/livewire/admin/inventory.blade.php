<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex gap-2 flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Buscar..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm w-60" />
            <select wire:model.live="filterStock" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
                <option value="">Todos</option>
                <option value="available">Con stock</option>
                <option value="low">Stock bajo</option>
                <option value="out">Agotados</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3">Modelo</th>
                    <th class="text-left px-4 py-3">Producto</th>
                    <th class="text-center px-4 py-3">Stock actual</th>
                    <th class="text-center px-4 py-3">Nuevo stock</th>
                    <th class="text-center px-4 py-3">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b border-gray-100 dark:border-white/5" wire:key="{{ $product->id }}">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $product->modelo }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[250px] truncate">{{ $product->title }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            @if($product->stock > 10) bg-green-100 text-green-700
                            @elseif($product->stock > 0) bg-amber-100 text-amber-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <input type="number" value="{{ $product->stock }}" wire:change="updateStock({{ $product->id }}, $event.target.value)" class="w-20 text-center bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2 py-1 text-sm" />
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button wire:click="updateStock({{ $product->id }}, {{ $product->stock }})" class="text-[#00C4FF] hover:underline text-xs font-bold">Actualizar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>