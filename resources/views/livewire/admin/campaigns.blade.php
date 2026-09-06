<div>
    <div class="flex gap-2 mb-4 sm:mb-6 overflow-x-auto scrollbar-none pb-1 -mx-6 px-6 sm:mx-0 sm:px-0 sm:flex-wrap snap-x snap-mandatory">
        <button wire:click="$set('activeTab', 'create')"
            class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap shrink-0 snap-start
            {{ $activeTab === 'create' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Crear
        </button>
        <button wire:click="$set('activeTab', 'utm')"
            class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap shrink-0 snap-start
            {{ $activeTab === 'utm' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-link"></i> UTM
        </button>
        <button wire:click="$set('activeTab', 'saved')"
            class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap shrink-0 snap-start
            {{ $activeTab === 'saved' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-bookmark"></i> Guardados
        </button>
    </div>

    {{-- TAB: CREAR --}}
    @if ($activeTab === 'create')
        <div class="space-y-3 sm:space-y-4">
            <div wire:key="image-canvas-block" x-data x-init="$nextTick(() => window.initInvictaImageCanvas())" class="space-y-3 sm:space-y-4">
                {{-- Selector de producto: full width, mobile-first --}}
                <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-2.5 sm:p-3 flex flex-col gap-2.5">
                    {{-- Fila búsqueda + filtros rápidos --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <i class="fa-solid fa-search text-gray-400 text-xs shrink-0"></i>
                            <input wire:model.live="productSearch" placeholder="Buscar modelo o título..."
                                class="flex-1 min-w-0 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-2 sm:py-1.5 text-xs sm:text-[11px] focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 overflow-x-auto scrollbar-none pb-0.5 sm:pb-0 -mx-1 px-1 sm:mx-0 sm:px-0 snap-x">
                            <button wire:click="setProductFilter('all')"
                                class="px-3 py-1.5 sm:py-1 rounded-full sm:rounded-md text-[11px] sm:text-[10px] font-bold whitespace-nowrap snap-start transition-colors {{ $productFilter === 'all' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 border border-transparent' }}">Todos</button>
                            <button wire:click="setProductFilter('pending')"
                                class="px-3 py-1.5 sm:py-1 rounded-full sm:rounded-md text-[11px] sm:text-[10px] font-bold whitespace-nowrap snap-start transition-colors {{ $productFilter === 'pending' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 border border-transparent' }}">Pendientes</button>
                            <button wire:click="exportFiltered"
                                class="px-3 py-1.5 sm:py-1 rounded-full sm:rounded-md text-[11px] sm:text-[10px] font-bold whitespace-nowrap snap-start bg-green-600 sm:bg-green-100 dark:bg-green-900/30 text-white sm:text-green-700 dark:text-green-400 hover:bg-green-700 sm:hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-table text-[10px]"></i> Exportar ZIP
                            </button>
                        </div>
                    </div>
                    {{-- Fila filtros: grid 2 cols en móvil, flex en desktop --}}
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-1.5">
                        <div class="flex items-center gap-1 col-span-2 sm:col-span-1 sm:flex-none">
                            <select wire:model.live="sortField" class="flex-1 sm:w-auto bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-2 sm:py-1.5 text-xs sm:text-[10px] focus:border-[#00C4FF] outline-none min-h-[36px] sm:min-h-0">
                                <option value="modelo">Modelo</option>
                                <option value="precio_venta">Precio</option>
                                <option value="created_at">Fecha</option>
                                <option value="size">Tamaño</option>
                            </select>
                            <button wire:click="sortBy(sortField)"
                                class="shrink-0 w-9 h-9 sm:w-auto sm:h-auto sm:px-2 sm:py-1 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 hover:text-[#00C4FF] flex items-center justify-center">
                                <i class="fa-solid {{ $sortDirection === 'asc' ? 'fa-arrow-up-wide-short' : 'fa-arrow-down-wide-short' }} text-xs"></i>
                            </button>
                        </div>
                        <select wire:model.live="filterColeccion" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-2 sm:py-1.5 text-xs sm:text-[10px] focus:border-[#00C4FF] outline-none min-h-[36px] sm:min-h-0">
                            <option value="">Todas las colecciones</option>
                            @foreach($colecciones as $col)
                                <option value="{{ $col }}">{{ $col }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filterColor" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-2 sm:py-1.5 text-xs sm:text-[10px] focus:border-[#00C4FF] outline-none min-h-[36px] sm:min-h-0">
                            <option value="">Todos los colores</option>
                            @foreach($colores as $color)
                                <option value="{{ $color }}">{{ $color }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filterBrazalete" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-2 sm:py-1.5 text-xs sm:text-[10px] focus:border-[#00C4FF] outline-none min-h-[36px] sm:min-h-0">
                            <option value="">Todos los brazaletes</option>
                            @foreach($brazaletes as $b)
                                <option value="{{ $b }}">{{ $b }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filterGenero" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-2 sm:py-1.5 text-xs sm:text-[10px] focus:border-[#00C4FF] outline-none min-h-[36px] sm:min-h-0">
                            <option value="">Todos los géneros</option>
                            <option value="hombre">Hombre</option>
                            <option value="mujer">Mujer</option>
                            <option value="unisex">Unisex</option>
                        </select>
                    </div>
                    {{-- Carrusel productos: scroll horizontal con snap --}}
                    <div class="flex gap-2 overflow-x-auto scrollbar-none pb-1 snap-x snap-mandatory -mx-2.5 px-2.5 sm:mx-0 sm:px-0">
                        @forelse($products as $p)
                            <button wire:click="$set('selectedProductId', {{ $p->id }})"
                                class="flex-shrink-0 p-1 sm:p-1 rounded-xl border-2 transition-all snap-start {{ $selectedProductId == $p->id ? 'border-[#00C4FF] bg-[#00C4FF]/5' : 'border-transparent hover:border-gray-200 dark:hover:border-white/15 bg-gray-50 dark:bg-white/[0.03]' }}"
                                style="width:76px">
                                @if ($p->imagen)
                                    <img src="{{ $p->imagen }}"
                                        class="w-full aspect-square object-contain rounded-lg bg-white dark:bg-white/5" loading="lazy" />
                                @else
                                    <div class="w-full aspect-square rounded-lg bg-gray-100 dark:bg-white/5 flex items-center justify-center"><i class="fa-solid fa-image text-gray-300"></i></div>
                                @endif
                                <p class="text-[9px] sm:text-[8px] font-bold text-gray-700 dark:text-gray-300 truncate mt-1 px-0.5">{{ $p->modelo }}</p>
                            </button>
                        @empty
                            <p class="text-xs text-gray-400 py-4 px-1">Sin resultados</p>
                        @endforelse
                    </div>
                    <div class="pt-1 border-t border-gray-100 dark:border-white/5 -mx-2.5 sm:mx-0 px-1">{{ $products->links() }}</div>
                </div>

                {{-- Grid principal: preview + controles --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 sm:gap-4">
                    {{-- Canvas preview: primero en móvil para feedback inmediato --}}
                    <div class="flex flex-col items-center justify-start p-2 sm:p-3 bg-[#050505] rounded-xl border border-white/10 order-1 xl:order-2 xl:sticky xl:top-4 self-start">
                        <p class="w-full text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2 flex items-center gap-1.5"><i class="fa-solid fa-eye text-[#00C4FF]"></i> Vista previa · 1080×1350</p>
                        <canvas id="adCanvas" width="1080" height="1350"
                            class="w-full h-auto rounded-[4px]"
                            style="max-width:100%;max-height:min(68vh,520px);width:auto;box-shadow:0 10px 40px rgba(0,0,0,.6);"></canvas>
                        <p class="text-[10px] text-white/30 mt-2 text-center">Pellizcá para hacer zoom en móvil · desliza para ver</p>
                    </div>

                    {{-- Columna controles --}}
                    <div class="flex flex-col gap-3 sm:gap-3 order-2 xl:order-1 min-w-0">
                        {{-- Controles imagen --}}
                        <div class="bg-white dark:bg-[#1c1c1e] rounded-xl border border-gray-200 dark:border-white/5 p-3 sm:p-3 space-y-3 xl:max-h-[calc(100vh-200px)] xl:overflow-y-auto text-gray-800 dark:text-gray-200">
                            <h2 class="font-bold text-[11px] sm:text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-5 h-5 sm:w-3.5 sm:h-3.5 rounded-full bg-[#d4af37] text-[9px] sm:text-[7px] flex items-center justify-center font-black text-white shrink-0">1</span>
                                Imagen del anuncio
                            </h2>
                            <p class="text-xs sm:text-[9px] text-gray-500 dark:text-gray-400 -mt-2">Los datos se cargan automáticamente del producto</p>

                            {{-- Inputs ocultos para que el JS del canvas los lea --}}
                            <input type="hidden" id="imgTitle" value="{{ $this->imageTemplateData['title'] }}" />
                            <input type="hidden" id="imgModelCode" value="{{ $this->imageTemplateData['modelCode'] }}" />
                            <input type="hidden" id="imgPrice" value="{{ $this->imageTemplateData['price'] }}" />
                            <input type="hidden" id="imgShipping" value="ENVÍO GRATIS" />
                            <textarea id="imgSpecs" class="sr-only">{{ $this->imageTemplateData['specs'] }}</textarea>
                            <input type="hidden" id="imgWhatsapp" value="8671-1422" />
                            <input type="hidden" id="imgWebsite" value="invictaCostaRica.com" />

                            <div>
                                <label class="block text-xs sm:text-[9px] uppercase font-bold tracking-wide text-gray-600 dark:text-gray-400 mb-1.5">Foto del reloj</label>
                                <input type="file" id="imgUpload" accept="image/*"
                                    data-product-image="{{ $this->imageTemplateData['image'] }}"
                                    class="w-full text-xs sm:text-[10px] text-gray-600 dark:text-gray-400 file:mr-2 file:px-3 file:py-2 sm:file:px-2 sm:file:py-1 file:rounded-xl sm:file:rounded-lg file:border-0 file:bg-gray-900 dark:file:bg-white file:text-white dark:file:text-gray-900 file:text-xs sm:file:text-[10px] file:font-bold" />
                            </div>
                            <div class="grid grid-cols-2 gap-3 sm:gap-1.5">
                                <div><label class="block text-xs sm:text-[9px] uppercase font-bold text-gray-500 dark:text-gray-400 mb-1">Escala</label><input type="range" id="imgScale" min="0.5" max="2" step="0.01" value="0.8" class="w-full accent-[#00C4FF] h-6 sm:h-4" /></div>
                                <div><label class="block text-xs sm:text-[9px] uppercase font-bold text-gray-500 dark:text-gray-400 mb-1">Vertical</label><input type="range" id="imgOffsetY" min="-200" max="200" step="1" value="0" class="w-full accent-[#00C4FF] h-6 sm:h-4" /></div>
                            </div>
                            <button type="button" id="imgDownloadBtn" wire:click="saveDownload" onclick="window.downloadCampaignPng()"
                                class="w-full px-4 py-3 sm:py-2.5 rounded-xl bg-[#d4af37] hover:brightness-110 active:scale-[0.98] text-[#1c1c1e] font-black uppercase tracking-tight text-xs sm:text-[11px] transition-all flex items-center justify-center gap-2 shadow-sm">
                                <i class="fa-solid fa-download"></i> Descargar PNG
                            </button>
                        </div>

                        {{-- Texto del anuncio --}}
                        @if ($generatedContent)
                            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3 sm:p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-bold text-[10px] sm:text-[11px] uppercase tracking-wider text-gray-900 dark:text-white flex items-center gap-1.5"><i class="fa-solid fa-quote-left text-[#00C4FF]"></i> Texto del anuncio</h3>
                                    <span class="text-[9px] text-gray-400 hidden sm:inline">Tocá para copiar</span>
                                </div>
                                <textarea id="ad-textarea" readonly rows="7" onclick="var t=this;t.select();t.setSelectionRange(0,99999);document.execCommand?document.execCommand('copy'):navigator.clipboard?.writeText(t.value);t.style.outline='2px solid #00C4FF';setTimeout(()=>t.style.outline='',1000)"
                                    class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2.5 text-xs sm:text-xs text-gray-700 dark:text-gray-300 font-mono resize-none leading-relaxed min-h-[140px] sm:min-h-0">{{ $generatedContent['headline'] }}
{{ $generatedContent['body'] }}
{{ $generatedContent['cta'] ? $generatedContent['cta'] : '' }}
                                </textarea>
                                <button onclick="var t=document.getElementById('ad-textarea');t.select();t.setSelectionRange(0,99999);document.execCommand?document.execCommand('copy'):navigator.clipboard?.writeText(t.value);this.innerHTML='<i class=&quot;fa-solid fa-check&quot;></i> ¡Copiado!';setTimeout(()=>this.innerHTML='<i class=&quot;fa-solid fa-copy&quot;></i> Copiar texto',1500)" class="mt-2 w-full sm:hidden bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl py-2.5 text-xs font-bold flex items-center justify-center gap-1.5"><i class="fa-solid fa-copy"></i> Copiar texto</button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
    </div>

    {{-- TAB: UTM --}}
    @elseif($activeTab === 'utm')
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="lg:col-span-2 space-y-3">
                <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3 sm:p-4">
                    <div class="space-y-3">
                        <input type="url" wire:model="utmUrl" placeholder="https://invictacostarica.com/producto/..."
                            class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-3 sm:py-2 text-sm sm:text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none min-h-[44px] sm:min-h-0" />
                        <div class="grid grid-cols-2 gap-2 sm:gap-2">
                            <select wire:model="utmSource"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-3 sm:px-2.5 sm:py-1.5 text-sm sm:text-xs min-h-[44px] sm:min-h-0">
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="google">Google</option>
                                <option value="email">Email</option>
                                <option value="tiktok">TikTok</option>
                            </select>
                            <select wire:model="utmMedium"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-3 sm:px-2.5 sm:py-1.5 text-sm sm:text-xs min-h-[44px] sm:min-h-0">
                                <option value="post">Post</option>
                                <option value="story">Historia</option>
                                <option value="ad">Anuncio</option>
                                <option value="bio">Bio</option>
                                <option value="newsletter">Newsletter</option>
                            </select>
                            <input type="text" wire:model="utmCampaign" placeholder="Campaña"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-3 sm:px-2.5 sm:py-1.5 text-sm sm:text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none min-h-[44px] sm:min-h-0" />
                            <input type="text" wire:model="utmTerm" placeholder="Término"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-3 sm:px-2.5 sm:py-1.5 text-sm sm:text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none min-h-[44px] sm:min-h-0" />
                        </div>
                        <input type="text" wire:model="utmContent" placeholder="Contenido (opcional)"
                            class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-3 sm:px-2.5 sm:py-1.5 text-sm sm:text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none min-h-[44px] sm:min-h-0" />
                        <button wire:click="generateUtm"
                            class="w-full bg-[#00C4FF] hover:bg-[#00b0e6] active:scale-[0.98] text-[#0a0f1c] font-black uppercase tracking-tight px-4 py-3.5 sm:py-2.5 rounded-xl text-sm sm:text-xs transition-all flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-link"></i> Generar UTM
                        </button>
                    </div>
                </div>
                <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Los UTM permiten rastrear
                        visitantes en Google Analytics. Usalos para medir cada canal.</p>
                </div>
            </div>
            <div class="lg:col-span-3 space-y-3">
                @if ($generatedUtm)
                    <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                        <div x-data="{ copy: false }">
                            <div
                                class="bg-gray-50 dark:bg-[#0a0f1c] rounded-lg p-3 border border-gray-200 dark:border-white/10">
                                <p class="text-xs text-gray-700 dark:text-gray-300 break-all font-mono">
                                    {{ $generatedUtm }}</p>
                            </div>
                            <div class="flex gap-2 mt-2">
                                <button
                                    @click="navigator.clipboard.writeText(document.querySelector('#utm-url').value); copy = true; setTimeout(() => copy = false, 2000)"
                                    class="flex-1 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1"
                                    :class="copy ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                        'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10'">
                                    <i class="fa-solid" :class="copy ? 'fa-check' : 'fa-copy'"></i>
                                    <span x-text="copy ? 'Copiado' : 'Copiar'"></span>
                                </button>
                                <a href="{{ $generatedUtm }}" target="_blank" rel="noopener"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-all flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Probar
                                </a>
                            </div>
                            <input id="utm-url" type="text" value="{{ $generatedUtm }}" class="sr-only" />
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- TAB: GUARDADOS --}}
    @elseif($activeTab === 'saved')
        <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-4">
            <h2 class="font-bold text-gray-900 dark:text-white mb-3 text-xs uppercase tracking-wider">Anuncios
                guardados</h2>
            @if ($savedAds->count() > 0)
                <div class="space-y-2">
                    @foreach ($savedAds as $ad)
                        <div
                            class="p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="font-bold text-gray-900 dark:text-white text-xs">{{ $ad->title }}
                                        </p>
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase
                                {{ $ad->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                            {{ $ad->status }}
                                        </span>
                                        @if ($ad->type)
                                            <span
                                                class="px-1.5 py-0.5 rounded bg-gray-200 dark:bg-white/10 text-gray-500 dark:text-gray-400 text-[9px] font-mono">{{ str_replace('ad_', '', $ad->type) }}</span>
                                        @endif
                                    </div>
                                    <p
                                        class="text-[10px] text-gray-500 dark:text-gray-400 line-clamp-2 whitespace-pre-line">
                                        {{ $ad->description }}</p>
                                    <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-0.5">
                                        {{ $ad->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex gap-1.5 flex-shrink-0">
                                    <textarea class="sr-only" id="saved-ad-{{ $ad->id }}">{{ $ad->description }}</textarea>
                                    <button
                                        onclick="navigator.clipboard.writeText(document.getElementById('saved-ad-{{ $ad->id }}').value); this.querySelector('i').className = 'fa-solid fa-check'; setTimeout(() => this.querySelector('i').className = 'fa-solid fa-copy', 2000)"
                                        class="p-1.5 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:text-[#00C4FF] transition-all text-[10px]">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8">
                    <div
                        class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-2">
                        <i class="fa-solid fa-bookmark text-lg text-gray-300 dark:text-gray-600"></i>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold">No hay anuncios guardados</p>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Generá un anuncio y guardalo</p>
                </div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
    <script>
        window.downloadCampaignPng = function() {
            const canvas = document.getElementById('adCanvas');
            const modelInput = document.getElementById('imgModelCode');
            if (!canvas) return;
            const modelCode = (modelInput ? modelInput.value : '') || 'invicta';
            const filename = modelCode.replace(/[^a-zA-Z0-9_-]/g, '') + '.png';
            canvas.toBlob((blob) => {
                if (!blob) return;
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = filename;
                link.rel = 'noopener';
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                setTimeout(() => URL.revokeObjectURL(url), 1000);
            }, 'image/png');
        };

        window.initInvictaImageCanvas = function() {
            const canvas = document.getElementById('adCanvas');
            if (!canvas || canvas.dataset.invictaInit === '1') return;
            canvas.dataset.invictaInit = '1';
            const ctx = canvas.getContext('2d');
            const W = canvas.width,
                H = canvas.height;

            const themes = {
                gold: {
                    dark: '#8a5a00',
                    light: '#e6b800',
                    cream: '#fdf6e3',
                    accent: '#1e2a4a',
                    badge: '#c0212b',
                    text: '#2b2b2b'
                },
                blue: {
                    dark: '#0b2447',
                    light: '#1a5fb4',
                    cream: '#eaf2fb',
                    accent: '#0b2447',
                    badge: '#c0212b',
                    text: '#2b2b2b'
                },
                dark: {
                    dark: '#141414',
                    light: '#3a3a3a',
                    cream: '#eceff1',
                    accent: '#141414',
                    badge: '#c0212b',
                    text: '#2b2b2b'
                },
                green: {
                    dark: '#0e3d24',
                    light: '#1f7a4d',
                    cream: '#eef7f0',
                    accent: '#0e3d24',
                    badge: '#c0212b',
                    text: '#2b2b2b'
                },
                red: {
                    dark: '#5a0a0a',
                    light: '#c0212b',
                    cream: '#fbeef0',
                    accent: '#5a0a0a',
                    badge: '#1e2a4a',
                    text: '#2b2b2b'
                },
                purple: {
                    dark: '#2e1065',
                    light: '#6d28d9',
                    cream: '#f3eefb',
                    accent: '#2e1065',
                    badge: '#c0212b',
                    text: '#2b2b2b'
                },
                teal: {
                    dark: '#0c4a4a',
                    light: '#0d9488',
                    cream: '#eef9f8',
                    accent: '#0c4a4a',
                    badge: '#c0212b',
                    text: '#2b2b2b'
                },
            };
            let currentTheme = 'blue';
            let watchImg = null;
            let waIcon = new Image();
            waIcon.src = '/images/whatsapp.svg';

            const fieldIds = ['imgTitle', 'imgModelCode', 'imgPrice', 'imgShipping', 'imgSpecs', 'imgWhatsapp',
                'imgWebsite', 'imgScale', 'imgOffsetY'
            ];
            fieldIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', draw);
            });

            const imgUploadEl = document.getElementById('imgUpload');
            imgUploadEl.addEventListener('change', e => {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = ev => {
                    const img = new Image();
                    img.onload = () => {
                        watchImg = img;
                        draw();
                    };
                    img.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });

            function loadImageFromUrl(url) {
                if (!url) return;
                
                // Si la imagen es del CDN, usar proxy para evitar problemas de CORS
                let imageUrl = url;
                if (url.includes('cdn.invictacostarica.com')) {
                    const path = url.replace('https://cdn.invictacostarica.com/', '');
                    imageUrl = '/api/image-proxy/' + path;
                }
                
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => {
                    watchImg = img;
                    draw();
                };
                img.onerror = () => {
                    console.warn('No se pudo cargar la imagen del producto.');
                    // Intentar sin crossOrigin como fallback
                    const fallbackImg = new Image();
                    fallbackImg.onload = () => {
                        watchImg = fallbackImg;
                        draw();
                    };
                    fallbackImg.src = url;
                };
                img.src = imageUrl;
            }

            // Auto-carga la foto del producto ya seleccionado al montar la pestaña
            loadImageFromUrl(imgUploadEl.dataset.productImage);

            window.addEventListener('set-theme', (e) => {
                currentTheme = e.detail;
                draw();
            });

            // Livewire despacha este evento automáticamente cada vez que se
            // selecciona un producto (sin necesidad de ningún botón manual).
            document.addEventListener('populate-image-fields', (e) => {
                const d = e.detail.payload || e.detail || {};
                if (d.title && document.getElementById('imgTitle')) document.getElementById('imgTitle').value =
                    d.title;
                if (d.modelCode !== undefined) document.getElementById('imgModelCode').value = d.modelCode;
                if (d.price) document.getElementById('imgPrice').value = d.price;
                if (d.specs) document.getElementById('imgSpecs').value = d.specs;
                if (d.image) loadImageFromUrl(d.image);
                draw();
            });

            function roundRect(x, y, w, h, r) {
                ctx.beginPath();
                ctx.moveTo(x + r, y);
                ctx.arcTo(x + w, y, x + w, y + h, r);
                ctx.arcTo(x + w, y + h, x, y + h, r);
                ctx.arcTo(x, y + h, x, y, r);
                ctx.arcTo(x, y, x + w, y, r);
                ctx.closePath();
            }

            function draw() {
                const t = themes[currentTheme];
                ctx.clearRect(0, 0, W, H);

                ctx.fillStyle = t.cream;
                ctx.fillRect(0, 0, W, H);

                const splitX = W * 0.5;
                ctx.save();
                const grad = ctx.createLinearGradient(0, 0, W * 0.62, H);
                grad.addColorStop(0, t.dark);
                grad.addColorStop(1, t.light);
                ctx.fillStyle = grad;
                ctx.beginPath();
                ctx.moveTo(0, 0);
                ctx.lineTo(splitX + 120, 0);
                ctx.lineTo(splitX - 120, H);
                ctx.lineTo(0, H);
                ctx.closePath();
                ctx.fill();
                ctx.restore();

                ctx.save();
                ctx.globalAlpha = 0.06;
                ctx.strokeStyle = t.dark;
                ctx.lineWidth = 30;
                for (let x = splitX - 200; x < W + 200; x += 90) {
                    ctx.beginPath();
                    ctx.moveTo(x, 0);
                    ctx.lineTo(x - 260, H);
                    ctx.stroke();
                }
                ctx.restore();

                const titleText = (document.getElementById('imgTitle').value || '').toUpperCase();
                const maxTitleWidth = W * 0.56;
                let titleFontSize = 82;
                ctx.save();
                ctx.font = `italic bold ${titleFontSize}px Arial`;
                while (ctx.measureText(titleText).width > maxTitleWidth && titleFontSize > 28) {
                    titleFontSize -= 2;
                    ctx.font = `italic bold ${titleFontSize}px Arial`;
                }
                ctx.fillStyle = '#ffffff';
                ctx.shadowColor = 'rgba(0,0,0,.35)';
                ctx.shadowBlur = 6;
                ctx.shadowOffsetX = 3;
                ctx.shadowOffsetY = 3;
                ctx.textBaseline = 'top';
                const ty = 70;
                ctx.fillText(titleText, 60, ty);
                ctx.restore();

                ctx.save();
                ctx.font = '36px Arial';
                ctx.fillStyle = 'rgba(255,255,255,.85)';
                ctx.fillText(document.getElementById('imgModelCode').value, 62, ty + 100);
                ctx.restore();

                // ===== CÍRCULO CENTRADO (modificar posición y tamaño) =====
                const cx = W / 2,
                    cy = H * 0.48;
                const r = 450; // <-- aumentá este número para agrandar el círculo del reloj
                // Fondo del círculo (blanco con sombra)
                ctx.save();
                ctx.beginPath();
                ctx.arc(cx, cy, r, 0, Math.PI * 2);
                ctx.fillStyle = '#ffffff';
                ctx.shadowColor = 'rgba(0,0,0,.15)';
                ctx.shadowBlur = 30;
                ctx.fill();
                ctx.restore();

                // ===== BLOQUE DE PRECIO: badge con pestaña dorada arriba =====
                const badgePadX = 40; // padding desde el borde izquierdo
                const badgeW = 520; // ancho del bloque
                const badgeH = 160; // alto del badge rojo
                const badgeY = (H - 100) - badgeH; // badge con margen inferior de 100
                ctx.save();
                ctx.shadowColor = 'rgba(0,0,0,.25)';
                ctx.shadowBlur = 20;
                ctx.shadowOffsetY = 8;
                ctx.fillStyle = t.badge;
                roundRect(badgePadX, badgeY, badgeW, badgeH, badgeH / 2);
                ctx.fill();
                ctx.restore();

                // pestaña dorada de envío (pequeña, centrada sobre el borde superior)
                const tagW = 300;
                const tagH = 44;
                const tagX = badgePadX + (badgeW - tagW) / 2;
                const tagY = badgeY - tagH / 2;
                ctx.save();
                ctx.shadowColor = 'rgba(0,0,0,.2)';
                ctx.shadowBlur = 12;
                ctx.shadowOffsetY = 4;
                ctx.fillStyle = '#e6b800';
                roundRect(tagX, tagY, tagW, tagH, tagH / 2);
                ctx.fill();
                ctx.shadowColor = 'transparent';
                ctx.shadowBlur = 0;
                ctx.shadowOffsetY = 0;
                ctx.font = 'bold 20px Arial';
                ctx.fillStyle = '#1c1c1c';
                ctx.textBaseline = 'middle';
                ctx.textAlign = 'center';
                ctx.fillText(document.getElementById('imgShipping').value, tagX + tagW / 2, tagY + tagH / 2);
                ctx.restore();

                // precio centrado vertical y horizontalmente dentro del badge
                ctx.save();
                const priceText = document.getElementById('imgPrice').value;
                let priceFontSize = 68;
                ctx.font = `bold ${priceFontSize}px Arial`;
                while (ctx.measureText(priceText).width > badgeW - 60 && priceFontSize > 40) {
                    priceFontSize -= 4;
                    ctx.font = `bold ${priceFontSize}px Arial`;
                }
                ctx.fillStyle = '#ffffff';
                ctx.textBaseline = 'middle';
                ctx.textAlign = 'center';
                ctx.shadowColor = 'rgba(0,0,0,.3)';
                ctx.shadowBlur = 4;
                ctx.shadowOffsetY = 2;
                ctx.fillText(priceText, badgePadX + badgeW / 2, badgeY + badgeH / 2);
                ctx.restore();

                if (watchImg) {
                    const scale = parseFloat(document.getElementById('imgScale').value);
                    const offsetY = parseFloat(document.getElementById('imgOffsetY').value);
                    const baseSize = r * 2.1 * scale;
                    const ratio = watchImg.width / watchImg.height;
                    let dw, dh;
                    if (ratio >= 1) {
                        dw = baseSize;
                        dh = baseSize / ratio;
                    } else {
                        dh = baseSize;
                        dw = baseSize * ratio;
                    }
                    // Recorta la imagen al círculo para que el fondo blanco no tape la plantilla
                    ctx.save();
                    ctx.beginPath();
                    ctx.arc(cx, cy, r, 0, Math.PI * 2);
                    ctx.clip();
                    const dx = cx - dw / 2;
                    const dy = cy - dh / 2 + offsetY;
                    ctx.drawImage(watchImg, dx, dy, dw, dh);
                    ctx.restore();
                    // Borde sutil del círculo para definir el recorte
                    ctx.save();
                    ctx.beginPath();
                    ctx.arc(cx, cy, r, 0, Math.PI * 2);
                    ctx.strokeStyle = 'rgba(0,0,0,.06)';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                    ctx.restore();
                } else {
                    ctx.save();
                    ctx.fillStyle = '#999';
                    ctx.font = '24px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText('Sube la foto del reloj', cx, cy);
                    ctx.restore();
                }

                // ===== ESPECIFICACIONES (al lado derecho del círculo) =====
                const specLines = (document.getElementById('imgSpecs').value || '').split('\n').filter(l => l.trim() !==
                    '');
                const specYoffset = 500; // <-- ajustá para subir/bajar (+ = baja, - = sube)
                ctx.save();
                ctx.font = '30px Arial';
                ctx.fillStyle = t.text;
                ctx.textAlign = 'right';
                const specStartY = cy - ((specLines.length - 1) * 30) - 30 + specYoffset;
                specLines.forEach((line, i) => {
                    const sy = specStartY + i * 60;
                    ctx.fillText(line.trim(), W - 35, sy);
                });
                ctx.restore();

                // ===== WHATSAPP ARRIBA (modificar posición, tamaño y color) =====
                const waText = document.getElementById('imgWhatsapp').value;
                ctx.save();
                ctx.font = 'bold 38px Arial';
                ctx.fillStyle = '#1c1c1e';
                ctx.textAlign = 'right';
                ctx.textBaseline = 'middle'; // <- clave: fija el mismo eje vertical para texto e ícono

                const waX = W - 35; // posición X del texto (borde derecho)
                const waY = 70; // posición Y (centro vertical de todo el bloque)
                const gap = 28; // separación entre ícono y texto

                const textWidth = ctx.measureText(waText).width;
                const iconRadius = 30;
                const iconX = waX - textWidth - gap - iconRadius;

                // círculo de recorte para el SVG de WhatsApp (sin relleno)
                // Ajustá iconRadius para cambiar el tamaño del círculo
                if (waIcon.complete && waIcon.naturalWidth > 0) {
                    const waIconScale = 2.5; // <-- aumentá para agrandar el icono dentro del círculo
                    const iconSize = iconRadius * waIconScale;
                    ctx.save();
                    ctx.beginPath();
                    ctx.arc(iconX, waY, iconRadius, 0, Math.PI * 2);
                    ctx.clip();
                    ctx.drawImage(waIcon, iconX - iconSize / 2, waY - iconSize / 2, iconSize, iconSize);
                    ctx.restore();
                }

                // texto del número
                ctx.fillStyle = '#1c1c1e';
                ctx.fillText(waText, waX, waY);
                ctx.restore();

                // ===== WEB (esquina inferior derecha) =====
                ctx.save();
                ctx.font = 'bold 42px Arial';
                ctx.fillStyle = 'rgba(0, 0, 0, 0.72)';
                ctx.textAlign = 'right';
                ctx.textBaseline = 'bottom';
                ctx.fillText(document.getElementById('imgWebsite').value, W - 40, H - 30);
                ctx.restore();
            }

            // Vuelve a dibujar si Livewire re-renderiza el componente
            draw();
        };
    </script>
@endpush
