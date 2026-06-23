<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex gap-2 flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Buscar productos..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm w-60" />
            <select wire:model.live="filterGender" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
                <option value="">Todos los géneros</option>
                <option value="hombre">Hombre</option>
                <option value="mujer">Mujer</option>
                <option value="unisex">Unisex</option>
            </select>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-5 py-2.5 rounded-xl text-sm transition-all uppercase tracking-wider">
            + Nuevo Producto
        </a>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3 cursor-pointer" wire:click="sortBy('modelo')">Modelo</th>
                    <th class="text-left px-4 py-3 cursor-pointer" wire:click="sortBy('title')">Nombre</th>
                    <th class="text-left px-4 py-3 cursor-pointer" wire:click="sortBy('genero')">Género</th>
                    <th class="text-right px-4 py-3 cursor-pointer" wire:click="sortBy('precio_venta')">Precio</th>
                    <th class="text-center px-4 py-3 cursor-pointer" wire:click="sortBy('stock')">Stock</th>
                    <th class="text-center px-4 py-3">Activo</th>
                    <th class="text-right px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $product->modelo }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">{{ $product->title }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 capitalize">{{ $product->genero }}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">₡{{ number_format($product->precio_venta, 0) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            @if($product->stock > 10) bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @elseif($product->stock > 0) bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                            @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button wire:click="toggleActive({{ $product->id }})" class="px-3 py-1 rounded-lg text-xs font-bold {{ $product->activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $product->activo ? 'Sí' : 'No' }}
                        </button>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[#00C4FF] hover:underline text-xs font-bold">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>