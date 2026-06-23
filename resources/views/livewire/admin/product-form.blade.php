<div>
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Modelo *</label>
                    <input wire:model="modelo" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                    @error('modelo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Slug *</label>
                    <input wire:model="slug" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                    @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input wire:model="title" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea wire:model="descripcion" rows="3" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Precio Venta *</label>
                    <input wire:model="precio_venta" type="number" step="0.01" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                    @error('precio_venta') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Precio Original</label>
                    <input wire:model="precio_original" type="number" step="0.01" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Descuento (%)</label>
                    <input wire:model="descuento" type="number" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Stock</label>
                    <input wire:model="stock" type="number" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Color</label>
                    <input wire:model="color" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Brazalete</label>
                    <input wire:model="brazalete" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Colección</label>
                    <input wire:model="coleccion" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Tipo Movimiento</label>
                    <input wire:model="tipo_movimiento" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Size (MM)</label>
                    <input wire:model="size" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Género</label>
                    <select wire:model="genero" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Seleccionar</option>
                        <option value="hombre">Hombre</option>
                        <option value="mujer">Mujer</option>
                        <option value="unisex">Unisex</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Material Caja</label>
                    <input wire:model="caja" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Resistencia al Agua</label>
                    <input wire:model="resistencia_agua" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">URL Imagen</label>
                    <input wire:model="imagen" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input wire:model="activo" type="checkbox" id="activo" class="text-[#00C4FF] rounded">
                    <label for="activo" class="text-sm font-bold text-gray-700 dark:text-gray-300">Producto activo</label>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-8 py-3 rounded-xl transition-all uppercase tracking-wider text-sm">
                    {{ $productId ? 'Actualizar' : 'Crear' }} Producto
                </button>
                <a href="{{ route('admin.products') }}" class="bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-bold px-8 py-3 rounded-xl text-sm">Cancelar</a>
            </div>
        </form>
    </div>
</div>