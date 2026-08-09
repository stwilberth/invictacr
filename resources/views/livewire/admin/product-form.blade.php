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
                                    <a href="{{ route('products.show', ['slug' => $slug]) }}" target="_blank" rel="noopener" class="text-green-600 dark:text-green-400 hover:underline text-xs font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-globe"></i> Ver en sitio
                                    </a>
                                @endif
                                <a href="https://www.invictawatch.com/watches/detail/{{ urlencode($modelo) }}" target="_blank" rel="noopener" class="text-[#00C4FF] hover:underline text-xs font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-up-right-from-square"></i> Ver en Invicta
                                </a>
                            </span>
                        @endif
                    </label>
                    <div class="flex gap-2">
                        <input wire:model="modelo" type="text" class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                        <button type="button" wire:click="fetchFromInvicta" wire:loading.attr="disabled" class="flex items-center gap-1.5 bg-gradient-to-r from-[#00C4FF] to-blue-600 hover:from-[#00b0e6] hover:to-blue-700 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap">
                            <i wire:loading.remove wire:target="fetchFromInvicta" class="fa-solid fa-cloud-arrow-down"></i>
                            <i wire:loading wire:target="fetchFromInvicta" class="fa-solid fa-spinner fa-spin"></i>
                            Obtener de Invicta
                        </button>
                    </div>
                    @if($fetchStatus === 'ok')
                        <p class="mt-2 text-xs font-bold text-green-600 dark:text-green-400">
                            <i class="fa-solid fa-check"></i> {{ $fetchMessage }}
                        </p>
                    @elseif($fetchStatus === 'error')
                        <p class="mt-2 text-xs font-bold text-red-600 dark:text-red-400">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $fetchMessage }}
                        </p>
                    @endif
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
                    <select wire:model="brazalete" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Sin brazalete</option>
                        @foreach($brazaletes as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
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
                    <select wire:model="tipo_movimiento" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Sin movimiento</option>
                        <option value="cuarzo">Cuarzo</option>
                        <option value="automatico">Automático</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Tamaño (mm)</label>
                    <input wire:model="size" type="number" step="any" min="0" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
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
                    <select wire:model="caja" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Sin material</option>
                        @foreach($cajas as $material)
                            <option value="{{ $material }}">{{ $material }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Resistencia al Agua (m)</label>
                    <input wire:model="resistencia_agua" type="number" step="any" min="0" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Imagen principal</label>
                    <div class="space-y-2">
                        <div class="flex gap-2">
                            <input wire:model="imagen" type="text" placeholder="https://cdn.invictawatch.com/..." class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                            <button type="button" wire:click="downloadImage" wire:loading.attr="disabled" wire:target="downloadImage" class="flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap">
                                <i class="fa-solid fa-download" wire:loading.remove wire:target="downloadImage"></i>
                                <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="downloadImage"></i>
                                Descargar
                            </button>
                            <button type="button" wire:click="$set('imagen', '/storage/relojes/' + {{ json_encode($modelo) }} + '.jpg')" class="flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap">
                                <i class="fa-solid fa-folder-open"></i>
                                Local
                            </button>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-50 dark:bg-[#0a0f1c] border border-dashed border-gray-300 dark:border-white/10 rounded-xl px-4 py-2.5">
                            <i class="fa-solid fa-cloud-arrow-up text-purple-400"></i>
                            <input type="file" wire:model="newImageFile" accept="image/*" class="flex-1 text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-100 dark:file:bg-purple-900/30 file:text-purple-700 dark:file:text-purple-300 hover:file:bg-purple-200 dark:hover:file:bg-purple-900/50 file:cursor-pointer" />
                            <button type="button" wire:click="uploadImage" wire:loading.attr="disabled" wire:target="newImageFile,uploadImage" class="flex items-center gap-1.5 bg-purple-500 hover:bg-purple-600 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                                <i class="fa-solid fa-cloud-arrow-up" wire:loading.remove wire:target="uploadImage"></i>
                                <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="uploadImage"></i>
                                Subir
                            </button>
                        </div>
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
                        <div>
                            <p class="text-xs text-gray-400 mt-1">
                                @if(str_starts_with($imagen, '/storage/'))
                                    <i class="fa-solid fa-check text-green-500"></i> Imagen guardada localmente
                                @else
                                    <i class="fa-solid fa-cloud text-blue-400"></i> Imagen externa (CDN)
                                @endif
                            </p>
                            @if(str_starts_with($imagen, '/storage/'))
                            <button type="button" wire:click="optimizeImage" wire:loading.attr="disabled" wire:target="optimizeImage" class="mt-2 flex items-center gap-1.5 bg-indigo-500 hover:bg-indigo-600 disabled:opacity-50 text-white font-bold text-[10px] uppercase tracking-wider px-3 py-1.5 rounded-lg transition-all">
                                <i wire:loading.remove wire:target="optimizeImage" class="fa-solid fa-wand-magic-sparkles"></i>
                                <i wire:loading wire:target="optimizeImage" class="fa-solid fa-spinner fa-spin"></i>
                                Optimizar WebP
                            </button>
                            @if($optimizeStatus === 'ok')
                                <p class="mt-1 text-xs font-bold text-green-600 dark:text-green-400">
                                    <i class="fa-solid fa-check"></i> {{ $optimizeMessage }}
                                </p>
                            @elseif($optimizeStatus === 'error')
                                <p class="mt-1 text-xs font-bold text-red-600 dark:text-red-400">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $optimizeMessage }}
                                </p>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Imágenes Extra</label>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @for($i = 0; $i < count($imagenes_extra); $i++)
                        <div class="relative group w-16 h-16 rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden bg-gray-50 dark:bg-[#0a0f1c]">
                            <img src="{{ $imagenes_extra[$i] }}" alt="Extra {{ $i + 1 }}" class="w-full h-full object-contain p-1" loading="lazy" />
                            <button type="button" wire:click="removeImagenExtra({{ $i }})" class="absolute top-0.5 right-0.5 w-4 h-4 bg-red-500 hover:bg-red-600 text-white rounded-full text-[8px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        @endfor
                    </div>
                    <div class="flex items-center gap-2">
                        <input wire:model="newExtraImageUrl" type="text" placeholder="https://cdn.invictawatch.com/..." class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm" />
                        <button type="button" wire:click="downloadAndAddExtraImage" wire:loading.attr="disabled" class="flex items-center gap-1.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap">
                            <i wire:loading.remove wire:target="downloadAndAddExtraImage" class="fa-solid fa-download"></i>
                            <i wire:loading wire:target="downloadAndAddExtraImage" class="fa-solid fa-spinner fa-spin"></i>
                            Descargar
                        </button>
                    </div>
                    <div class="flex items-center gap-2 mt-2 bg-gray-50 dark:bg-[#0a0f1c] border border-dashed border-gray-300 dark:border-white/10 rounded-xl px-4 py-2.5">
                        <i class="fa-solid fa-cloud-arrow-up text-purple-400"></i>
                        <input type="file" wire:model="newExtraImageFile" accept="image/*" class="flex-1 text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-100 dark:file:bg-purple-900/30 file:text-purple-700 dark:file:text-purple-300 hover:file:bg-purple-200 dark:hover:file:bg-purple-900/50 file:cursor-pointer" />
                        <button type="button" wire:click="uploadAndAddExtraImage" wire:loading.attr="disabled" wire:target="newExtraImageFile,uploadAndAddExtraImage" class="flex items-center gap-1.5 bg-purple-500 hover:bg-purple-600 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                            <i class="fa-solid fa-cloud-arrow-up" wire:loading.remove wire:target="uploadAndAddExtraImage"></i>
                            <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="uploadAndAddExtraImage"></i>
                            Subir
                        </button>
                    </div>
                    @if($extraDownloadStatus === 'ok')
                        <p class="mt-2 text-xs font-bold text-green-600 dark:text-green-400">
                            <i class="fa-solid fa-check"></i> {{ $extraDownloadMessage }}
                        </p>
                    @elseif($extraDownloadStatus === 'error')
                        <p class="mt-2 text-xs font-bold text-red-600 dark:text-red-400">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $extraDownloadMessage }}
                        </p>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">UID Video (Cloudflare Stream)</label>
                    <input wire:model="video_uid" type="text" placeholder="32 caracteres hex del uid en Stream" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm font-mono" />
                    @if($video_uid)
                    <p class="mt-1 text-xs text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        <span>Stream UID configurado</span>
                        <a href="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video_uid }}/iframe" target="_blank" rel="noopener" class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 rounded-lg font-bold transition-colors">
                            <i class="fa-solid fa-external-link-alt text-[10px]"></i> Ver
                        </a>
                        <button wire:click="deleteVideo" wire:confirm="¿Eliminar este video de Cloudflare Stream?" class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-500/10 text-red-500 hover:bg-red-500/20 rounded-lg font-bold transition-colors">
                            <i class="fa-solid fa-trash-can text-[10px]"></i> Borrar
                        </button>
                    </p>
                    @endif
                    @if($videoDeleteStatus === 'ok')
                    <p class="mt-1 text-xs font-bold text-green-600 dark:text-green-400">
                        <i class="fa-solid fa-circle-check"></i> {{ $videoDeleteMessage }}
                    </p>
                    @elseif($videoDeleteStatus === 'error')
                    <p class="mt-1 text-xs font-bold text-red-600 dark:text-red-400">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $videoDeleteMessage }}
                    </p>
                    @endif
                </div>
            </div>

            {{-- Aumento calculado --}}
            <div class="border-t border-gray-100 dark:border-white/10 pt-4">
                <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-calculator text-amber-500 mr-1"></i> Sync VariedadesCR
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Aumento calculado</label>
                        <input type="text" value="{{ $variedades_increase > 0 ? '₡' . number_format($variedades_increase, 0, ',', '.') : '—' }}" readonly class="w-full bg-gray-100 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-500" />
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input wire:model="activo" type="checkbox" id="activo" class="text-[#00C4FF] rounded">
                    <label for="activo" class="text-sm font-bold text-gray-700 dark:text-gray-300">Producto activo</label>
                </div>
                <div class="flex items-center gap-3">
                    <input wire:model="proximo" type="checkbox" id="proximo" class="text-amber-500 rounded">
                    <label for="proximo" class="text-sm font-bold text-gray-700 dark:text-gray-300">
                        Próximo / Agotado
                        <span class="block text-xs font-normal text-gray-400">Producto aún no disponible (sin precio, sin stock) — se mostrará como "Agotado / Próximo"</span>
                    </label>
                </div>
                <div class="flex items-center gap-3">
                    <input wire:model="bloqueado" type="checkbox" id="bloqueado" class="text-amber-500 rounded">
                    <label for="bloqueado" class="text-sm font-bold text-gray-700 dark:text-gray-300">
                        Bloquear sincronización
                        <span class="block text-xs font-normal text-gray-400">Evita que el sync de VariedadesCR cambie el stock y precio</span>
                    </label>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Disponibilidad</label>
                    <select wire:model="disponibilidad" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="disponible">Disponible</option>
                        <option value="agotado">Agotado</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Si tiene stock pero marca "Agotado", no se mostrará como disponible en el sitio.</p>
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
