<div x-data="columnManager()" x-init="init()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex gap-2 flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Buscar productos..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm w-60" />
            <select wire:model.live="filterGender" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
                <option value="">Todos los géneros</option>
                <option value="hombre">Hombre</option>
                <option value="mujer">Mujer</option>
                <option value="unisex">Unisex</option>
            </select>
            <select wire:model.live="filterColeccion" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
                <option value="">Todas las colecciones</option>
                @foreach($colecciones as $col)
                    <option value="{{ $col }}">{{ $col }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStock" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
                <option value="all">Todo el stock</option>
                <option value="in">Con stock</option>
                <option value="out">Sin stock</option>
            </select>
            <select wire:model.live="filterActivo" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
                <option value="all">Todos</option>
                <option value="yes">Activos</option>
                <option value="no">Inactivos</option>
            </select>
        </div>
        <div class="flex gap-2 items-center">
            <div class="relative" @click.outside="open = false">
                <button @click="open = !open" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-2">
                    <i class="fa-solid fa-table-cells"></i>
                    Columnas
                    <i class="fa-solid fa-chevron-down text-[10px]" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" x-transition class="absolute right-0 top-full mt-1 z-50 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 rounded-xl shadow-xl py-2 min-w-[180px]">
                    <template x-for="col in columns" :key="col.key">
                        <label class="flex items-center gap-2 px-4 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer whitespace-nowrap">
                            <input type="checkbox" :checked="col.visible" @change="toggle(col.key)" class="rounded border-gray-300 dark:border-gray-600 text-[#00C4FF] focus:ring-[#00C4FF]" />
                            <span x-text="col.label"></span>
                        </label>
                    </template>
                </div>
            </div>
            <a href="{{ route('admin.products.create') }}" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-5 py-2.5 rounded-xl text-sm transition-all uppercase tracking-wider">
                + Nuevo Producto
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-x-auto">
        <table class="w-full text-sm" style="min-width: 1200px;">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3" data-column="imagen">Imagen</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="modelo" wire:click="sortBy('modelo')">Modelo</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="coleccion" wire:click="sortBy('coleccion')">Colección</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="color" wire:click="sortBy('color')">Color</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="tamanio" wire:click="sortBy('size')">Tamaño</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="caja" wire:click="sortBy('caja')">Material Caja</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="brazalete" wire:click="sortBy('brazalete')">Brazalete</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="resistencia" wire:click="sortBy('resistencia_agua')">Resistencia</th>
                    <th class="text-left px-4 py-3 cursor-pointer" data-column="genero" wire:click="sortBy('genero')">Género</th>
                    <th class="text-right px-4 py-3 cursor-pointer" data-column="precio" wire:click="sortBy('precio_venta')">Precio</th>
                    <th class="text-center px-4 py-3 cursor-pointer" data-column="imgs" wire:click="sortBy('images_count')">Imgs</th>
                    <th class="text-center px-4 py-3 cursor-pointer" data-column="stock" wire:click="sortBy('stock')">Stock</th>
                    <th class="text-center px-4 py-3 cursor-pointer" data-column="video" wire:click="sortBy('video')">Video</th>
                    <th class="text-right px-4 py-3" data-column="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="px-4 py-3" data-column="imagen">
                        @if($product->imagen)
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 dark:bg-[#0a0f1c] flex items-center justify-center relative" title="Imagen local">
                                <img src="{{ $product->imagen }}" alt="{{ $product->modelo }}" class="w-full h-full object-contain" loading="lazy" />
                                <i class="fa-solid fa-circle text-[6px] text-green-500 absolute top-0 right-0" title="Imagen local"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-[#0a0f1c] flex items-center justify-center text-gray-300" title="Sin imagen">
                                <i class="fa-solid fa-image text-xs"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white whitespace-nowrap" data-column="modelo">{{ $product->modelo }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs uppercase" data-column="coleccion">{{ $product->coleccion ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs" data-column="color">{{ $product->color ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs" data-column="tamanio">{{ $product->size ? $product->size . 'mm' : '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs" data-column="caja">{{ $product->caja ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs" data-column="brazalete">{{ $product->brazalete ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs" data-column="resistencia">{{ $product->resistencia_agua ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 capitalize" data-column="genero">{{ $product->genero }}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white" data-column="precio">₡{{ number_format($product->precio_venta, 0) }}</td>
                    <td class="px-4 py-3 text-center" data-column="imgs">
                        @php
                            $totalImgs = $product->images_count + ($product->getRawOriginal('imagen') ? 1 : 0);
                        @endphp
                        <span class="text-xs font-bold {{ $totalImgs > 0 ? 'text-[#00C4FF]' : 'text-gray-400' }}">
                            {{ $totalImgs }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center" data-column="stock">
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            @if($product->stock > 10) bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @elseif($product->stock > 0) bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                            @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center" data-column="video">
                        @if($product->video)
                            <i class="fa-solid fa-circle-play text-red-500 text-lg" title="Tiene video"></i>
                        @else
                            <span class="text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap" data-column="acciones">
                        <a href="{{ route('products.show', ['slug' => $product->slug]) }}" target="_blank" rel="noopener" class="text-green-600 dark:text-green-400 hover:underline text-xs font-bold" title="Ver en el sitio">Sitio</a>
                        ·
                        <a href="https://www.invictawatch.com/watches/detail/{{ urlencode($product->modelo) }}" target="_blank" rel="noopener" class="text-[#00C4FF] hover:underline text-xs font-bold" title="Ver en InvictaWatch">Invicta</a>
                        ·
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[#00C4FF] hover:underline text-xs font-bold">Editar</a>
                        @if($product->imagen)
                        @if($optimizationStatus[$product->id] ?? false)
                        · <span class="text-green-500 text-xs font-bold" title="WebP generados"><i class="fa-solid fa-check-circle"></i></span>
                        @else
                        · <button wire:click="optimizeImage({{ $product->id }})" wire:loading.attr="disabled" wire:target="optimizeImage({{ $product->id }})" class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white text-[10px] font-bold px-2 py-1 rounded-lg transition-all" title="Optimizar imagen">
                                <i wire:loading.remove wire:target="optimizeImage({{ $product->id }})" class="fa-solid fa-wand-magic-sparkles"></i>
                                <i wire:loading wire:target="optimizeImage({{ $product->id }})" class="fa-solid fa-spinner fa-spin"></i>
                                <span wire:loading.remove wire:target="optimizeImage({{ $product->id }})">WebP</span>
                                <span wire:loading wire:target="optimizeImage({{ $product->id }})">...</span>
                            </button>
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('columnManager', () => ({
        open: false,
        columns: [],
        init() {
            const saved = localStorage.getItem('admin_products_columns');
            const defaults = [
                { key: 'imagen', label: 'Imagen', visible: true },
                { key: 'modelo', label: 'Modelo', visible: true },
                { key: 'coleccion', label: 'Colección', visible: true },
                { key: 'color', label: 'Color', visible: true },
                { key: 'tamanio', label: 'Tamaño', visible: true },
                { key: 'caja', label: 'Material Caja', visible: true },
                { key: 'brazalete', label: 'Brazalete', visible: true },
                { key: 'resistencia', label: 'Resistencia', visible: true },
                { key: 'genero', label: 'Género', visible: true },
                { key: 'precio', label: 'Precio', visible: true },
                { key: 'imgs', label: 'Imgs', visible: true },
                { key: 'stock', label: 'Stock', visible: true },
                { key: 'video', label: 'Video', visible: true },
                { key: 'acciones', label: 'Acciones', visible: true },
            ];
            if (saved) {
                const savedState = JSON.parse(saved);
                this.columns = defaults.map(col => ({
                    ...col,
                    visible: savedState[col.key] !== undefined ? savedState[col.key] : col.visible,
                }));
            } else {
                this.columns = defaults;
            }
            this.apply();
        },
        toggle(key) {
            const col = this.columns.find(c => c.key === key);
            if (col) col.visible = !col.visible;
            this.save();
            this.apply();
        },
        save() {
            const state = {};
            this.columns.forEach(c => { state[c.key] = c.visible; });
            localStorage.setItem('admin_products_columns', JSON.stringify(state));
        },
        apply() {
            this.columns.forEach(col => {
                document.querySelectorAll(`[data-column="${col.key}"]`).forEach(el => {
                    el.style.display = col.visible ? '' : 'none';
                });
            });
        },
    }));
});
</script>
