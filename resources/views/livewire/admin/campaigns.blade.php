<div>
    <div class="flex gap-2 mb-6 flex-wrap">
        <button wire:click="$set('activeTab', 'create')"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
            {{ $activeTab === 'create' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Crear
        </button>
        <button wire:click="$set('activeTab', 'utm')"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
            {{ $activeTab === 'utm' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-link"></i> UTM
        </button>
        <button wire:click="$set('activeTab', 'saved')"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
            {{ $activeTab === 'saved' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-bookmark"></i> Guardados
        </button>
    </div>

    {{-- TAB: CREAR (3 columnas: producto | controles | preview) --}}
    @if ($activeTab === 'create')
        <div class="space-y-3">
            <div wire:key="image-canvas-block" x-data x-init="$nextTick(() => window.initInvictaImageCanvas())">
                {{-- Columna 1: Selector de producto (vertical) --}}
                <div class="grid grid-cols-2 gap-3">

                    {{-- Columna 2: Controles del formulario de imagen --}}
                    <div class="flex flex-col">
                        <div
                            class="col-span-2 bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-2 flex flex-col">
                            <div class="flex items-center gap-1 mb-2 px-1">
                                <i class="fa-solid fa-search text-gray-400 text-[10px]"></i>
                                <input wire:model.live="productSearch" placeholder="Buscar..."
                                    class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-md px-2 py-1 text-[10px] focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                                <button wire:click="setProductFilter('all')"
                                    class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $productFilter === 'all' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-gray-100 dark:bg-white/5 text-gray-500' }}">Todos</button>
                                <button wire:click="setProductFilter('pending')"
                                    class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $productFilter === 'pending' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-gray-100 dark:bg-white/5 text-gray-500' }}">Pendientes</button>
                                <button wire:click="exportFiltered"
                                    class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/30 transition-all">
                                    <i class="fa-solid fa-table"></i> Exportar
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-1 mb-2 px-1">
                                <select wire:model.live="sortField" class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-md px-2 py-1 text-[10px] focus:border-[#00C4FF] outline-none">
                                    <option value="modelo">Modelo</option>
                                    <option value="precio_venta">Precio</option>
                                    <option value="created_at">Fecha</option>
                                    <option value="size">Tamaño</option>
                                </select>
                                <button wire:click="sortBy(sortField)"
                                    class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-[#00C4FF]">
                                    <i class="fa-solid {{ $sortDirection === 'asc' ? 'fa-arrow-up-wide-short' : 'fa-arrow-down-wide-short' }}"></i>
                                </button>
                                <select wire:model.live="filterColeccion" class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-md px-2 py-1 text-[10px] focus:border-[#00C4FF] outline-none">
                                    <option value="">Todas las colecciones</option>
                                    @foreach($colecciones as $col)
                                        <option value="{{ $col }}">{{ $col }}</option>
                                    @endforeach
                                </select>
                                <select wire:model.live="filterColor" class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-md px-2 py-1 text-[10px] focus:border-[#00C4FF] outline-none">
                                    <option value="">Todos los colores</option>
                                    @foreach($colores as $color)
                                        <option value="{{ $color }}">{{ $color }}</option>
                                    @endforeach
                                </select>
                                <select wire:model.live="filterBrazalete" class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-md px-2 py-1 text-[10px] focus:border-[#00C4FF] outline-none">
                                    <option value="">Todos los brazaletes</option>
                                    @foreach($brazaletes as $b)
                                        <option value="{{ $b }}">{{ $b }}</option>
                                    @endforeach
                                </select>
                                <select wire:model.live="filterGenero" class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-md px-2 py-1 text-[10px] focus:border-[#00C4FF] outline-none">
                                    <option value="">Todos los géneros</option>
                                    <option value="hombre">Hombre</option>
                                    <option value="mujer">Mujer</option>
                                    <option value="unisex">Unisex</option>
                                </select>
                            </div>
                            <div class="flex gap-2 overflow-x-auto pb-1">
                                @forelse($products as $p)
                                    <button wire:click="$set('selectedProductId', {{ $p->id }})"
                                        class="flex-shrink-0 p-1 rounded-lg border-2 transition-all {{ $selectedProductId == $p->id ? 'border-[#00C4FF]' : 'border-transparent hover:border-gray-200 dark:hover:border-white/20' }}"
                                        style="width:90px">
                                        @if ($p->imagen)
                                            <img src="{{ $p->imagen }}"
                                                class="w-full aspect-square object-contain rounded" loading="lazy" />
                                        @endif
                                        <p
                                            class="text-[8px] font-medium text-gray-700 dark:text-gray-300 truncate mt-0.5">
                                            {{ $p->modelo }}</p>
                                    </button>
                                @empty
                                    <p class="text-[12px] text-gray-400 py-2">Sin resultados</p>
                                @endforelse
                            </div>
                            <div class="mt-2">{{ $products->links() }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="col">
                                {{-- Texto del anuncio (solo textarea, se autogenera al elegir producto) --}}
                                @if ($generatedContent)
                                    <div
                                        class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                                        <textarea id="ad-textarea" readonly rows="12" onclick="var t=this;t.select();t.setSelectionRange(0,99999);document.execCommand?document.execCommand('copy'):navigator.clipboard?.writeText(t.value);t.style.outline='2px solid #00C4FF';setTimeout(()=>t.style.outline='',1000)"
                                            class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs text-gray-700 dark:text-gray-300 font-mono resize-none whitespace-pre-wrap">{{ $generatedContent['headline'] }}
                                            {{ $generatedContent['body'] }}
                                            {{ $generatedContent['cta'] ? $generatedContent['cta'] : '' }}
                                        </textarea>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <div
                                    class="bg-white dark:bg-[#1c1c1e] rounded-xl border border-gray-200 dark:border-white/5 p-3 space-y-2 max-h-[calc(100vh-280px)] overflow-y-auto text-gray-800 dark:text-gray-200">
                                    <h2
                                        class="font-bold text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                                        <span
                                            class="w-3.5 h-3.5 rounded-full bg-[#d4af37] text-[7px] flex items-center justify-center font-black text-white">1</span>
                                        Imagen del anuncio
                                    </h2>
                                    <p class="text-[9px] text-gray-400 -mt-1">Los datos se cargan automáticamente del
                                        producto
                                    </p>

                                    {{-- Inputs ocultos para que el JS del canvas los lea --}}
                                    <input type="hidden" id="imgTitle"
                                        value="{{ $this->imageTemplateData['title'] }}" />
                                    <input type="hidden" id="imgModelCode"
                                        value="{{ $this->imageTemplateData['modelCode'] }}" />
                                    <input type="hidden" id="imgPrice"
                                        value="{{ $this->imageTemplateData['price'] }}" />
                                    <input type="hidden" id="imgShipping" value="ENVÍO GRATIS" />
                                    <textarea id="imgSpecs" class="sr-only">{{ $this->imageTemplateData['specs'] }}</textarea>
                                    <input type="hidden" id="imgWhatsapp" value="8671-1422" />
                                    <input type="hidden" id="imgWebsite" value="INVICTACR.COM" />

                                    <div x-data="{
                                        currentTheme: 'gold',
                                        themes: {
                                            gold: { name: 'Gold', dark: '#8a5a00', light: '#e6b800', cream: '#fdf6e3' },
                                            blue: { name: 'Blue', dark: '#0b2447', light: '#1a5fb4', cream: '#eaf2fb' },
                                            dark: { name: 'Dark', dark: '#141414', light: '#3a3a3a', cream: '#eceff1' },
                                            green: { name: 'Green', dark: '#0e3d24', light: '#1f7a4d', cream: '#eef7f0' },
                                            red: { name: 'Red', dark: '#5a0a0a', light: '#c0212b', cream: '#fbeef0' },
                                            purple: { name: 'Purple', dark: '#2e1065', light: '#6d28d9', cream: '#f3eefb' },
                                            teal: { name: 'Teal', dark: '#0c4a4a', light: '#0d9488', cream: '#eef9f8' },
                                        }
                                    }">
                                        <label
                                            class="block text-[9px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Color</label>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            <template x-for="entry in Object.entries(themes)" :key="entry[0]">
                                                <button type="button"
                                                    @click="window.dispatchEvent(new CustomEvent('set-theme', { detail: entry[0] })); currentTheme = entry[0]"
                                                    :class="currentTheme === entry[0] ?
                                                        'ring-2 ring-white ring-offset-1 ring-offset-[#1c1c1e]' : ''"
                                                    class="w-7 h-7 rounded-full border-2 border-transparent transition-all"
                                                    :style="`background:linear-gradient(135deg, ${entry[1].dark}, ${entry[1].light})`"
                                                    :title="entry[1].name"></button>
                                            </template>
                                        </div>
                                    </div>

                                    <div><label
                                            class="block text-[9px] uppercase text-gray-500 dark:text-gray-400">Foto</label>
                                        <input type="file" id="imgUpload" accept="image/*"
                                            data-product-image="{{ $this->imageTemplateData['image'] }}"
                                            class="w-full text-[9px] text-gray-500 file:mr-1 file:px-1.5 file:py-0.5 file:rounded file:border-0 file:bg-gray-200 dark:file:bg-white/10 file:text-gray-700 dark:file:text-gray-300 file:text-[10px]" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-1.5">
                                        <!-- escala default: value="1"; cambiá ese número para el zoom inicial de la foto -->
                                        <div><label
                                                class="block text-[9px] uppercase text-gray-500 dark:text-gray-400">Escala</label><input
                                                type="range" id="imgScale" min="0.5" max="2"
                                                step="0.01" value="0.8" class="w-full accent-[#00C4FF] h-4" />
                                        </div>
                                        <div><label
                                                class="block text-[9px] uppercase text-gray-500 dark:text-gray-400">Vertical</label><input
                                                type="range" id="imgOffsetY" min="-200" max="200"
                                                step="1" value="0" class="w-full accent-[#00C4FF] h-4" />
                                        </div>
                                    </div>
                                    <button type="button" id="imgDownloadBtn"
                                        wire:click="saveDownload"
                                        class="w-full px-2 py-1.5 rounded-lg bg-[#d4af37] hover:brightness-110 text-[#1c1c1e] font-bold text-[11px] transition-all flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-download"></i> Descargar PNG
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- 4. IA: variantes de copy --}}
                        <div x-data="{ open: true }"
                            class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                            <button @click="open = !open" class="flex items-center gap-2 w-full text-left">
                                <span
                                    class="w-5 h-5 rounded-full bg-gradient-to-r from-[#00C4FF] to-purple-500 text-[9px] flex items-center justify-center font-black text-white">AI</span>
                                <span
                                    class="font-bold text-[11px] uppercase tracking-wider text-gray-900 dark:text-white flex-1">IA
                                    · Variantes de texto</span>
                                <i class="fa-solid text-[10px] text-gray-400"
                                    :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                            <div x-show="open" x-collapse class="mt-3 space-y-3">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @foreach ($this->aiToneOptions as $key => $info)
                                        <button wire:click="$set('aiTone', '{{ $key }}')"
                                            class="px-2 py-1 rounded-md text-[10px] font-bold transition-all border {{ $aiTone === $key ? 'border-[#00C4FF] bg-[#00C4FF]/10 text-[#00C4FF]' : 'border-gray-200 dark:border-white/5 text-gray-500 hover:border-gray-300 dark:hover:border-white/20' }}">
                                            <i class="fa-regular {{ $info[0] }}"></i> {{ $info[1] }}
                                        </button>
                                    @endforeach
                                    <button wire:click="generateWithAI" wire:loading.attr="disabled"
                                        class="bg-gradient-to-r from-[#00C4FF] to-purple-500 hover:from-[#00b0e6] hover:to-purple-600 text-white font-bold px-2.5 py-1 rounded-md text-[10px] transition-all flex items-center gap-1 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="generateWithAI"><i
                                                class="fa-solid fa-wand-magic-sparkles"></i> Generar</span>
                                        <span wire:loading wire:target="generateWithAI"><i
                                                class="fa-solid fa-spinner fa-spin"></i></span>
                                    </button>
                                </div>

                                @if ($aiLoading)
                                    <div class="flex items-center justify-center py-6">
                                        <i class="fa-solid fa-spinner fa-spin text-[#00C4FF] mr-2"></i>
                                        <span class="text-xs text-gray-500">Generando variantes...</span>
                                    </div>
                                @elseif($aiGenerated)
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        @foreach ($aiGenerated['variants'] as $index => $variant)
                                            @php
                                                $parts = preg_split('/\n(?=Cuerpo:|Hashtags:)/', $variant);
                                            @endphp
                                            <div
                                                class="p-2 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5 flex flex-col">
                                                <div class="flex-1 space-y-1">
                                                    <span
                                                        class="w-4 h-4 rounded-full bg-[#00C4FF] inline-flex items-center justify-center text-[8px] font-black text-[#0a0f1c]">#{{ $index + 1 }}</span>
                                                    @foreach ($parts as $part)
                                                        @php $colon = mb_strpos($part, ':' ); @endphp
                                                        @if ($colon !== false)
                                                            <p
                                                                class="text-[10px] text-gray-700 dark:text-gray-300 leading-relaxed">
                                                                <span
                                                                    class="font-bold text-[#00C4FF]">{{ mb_substr($part, 0, $colon) }}:</span>{{ mb_substr($part, $colon + 1) }}
                                                            </p>
                                                        @else
                                                            <p
                                                                class="text-[10px] text-gray-700 dark:text-gray-300 leading-relaxed">
                                                                {{ $part }}</p>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <button wire:click="useAiVariant({{ $index }})"
                                                    class="mt-1.5 w-full bg-[#00C4FF]/10 hover:bg-[#00C4FF]/20 text-[#00C4FF] font-bold px-2 py-1 rounded-lg text-[9px] transition-all flex items-center justify-center gap-1">
                                                    <i class="fa-solid fa-arrow-right"></i> Usar
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[10px] text-gray-400">Seleccioná producto y tono, luego generá</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Columna 3: Canvas preview --}}
                    <div
                        class="flex items-start justify-center p-2 bg-[#050505] rounded-xl border border-gray-200 dark:border-white/5">
                        <canvas id="adCanvas" width="1080" height="1350"
                            style="max-width:100%;max-height:500px;width:auto;box-shadow:0 10px 40px rgba(0,0,0,.6);border-radius:4px;"></canvas>
                    </div>
                </div>
            </div>
    </div>

    {{-- Downloads table --}}
    @if ($downloads && $downloads->count() > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3 mt-3">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-bold text-[11px] uppercase tracking-wider text-gray-900 dark:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-clock-rotate-left text-[#00C4FF] text-[10px]"></i> Descargados
            </h2>
            <button wire:click="resetDownloads" wire:confirm="¿Limpiar todo el historial?"
                class="px-2 py-0.5 rounded text-[9px] font-bold bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/30 transition-all">
                <i class="fa-solid fa-trash-can"></i> Reset
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[10px] text-left">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-500 border-b border-gray-200 dark:border-white/10">
                        <th class="py-1 pr-2 font-medium">Modelo</th>
                        <th class="py-1 px-2 font-medium">Foto</th>
                        <th class="py-1 px-2 font-medium">Texto</th>
                        <th class="py-1 pl-2 font-medium">Descargado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @php
                        $grouped = $downloads->groupBy(fn($d) => $d->created_at->format('Y-m-d'));
                    @endphp
                    @foreach ($grouped as $date => $items)
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <td colspan="4" class="py-1.5 px-2 font-bold text-[10px] uppercase tracking-wider text-[#00C4FF]">
                                <i class="fa-regular fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d/m/Y') }}
                                <span class="text-gray-400 dark:text-gray-500 font-normal normal-case">({{ $items->count() }})</span>
                            </td>
                        </tr>
                        @foreach ($items as $d)
                        <tr class="text-gray-600 dark:text-gray-400">
                            <td class="py-1 pr-2 font-bold text-gray-800 dark:text-gray-200">{{ $d->model_code }}</td>
                            <td class="py-1 px-2">
                                @if ($d->product_image)
                                <img src="{{ $d->product_image }}" class="w-8 h-8 object-contain rounded" />
                                @endif
                            </td>
                            <td class="py-1 px-2 max-w-[200px] truncate" title="{{ $d->text_content }}">{{ $d->text_content }}</td>
                            <td class="py-1 pl-2 whitespace-nowrap">{{ $d->created_at->format('H:i') }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- TAB: UTM --}}
    @elseif($activeTab === 'utm')
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2 space-y-3">
                <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                    <div class="space-y-3">
                        <input type="url" wire:model="utmUrl" placeholder="https://invictacr.com/producto/..."
                            class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                        <div class="grid grid-cols-2 gap-2">
                            <select wire:model="utmSource"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs">
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="google">Google</option>
                                <option value="email">Email</option>
                                <option value="tiktok">TikTok</option>
                            </select>
                            <select wire:model="utmMedium"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs">
                                <option value="post">Post</option>
                                <option value="story">Historia</option>
                                <option value="ad">Anuncio</option>
                                <option value="bio">Bio</option>
                                <option value="newsletter">Newsletter</option>
                            </select>
                            <input type="text" wire:model="utmCampaign" placeholder="Campaña"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                            <input type="text" wire:model="utmTerm" placeholder="Término"
                                class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                        </div>
                        <input type="text" wire:model="utmContent" placeholder="Contenido (opcional)"
                            class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                        <button wire:click="generateUtm"
                            class="w-full bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-bold px-3 py-2 rounded-lg text-xs transition-all flex items-center justify-center gap-1">
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
            let currentTheme = 'gold';
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

                // ===== BADGE DE PRECIO (modificar tamaño y posición) =====
                const badgePadX = 40; // padding desde el borde izquierdo
                const badgeY = H - 240; // posición Y del badge
                const badgeW = 520; // ancho del badge
                const badgeH = 160; // alto del badge
                const badgeRadius = 80; // bordes redondeados
                ctx.save();
                ctx.shadowColor = 'rgba(0,0,0,.25)';
                ctx.shadowBlur = 20;
                ctx.shadowOffsetY = 8;
                ctx.fillStyle = t.badge;
                roundRect(badgePadX, badgeY, badgeW, badgeH, badgeRadius);
                ctx.fill();
                ctx.restore();

                // etiqueta amarilla de envío (sobre el badge)
                const tagH = 48;
                const tagRadius = 24;
                const tagW = 320;
                ctx.save();
                ctx.shadowColor = 'rgba(0,0,0,.2)';
                ctx.shadowBlur = 12;
                ctx.shadowOffsetY = 4;
                ctx.fillStyle = '#f2c400';
                roundRect(badgePadX + 30, badgeY - tagH / 2, tagW, tagH, tagRadius);
                ctx.fill();
                ctx.font = 'bold 22px Arial';
                ctx.fillStyle = '#1c1c1c';
                ctx.textBaseline = 'middle';
                ctx.textAlign = 'center';
                ctx.fillText(document.getElementById('imgShipping').value, badgePadX + 30 + tagW / 2, badgeY);
                ctx.restore();

                // precio centrado vertical y horizontalmente dentro del badge
                ctx.save();
                const priceText = document.getElementById('imgPrice').value;
                const priceFontSize = 68;
                ctx.font = `italic bold ${priceFontSize}px Arial`;
                ctx.fillStyle = '#ffffff';
                ctx.textBaseline = 'middle';
                const priceCenterX = badgePadX + badgeW / 2;
                const priceCenterY = badgeY + badgeH / 2 + 24;
                ctx.textAlign = 'center';
                ctx.shadowColor = 'rgba(0,0,0,.3)';
                ctx.shadowBlur = 4;
                ctx.shadowOffsetY = 2;
                ctx.fillText(priceText, priceCenterX, priceCenterY);
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

    document.addEventListener('trigger-png-download', () => {
        const modelCode = (document.getElementById('imgModelCode').value || 'invicta').replace(/[^a-zA-Z0-9_-]/g, '');
        const link = document.createElement('a');
        link.download = modelCode + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });

            // Vuelve a dibujar si Livewire re-renderiza el componente
            draw();
        };
    </script>
@endpush
