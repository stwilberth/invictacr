<div>
    <div class="mb-6">
        <input wire:model.live="search" type="text" placeholder="Buscar próximos productos..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm w-80" />
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3">Modelo</th>
                    <th class="text-left px-4 py-3">Producto</th>
                    <th class="text-left px-4 py-3">Colección</th>
                    <th class="text-left px-4 py-3">Género</th>
                    <th class="text-center px-4 py-3">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $product->modelo }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">{{ $product->title ?? 'Sin título' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->coleccion ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 capitalize">{{ $product->genero ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <button wire:click="activate({{ $product->id }})" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all">Activar</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay productos próximos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>