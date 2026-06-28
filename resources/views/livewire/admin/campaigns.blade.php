<div>
    <div class="flex gap-2 mb-6 flex-wrap">
        <button wire:click="$set('activeTab', 'generator')"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
            {{ $activeTab === 'generator' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Generar
        </button>
        <button wire:click="$set('activeTab', 'ai')"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
            {{ $activeTab === 'ai' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5 hover:border-[#00C4FF]/50' }}">
            <i class="fa-solid fa-brain"></i> IA
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

    {{-- TAB: GENERADOR --}}
    @if($activeTab === 'generator')
    <div class="space-y-3">
        <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
            <div class="flex items-start gap-4">
                <div class="flex gap-2 overflow-x-auto flex-1 min-w-0 pb-1">
                    @foreach($products as $p)
                    <button wire:click="$set('selectedProductId', {{ $p->id }})"
                        class="flex-shrink-0 p-1 rounded-lg border-2 transition-all {{ $selectedProductId == $p->id ? 'border-[#00C4FF]' : 'border-transparent hover:border-gray-200 dark:hover:border-white/20' }}" style="width:100px">
                        @if($p->imagen)
                        <img src="{{ $p->imagen }}" class="w-full aspect-square object-contain rounded" loading="lazy" />
                        @endif
                        <p class="text-[8px] font-medium text-gray-700 dark:text-gray-300 truncate mt-0.5">{{ $p->modelo }}</p>
                    </button>
                    @endforeach
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0 pt-0.5">
                    @php $templates = [
                        'instagram' => ['fa-brands fa-instagram', 'IG'],
                        'facebook' => ['fa-brands fa-facebook', 'FB'],
                        'whatsapp' => ['fa-brands fa-whatsapp', 'WA'],
                        'story' => ['fa-solid fa-clapperboard', 'Story'],
                    ] @endphp
                    @foreach($templates as $key => $icon)
                    <button wire:click="$set('templateType', '{{ $key }}')"
                        class="px-2 py-1.5 rounded-md text-xs font-bold transition-all border {{ $templateType === $key ? 'border-[#00C4FF] bg-[#00C4FF]/10 text-[#00C4FF]' : 'border-gray-200 dark:border-white/5 text-gray-500 hover:border-gray-300 dark:hover:border-white/20' }}">
                        <i class="{{ $icon[0] }}"></i>
                    </button>
                    @endforeach
                    <button wire:click="generateAd" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-bold px-3 py-1.5 rounded-md text-xs transition-all flex items-center gap-1">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Generar
                    </button>
                    @if($generatedContent)
                    <button wire:click="saveAd" class="bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 hover:border-[#00C4FF]/50 text-gray-500 dark:text-gray-400 font-bold px-2.5 py-1.5 rounded-md text-xs transition-all">
                        <i class="fa-solid fa-floppy-disk"></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-3">
            @if($generatedContent)
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="font-bold text-gray-900 dark:text-white text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-eye text-[#00C4FF] text-[10px]"></i> Vista previa
                    </h2>
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 capitalize">{{ $generatedContent['template'] }}</span>
                </div>

                {{-- Instagram Preview --}}
                @if($generatedContent['template'] === 'instagram')
                <div class="max-w-sm mx-auto bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100">
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center text-white text-xs font-black">I</div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900">invictacostarica</p>
                            <p class="text-xs text-gray-500">{{ $product?->coleccion ?? 'Invicta' }}</p>
                        </div>
                        <i class="fa-solid fa-ellipsis-vertical text-gray-600"></i>
                    </div>
                    <div class="bg-gray-100 aspect-square flex items-center justify-center p-4">
                        @if($generatedContent['image'])
                        <img src="{{ $generatedContent['image'] }}" class="w-full h-full object-contain" />
                        @else
                        <i class="fa-solid fa-image text-4xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="px-4 py-3 space-y-2">
                        <div class="flex gap-3 text-xl">
                            <i class="fa-regular fa-heart hover:text-red-500 cursor-pointer"></i>
                            <i class="fa-regular fa-comment cursor-pointer"></i>
                            <i class="fa-regular fa-paper-plane cursor-pointer"></i>
                            <i class="fa-regular fa-bookmark ml-auto cursor-pointer"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-900">{{ number_format(rand(100, 999)) }} Me gusta</p>
                        <div class="text-sm text-gray-900">
                            <span class="font-bold">invictacostarica</span>
                            <span class="whitespace-pre-line">{{ $generatedContent['body'] }}</span>
                        </div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Ver los comentarios...</p>
                    </div>
                </div>

                {{-- Facebook Preview --}}
                @elseif($generatedContent['template'] === 'facebook')
                <div class="max-w-sm mx-auto bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100">
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-black">I</div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900">Invicta Costa Rica</p>
                            <p class="text-xs text-gray-500">{{ now()->format('F j, Y') }} · <i class="fa-solid fa-globe text-xs"></i></p>
                        </div>
                    </div>
                    <div class="px-4 pb-3">
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $generatedContent['body'] }}</p>
                    </div>
                    <div class="bg-gray-100 aspect-video flex items-center justify-center p-4">
                        @if($generatedContent['image'])
                        <img src="{{ $generatedContent['image'] }}" class="w-full h-full object-contain" />
                        @else
                        <i class="fa-solid fa-image text-4xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100 flex gap-4 text-sm text-gray-500">
                        <span><i class="fa-regular fa-thumbs-up"></i> Me gusta</span>
                        <span><i class="fa-regular fa-comment"></i> Comentar</span>
                        <span><i class="fa-regular fa-share-from-square"></i> Compartir</span>
                    </div>
                </div>

                {{-- WhatsApp Preview --}}
                @elseif($generatedContent['template'] === 'whatsapp')
                <div class="max-w-sm mx-auto bg-[#e5ddd5] dark:bg-[#1f2c33] rounded-2xl overflow-hidden shadow-lg p-4 space-y-3">
                    <div class="flex items-center gap-3 border-b border-gray-300 dark:border-white/10 pb-3">
                        <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white text-sm font-black">I</div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Invicta Costa Rica</p>
                            <p class="text-xs text-gray-500">en línea</p>
                        </div>
                    </div>
                    <div class="flex justify-start">
                        <div class="bg-white dark:bg-[#0a2418] rounded-2xl rounded-bl-sm px-4 py-3 max-w-[85%] shadow-sm">
                            <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $generatedContent['body'] }}</p>
                            <p class="text-xs text-gray-400 text-right mt-1">{{ now()->format('h:i A') }}</p>
                        </div>
                    </div>
                    @if($generatedContent['image'])
                    <div class="flex justify-start">
                        <div class="bg-white dark:bg-[#0a2418] rounded-2xl rounded-bl-sm p-2 max-w-[70%] shadow-sm">
                            <img src="{{ $generatedContent['image'] }}" class="w-full rounded-lg" />
                            <p class="text-xs text-gray-400 text-right mt-1">{{ now()->format('h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 text-gray-400 text-sm">
                        <i class="fa-solid fa-circle text-[6px] text-green-500"></i>
                        <span>Escribe un mensaje...</span>
                        <i class="fa-solid fa-paper-plane ml-auto text-green-500"></i>
                    </div>
                </div>

                {{-- Story Preview --}}
                @elseif($generatedContent['template'] === 'story')
                <div class="max-w-xs mx-auto aspect-[9/16] bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-2xl overflow-hidden shadow-lg relative flex flex-col items-center justify-center p-6">
                    @if($generatedContent['image'])
                    <img src="{{ $generatedContent['image'] }}" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40"></div>
                    <div class="relative z-10 text-center space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-full border-2 border-[#00C4FF] overflow-hidden bg-gray-700 flex items-center justify-center">
                            @if($generatedContent['image'])
                            <img src="{{ $generatedContent['image'] }}" class="w-full h-full object-cover" />
                            @else
                            <i class="fa-solid fa-crown text-2xl text-[#00C4FF]"></i>
                            @endif
                        </div>
                        <p class="text-white font-black text-2xl leading-tight">{{ $generatedContent['headline'] }}</p>
                        <p class="text-white/80 text-sm whitespace-pre-line">{{ $generatedContent['body'] }}</p>
                        @if($generatedContent['cta'])
                        <div class="inline-block bg-[#00C4FF] text-[#0a0f1c] font-black px-6 py-2.5 rounded-full text-sm uppercase tracking-wider">
                            {{ $generatedContent['cta'] }}
                        </div>
                        @endif
                    </div>
                    <div class="absolute top-4 left-4 right-4 flex gap-1.5 z-10">
                        @foreach(range(1,5) as $i)
                        <div class="flex-1 h-0.5 rounded-full {{ $i === 1 ? 'bg-white' : 'bg-white/30' }}"></div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                <h2 class="font-bold text-gray-900 dark:text-white mb-1.5 text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-copy text-[#00C4FF] text-[10px]"></i> Texto
                </h2>
                <div x-data="{ copy: false }">
                    <textarea readonly rows="3" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-[10px] text-gray-700 dark:text-gray-300 font-mono resize-none"
                    >{{ $generatedContent['headline'] }}

{{ $generatedContent['body'] }}
@if($generatedContent['cta'])
{{ $generatedContent['cta'] }}
@endif</textarea>
                    <button @click="navigator.clipboard.writeText(document.querySelector('#ad-textarea').value); copy = true; setTimeout(() => copy = false, 2000)"
                        class="mt-1.5 px-2.5 py-1 rounded-lg text-[9px] font-bold transition-all flex items-center gap-1"
                        :class="copy ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10'">
                        <i class="fa-solid text-[9px]" :class="copy ? 'fa-check' : 'fa-copy'"></i>
                        <span x-text="copy ? 'Copiado' : 'Copiar'"></span>
                    </button>
                    <textarea id="ad-textarea" class="sr-only">{{ $generatedContent['headline'] }}

{{ $generatedContent['body'] }}
@if($generatedContent['cta'])
{{ $generatedContent['cta'] }}
@endif</textarea>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3 flex flex-col items-center justify-center text-center min-h-[200px]">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-sm text-gray-300 dark:text-gray-600"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold">Seleccioná un producto</p>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">La vista previa aparecerá acá</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TAB: IA --}}
    @elseif($activeTab === 'ai')
    <div class="space-y-3">
        <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
            <div class="flex items-center gap-3">
                <div class="flex gap-2 overflow-x-auto flex-1 min-w-0 pb-0.5">
                    @foreach($products as $p)
                    <button wire:click="$set('selectedProductId', {{ $p->id }})"
                        class="flex-shrink-0 p-1 rounded-lg border-2 transition-all {{ $selectedProductId == $p->id ? 'border-[#00C4FF]' : 'border-transparent hover:border-gray-200 dark:hover:border-white/20' }}" style="width:100px">
                        @if($p->imagen)
                        <img src="{{ $p->imagen }}" class="w-full aspect-square object-contain rounded" loading="lazy" />
                        @endif
                        <p class="text-[8px] font-medium text-gray-700 dark:text-gray-300 truncate mt-0.5">{{ $p->modelo }}</p>
                    </button>
                    @endforeach
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    @php $tones = [
                        'casual' => ['fa-face-smile', 'Casual'],
                        'profesional' => ['fa-briefcase', 'Pro'],
                        'urgente' => ['fa-bolt', 'Urgente'],
                        'lujoso' => ['fa-crown', 'Lujo'],
                    ] @endphp
                    @foreach($tones as $key => $info)
                    <button wire:click="$set('aiTone', '{{ $key }}')"
                        class="px-2 py-1.5 rounded-md text-xs font-bold transition-all border {{ $aiTone === $key ? 'border-[#00C4FF] bg-[#00C4FF]/10 text-[#00C4FF]' : 'border-gray-200 dark:border-white/5 text-gray-500 hover:border-gray-300 dark:hover:border-white/20' }}">
                        <i class="fa-regular {{ $info[0] }}"></i>
                        <span class="ml-0.5 hidden sm:inline">{{ $info[1] }}</span>
                    </button>
                    @endforeach
                    <button wire:click="generateWithAI" wire:loading.attr="disabled"
                        class="bg-gradient-to-r from-[#00C4FF] to-purple-500 hover:from-[#00b0e6] hover:to-purple-600 text-white font-bold px-3 py-1.5 rounded-md text-xs transition-all flex items-center gap-1 disabled:opacity-50">
                        <span wire:loading.remove wire:target="generateWithAI"><i class="fa-solid fa-wand-magic-sparkles"></i> IA</span>
                        <span wire:loading wire:target="generateWithAI"><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            @if($aiLoading)
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-6 flex flex-col items-center justify-center min-h-[200px]">
                <div class="w-12 h-12 rounded-full bg-[#00C4FF]/10 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-spinner fa-spin text-xl text-[#00C4FF]"></i>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">Generando variantes...</p>
            </div>
            @elseif($aiGenerated)
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-4">
                <h2 class="font-bold text-gray-900 dark:text-white text-xs uppercase tracking-wider mb-3">Variantes generadas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($aiGenerated['variants'] as $index => $variant)
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5 flex flex-col">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-5 h-5 rounded-full bg-[#00C4FF] flex items-center justify-center text-[9px] font-black text-[#0a0f1c]">#{{ $index + 1 }}</span>
                            </div>
                            <p class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $variant }}</p>
                        </div>
                        <button wire:click="useAiVariant({{ $index }})"
                            class="mt-2 w-full bg-[#00C4FF]/10 hover:bg-[#00C4FF]/20 text-[#00C4FF] font-bold px-3 py-1.5 rounded-lg text-[10px] transition-all flex items-center justify-center gap-1">
                            <i class="fa-solid fa-arrow-right"></i> Usar
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-4 flex flex-col items-center justify-center text-center min-h-[150px]">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-2">
                    <i class="fa-solid fa-brain text-sm text-gray-300 dark:text-gray-600"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold">Seleccioná producto y tono</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TAB: UTM --}}
    @elseif($activeTab === 'utm')
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2 space-y-3">
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                <div class="space-y-3">
                    <input type="url" wire:model="utmUrl" placeholder="https://invictacr.com/producto/..."
                        class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                    <div class="grid grid-cols-2 gap-2">
                        <select wire:model="utmSource" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs">
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="google">Google</option>
                            <option value="email">Email</option>
                            <option value="tiktok">TikTok</option>
                        </select>
                        <select wire:model="utmMedium" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs">
                            <option value="post">Post</option>
                            <option value="story">Historia</option>
                            <option value="ad">Anuncio</option>
                            <option value="bio">Bio</option>
                            <option value="newsletter">Newsletter</option>
                        </select>
                        <input type="text" wire:model="utmCampaign" placeholder="Campaña" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                        <input type="text" wire:model="utmTerm" placeholder="Término" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                    </div>
                    <input type="text" wire:model="utmContent" placeholder="Contenido (opcional)" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-lg px-2.5 py-1.5 text-xs focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] outline-none" />
                    <button wire:click="generateUtm" class="w-full bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-bold px-3 py-2 rounded-lg text-xs transition-all flex items-center justify-center gap-1">
                        <i class="fa-solid fa-link"></i> Generar UTM
                    </button>
                </div>
            </div>
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Los UTM permiten rastrear visitantes en Google Analytics. Usalos para medir cada canal.</p>
            </div>
        </div>
        <div class="lg:col-span-3 space-y-3">
            @if($generatedUtm)
            <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-white/5 p-3">
                <div x-data="{ copy: false }">
                    <div class="bg-gray-50 dark:bg-[#0a0f1c] rounded-lg p-3 border border-gray-200 dark:border-white/10">
                        <p class="text-xs text-gray-700 dark:text-gray-300 break-all font-mono">{{ $generatedUtm }}</p>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button @click="navigator.clipboard.writeText(document.querySelector('#utm-url').value); copy = true; setTimeout(() => copy = false, 2000)"
                            class="flex-1 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1"
                            :class="copy ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10'">
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
        <h2 class="font-bold text-gray-900 dark:text-white mb-3 text-xs uppercase tracking-wider">Anuncios guardados</h2>
        @if($savedAds->count() > 0)
        <div class="space-y-2">
            @foreach($savedAds as $ad)
            <div class="p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="font-bold text-gray-900 dark:text-white text-xs">{{ $ad->title }}</p>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase
                                {{ $ad->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                {{ $ad->status }}
                            </span>
                            @if($ad->type)
                            <span class="px-1.5 py-0.5 rounded bg-gray-200 dark:bg-white/10 text-gray-500 dark:text-gray-400 text-[9px] font-mono">{{ str_replace('ad_', '', $ad->type) }}</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 line-clamp-2 whitespace-pre-line">{{ $ad->description }}</p>
                        <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $ad->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex gap-1.5 flex-shrink-0">
                        <textarea class="sr-only" id="saved-ad-{{ $ad->id }}">{{ $ad->description }}</textarea>
                        <button onclick="navigator.clipboard.writeText(document.getElementById('saved-ad-{{ $ad->id }}').value); this.querySelector('i').className = 'fa-solid fa-check'; setTimeout(() => this.querySelector('i').className = 'fa-solid fa-copy', 2000)"
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
            <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-2">
                <i class="fa-solid fa-bookmark text-lg text-gray-300 dark:text-gray-600"></i>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-bold">No hay anuncios guardados</p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Generá un anuncio y guardalo</p>
        </div>
        @endif
    </div>
    @endif
</div>
