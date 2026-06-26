<div>
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center justify-between text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">
                        <span>Modelo *</span>
                        @if($modelo)
                            <span class="flex items-center gap-2">
                                @if($slug)
                                    <a href="{{ route('products.show', ['gender' => $genero ?? 'unisex', 'slug' => $slug]) }}" target="_blank" rel="noopener" class="text-green-600 dark:text-green-400 hover:underline text-xs font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-globe"></i> Ver en sitio
                                    </a>
                                @endif
                                <a href="https://www.invictawatch.com/watches/detail/{{ urlencode($modelo) }}" target="_blank" rel="noopener" class="text-[#00C4FF] hover:underline text-xs font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-up-right-from-square"></i> Ver en Invicta
                                </a>
                            </span>
                        @endif
                    </label>
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
                    <select wire:model="color" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Sin color</option>
                        @foreach($colores as $col)
                            <option value="{{ $col }}">{{ $col }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Brazalete</label>
                    <input wire:model="brazalete" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Colección</label>
                    <select wire:model="coleccion" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Sin colección</option>
                        @foreach($colecciones as $col)
                            <option value="{{ $col }}">{{ $col }}</option>
                        @endforeach
                    </select>
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
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">URL Imagen</label>
                    <div class="flex gap-2">
                        <input wire:model="imagen" type="text" class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                        <button type="button" wire:click="downloadImage" wire:loading.attr="disabled" wire:target="downloadImage" class="flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap">
                            <i class="fa-solid fa-download" wire:loading.remove wire:target="downloadImage"></i>
                            <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="downloadImage"></i>
                            Descargar
                        </button>
                        <button type="button" wire:click="$set('imagen', '/storage/relojes/' + {{ json_encode($modelo) }} + '.jpg')" class="flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap">
                            <i class="fa-solid fa-folder-open"></i>
                            Imagen local
                        </button>
                    </div>
                    @if($downloadStatus === 'ok')
                        <p class="mt-2 text-xs font-bold text-green-600 dark:text-green-400">
                            <i class="fa-solid fa-check"></i> {{ $downloadMessage }}
                        </p>
                    @elseif($downloadStatus === 'error')
                        <p class="mt-2 text-xs font-bold text-red-600 dark:text-red-400">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $downloadMessage }}
                        </p>
                    @endif
                    @if($imagen)
                    <div class="mt-3 flex items-start gap-4">
                        <div class="w-28 h-28 rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden bg-gray-50 dark:bg-[#0a0f1c] flex items-center justify-center flex-shrink-0">
                            <img src="{{ $imagen }}" alt="Preview" class="max-w-full max-h-full object-contain" />
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            @if(str_starts_with($imagen, '/storage/'))
                                <i class="fa-solid fa-check text-green-500"></i> Imagen guardada localmente
                            @else
                                <i class="fa-solid fa-cloud text-blue-400"></i> Imagen externa (CDN)
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Sincronización VariedadesCR --}}
            <div class="border-t border-gray-100 dark:border-white/10 pt-4">
                <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-lock text-amber-500 mr-1"></i> Sincronización VariedadesCR
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Precio VariedadesCR</label>
                        <input wire:model="variedades_price" type="number" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Aumento Aplicado</label>
                        <input wire:model="variedades_increase" type="number" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input wire:model="activo" type="checkbox" id="activo" class="text-[#00C4FF] rounded">
                    <label for="activo" class="text-sm font-bold text-gray-700 dark:text-gray-300">Producto activo</label>
                </div>
                <div class="flex items-center gap-3">
                    <input wire:model="bloqueado" type="checkbox" id="bloqueado" class="text-amber-500 rounded">
                    <label for="bloqueado" class="text-sm font-bold text-gray-700 dark:text-gray-300">
                        Bloquear sincronización
                        <span class="block text-xs font-normal text-gray-400">Evita que el sync de VariedadesCR cambie el stock y precio</span>
                    </label>
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
