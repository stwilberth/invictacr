<div x-data="{ dirty: false }" x-init="window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } })">
    <h2 class="text-xl font-black mb-4">{{ $productId ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <form wire:submit="save" class="space-y-6" @input="dirty = true" @change="dirty = true" @submit="dirty = false">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2 lg:col-span-4">
                    <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/10 pb-1">Identificación</h3>
                </div>
                <div class="lg:col-span-3">
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
                <div class="md:col-span-2 lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input wire:model="title" type="text" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
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
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Género</label>
                    <select wire:model="genero" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Seleccionar</option>
                        <option value="hombre">Hombre</option>
                        <option value="mujer">Mujer</option>
                        <option value="unisex">Unisex</option>
                    </select>
                </div>
                <div class="md:col-span-2 lg:col-span-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea wire:model="descripcion" rows="3" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"></textarea>
                </div>
                <div class="md:col-span-2 lg:col-span-4 mt-2">
                    <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/10 pb-1">Precio y stock</h3>
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
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Precio Costo (sin IVA)</label>
                    <input wire:model="precio_costo" type="number" step="0.01" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                    @error('precio_costo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Descuento (%)</label>
                    <input wire:model="descuento" type="number" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Stock</label>
                    <input wire:model="stock" type="number" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                </div>
                <div class="md:col-span-2 lg:col-span-4 mt-2">
                    <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/10 pb-1">Características</h3>
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
                <div class="md:col-span-2 lg:col-span-4 mt-2">
                    <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/10 pb-1">Fotos y video</h3>
                </div>
                <div class="md:col-span-2 lg:col-span-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Imagen principal</label>
                    <div class="space-y-2">
                        <div class="flex flex-wrap gap-2">
                            <input wire:model="imagen" type="text" placeholder="https://cdn.invictawatch.com/..." class="flex-1 min-w-[140px] bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
                            <button type="button" wire:click="downloadImage" wire:loading.attr="disabled" wire:target="downloadImage" class="flex-1 sm:flex-none flex items-center justify-center gap-1.5 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all">
                                <i class="fa-solid fa-download" wire:loading.remove wire:target="downloadImage"></i>
                                <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="downloadImage"></i>
                                Descargar
                            </button>
                            <button type="button" wire:click="$set('imagen', '/storage/relojes/' + {{ json_encode($modelo) }} + '.jpg')" class="flex-1 sm:flex-none flex items-center justify-center gap-1.5 bg-green-500 hover:bg-green-600 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all">
                                <i class="fa-solid fa-folder-open"></i>
                                Local
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
                <div class="md:col-span-2 lg:col-span-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">UID Video (Cloudflare Stream)</label>
                    <input id="video_uid_input" wire:model="video_uid" type="text" placeholder="32 caracteres hex del uid en Stream" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm font-mono" />
                    @if($video_uid)
                    <p class="mt-1 text-xs text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        <span>Stream UID configurado</span>
                        <a href="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video_uid }}/iframe" target="_blank" rel="noopener" class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 rounded-lg font-bold transition-colors">
                            <i class="fa-solid fa-external-link-alt text-[10px]"></i> Ver
                        </a>
                        <button wire:click="deleteVideo" wire:confirm="¿Quitar el video? Se eliminará de Cloudflare al guardar el producto." class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-500/10 text-red-500 hover:bg-red-500/20 rounded-lg font-bold transition-colors">
                            <i class="fa-solid fa-trash-can text-[10px]"></i> Quitar
                        </button>
                    </p>
                    @endif
                    @if($videoDeleteStatus === 'staged')
                    <p class="mt-1 text-xs font-bold text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-hourglass-half"></i> {{ $videoDeleteMessage }}
                    </p>
                    @elseif($videoDeleteStatus === 'ok')
                    <p class="mt-1 text-xs font-bold text-green-600 dark:text-green-400">
                        <i class="fa-solid fa-circle-check"></i> {{ $videoDeleteMessage }}
                    </p>
                    @elseif($videoDeleteStatus === 'error')
                    <p class="mt-1 text-xs font-bold text-red-600 dark:text-red-400">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $videoDeleteMessage }}
                    </p>
                    @endif
                    @if($video_uid)
                    <div class="mt-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-[#0a0f1c] p-3">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Escenas del video</label>
                        <p class="text-xs text-gray-400 mb-2">La primera es la principal: foto principal, miniatura, arranque del video y portada en /relojes. Las demás salen como fotos en la galería. Máximo 10 extras.</p>
                        @if($scenesSuggested)
                        <p class="mb-2 text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 rounded-xl px-3 py-2">
                            <i class="fa-solid fa-triangle-exclamation"></i> Estas escenas son sugerencias, aún no están guardadas. Revisalas y dale «Actualizar Producto».
                        </p>
                        @endif
                        <div class="flex gap-3 mt-2 overflow-x-auto pb-2">
                            <div class="w-56 flex-shrink-0 rounded-xl border border-[#00C4FF]/40 bg-white dark:bg-[#0f172a] p-2">
                                <p class="text-[11px] font-black text-[#00C4FF] uppercase tracking-wider mb-1">Principal</p>
                                <div class="flex items-center gap-1.5">
                                    <input id="video_thumb_time_input" wire:model="video_thumb_time" type="number" min="0" max="3600" step="1" placeholder="0" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                                    <span class="text-xs text-gray-400">s</span>
                                </div>
                                <div class="flex gap-1 mt-1.5">
                                    @foreach([0, 2, 5, 10] as $s)
                                    <button type="button" data-thumb-seconds="{{ $s }}" class="thumb-quick-btn px-2 py-1 text-xs font-bold rounded-lg bg-gray-200 dark:bg-white/10 text-gray-600 dark:text-gray-300 hover:bg-[#00C4FF] hover:text-white transition-colors">{{ $s }}s</button>
                                    @endforeach
                                </div>
                                @error('video_thumb_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <div class="mt-1.5 rounded-lg overflow-hidden bg-black">
                                    <img id="video_thumb_preview" data-thumb-width="960" data-thumb-height="540" src="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video_uid }}/thumbnails/thumbnail.jpg?time={{ (int) ($video_thumb_time ?? 0) }}s&width=960&height=540" alt="Escena principal" class="w-full h-auto object-contain" loading="lazy" />
                                </div>
                            </div>
                            @foreach($video_extra_scenes as $i => $sec)
                            <div class="w-56 flex-shrink-0 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-[#0f172a] p-2">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider">#{{ $i + 1 }}</p>
                                    <button type="button" wire:click="removeExtraScene({{ $i }})" class="px-2 py-1 text-xs font-bold rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors" title="Quitar escena">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <input wire:model="video_extra_scenes.{{ $i }}" type="number" min="0" max="3600" step="1" placeholder="0" data-preview="extra-scene-preview-{{ $i }}" class="extra-scene-input w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                                    <span class="text-xs text-gray-400">s</span>
                                </div>
                                <div class="mt-1.5 rounded-lg overflow-hidden bg-black">
                                    <img id="extra-scene-preview-{{ $i }}" data-uid="{{ $video_uid }}" data-thumb-width="960" data-thumb-height="540" src="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video_uid }}/thumbnails/thumbnail.jpg?time={{ (int) ($sec ?? 0) }}s&width=960&height=540" alt="Escena extra {{ $i + 1 }}" class="w-full h-auto object-contain" loading="lazy" />
                                </div>
                            </div>
                            @endforeach
                            <button type="button" wire:click="addExtraScene" class="w-40 flex-shrink-0 rounded-xl border-2 border-dashed border-gray-300 dark:border-white/15 text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors min-h-[160px] flex flex-col items-center justify-center gap-1 text-xs font-bold uppercase tracking-wider">
                                <i class="fa-solid fa-plus text-lg"></i> Agregar escena
                            </button>
                        </div>
                        @error('video_extra_scenes.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <script>
                    (function () {
                        function paintPreview(img, seconds) {
                            var base = thumbBase(img);
                            if (!img || !base) return;
                            var w = img.getAttribute('data-thumb-width') || '960';
                            var h = img.getAttribute('data-thumb-height') || '540';
                            img.src = base + '?time=' + seconds + 's&width=' + w + '&height=' + h;
                        }
                        function thumbBase(img) {
                            var uidEl = document.getElementById('video_uid_input');
                            var uid = (uidEl ? uidEl.value.trim() : '') || (img ? img.getAttribute('data-uid') || '' : '');
                            if (!uid) return null;
                            return 'https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/' + uid + '/thumbnails/thumbnail.jpg';
                        }
                        function refreshThumbPreview() {
                            var secEl = document.getElementById('video_thumb_time_input');
                            var img = document.getElementById('video_thumb_preview');
                            if (!secEl || !img) return;
                            var s = secEl.value !== '' ? Math.max(0, parseInt(secEl.value, 10) || 0) : 0;
                            paintPreview(img, s);
                        }
                        function refreshExtraPreviews() {
                            document.querySelectorAll('.extra-scene-input').forEach(function (secEl) {
                                var img = document.getElementById(secEl.getAttribute('data-preview'));
                                if (!img) return;
                                var s = secEl.value !== '' ? Math.max(0, parseInt(secEl.value, 10) || 0) : 0;
                                paintPreview(img, s);
                            });
                        }
                        document.addEventListener('input', function (e) {
                            if (!e.target) return;
                            if (e.target.id === 'video_thumb_time_input') refreshThumbPreview();
                            else if (e.target.classList && e.target.classList.contains('extra-scene-input')) refreshExtraPreviews();
                            else if (e.target.id === 'video_uid_input') { refreshThumbPreview(); refreshExtraPreviews(); }
                        });
                        document.addEventListener('click', function (e) {
                            var btn = e.target && e.target.closest ? e.target.closest('.thumb-quick-btn') : null;
                            if (!btn) return;
                            var secEl = document.getElementById('video_thumb_time_input');
                            if (!secEl) return;
                            secEl.value = btn.getAttribute('data-thumb-seconds');
                            secEl.dispatchEvent(new Event('input', { bubbles: true }));
                            refreshThumbPreview();
                        });
                    })();
                    </script>
                    @endif
                </div>
            </div>

            {{-- Precios calculados (PricingService) --}}
            <div class="border-t border-gray-100 dark:border-white/10 pt-4">
                <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-calculator text-amber-500 mr-1"></i> Precios sugeridos
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Costo sugerido</label>
                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ $costoSugerido !== null ? '₡' . number_format((float) $costoSugerido, 2) : '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Precio sugerido</label>
                        <p class="text-sm font-black text-[#00C4FF]">{{ $precioSugerido !== null ? '₡' . number_format((float) $precioSugerido, 0) : '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Precio mínimo</label>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $precioMinimo !== null ? '₡' . number_format((float) $precioMinimo, 0) : '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Margen esperado</label>
                        <p class="text-sm font-bold {{ $margenEsperado !== null && $margenEsperado >= config('pricing.minimum_margin_percent') ? 'text-green-600' : 'text-red-500' }}">{{ $margenEsperado !== null ? number_format((float) $margenEsperado, 2) . '%' : '—' }}</p>
                    </div>
                </div>
                @if($descuentoMaximo !== null)
                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                        Descuento máximo permitido: <span class="font-bold">{{ number_format((float) $descuentoMaximo, 2) }}%</span> (margen de promoción {{ config('pricing.promotion_minimum_margin_percent') }}%).
                    </p>
                @endif
                <button type="button" wire:click="recalcular" class="mt-3 inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-xl transition-all">
                    <i class="fa-solid fa-rotate"></i> Recalcular precio sugerido
                </button>
                <div class="flex items-center gap-3 pt-4">
                    <input wire:model="manual_override" type="checkbox" id="manual_override" class="text-amber-500 rounded">
                    <label for="manual_override" class="text-sm font-bold text-gray-700 dark:text-gray-300">
                        Precio manual (no sincronizar)
                        <span class="block text-xs font-normal text-gray-400">El sincronizador no modificará costo, precio original ni precio de venta</span>
                    </label>
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
            <div class="flex flex-wrap gap-3">
                <button type="submit" wire:loading.attr="disabled" wire:target="save" @click="dirty = false" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 text-center bg-[#00C4FF] hover:bg-[#00b0e6] disabled:opacity-60 disabled:cursor-not-allowed text-[#0a0f1c] font-black px-8 py-3 rounded-xl transition-all uppercase tracking-wider text-sm">
                    <i wire:loading.remove wire:target="save" class="fa-solid fa-floppy-disk"></i>
                    <i wire:loading wire:target="save" class="fa-solid fa-spinner fa-spin"></i>
                    <span wire:loading.remove wire:target="save">{{ $productId ? 'Actualizar' : 'Crear' }} Producto</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
                <a href="{{ route('admin.products') }}" @click="if (dirty && !confirm('Tenés cambios sin guardar. ¿Salir de todos modos?')) $event.preventDefault()" class="flex-1 sm:flex-none text-center bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-bold px-8 py-3 rounded-xl text-sm">Cancelar</a>
            </div>
        </form>
    </div>
</div>
