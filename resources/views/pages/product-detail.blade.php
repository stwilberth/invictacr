@php
    $size = $product->size ? preg_replace('/\s*mm$/i', '', $product->size) : '';
    $displayTitle = 'Reloj Invicta ' . ($product->coleccion && strtolower($product->coleccion) !== 'otros' ? $product->coleccion . ' ' : '') . ($product->genero && strtolower($product->genero) !== 'unisex' ? 'para ' . $product->genero . ' ' : '') . '(' . $product->modelo . ') - ' . $size . ' mm';
    $seoTitle = $displayTitle . ' | Comprar en Costa Rica';

    $mediumImage = $product->imagen;
    if ($product->imagen && str_starts_with($product->imagen, '/storage/relojes/')) {
        $basename = basename($product->imagen);
        $medModelo = pathinfo($basename, PATHINFO_FILENAME);
        $medCandidate = public_path("storage/relojes/medium/{$medModelo}.webp");
        if (file_exists($medCandidate)) {
            $mediumImage = "/storage/relojes/medium/{$medModelo}.webp";
        }
    }
@endphp
<x-app-layout :title="$seoTitle" :description="$product->descripcion ?? 'Reloj Invicta ' . $product->modelo" :ogImage="$product->imagen" ogType="product">
    @php
        $isAgotado = ($product->stock ?? 0) <= 0;
        $isUpcoming = $product->proximo;
        $isUpcomingYAgotado = $product->proximo && $isAgotado;
        $priceAfterDiscount = $product->price_after_discount;
        $layawayAmount = $isUpcoming ? 19000 : (ceil(($priceAfterDiscount * 0.2) / 1000) * 1000);
        $whatsappBuy = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Me interesa comprar el reloj Invicta {$product->modelo} ({$product->coleccion}) - ₡" . number_format($priceAfterDiscount, 0) . ". ¿Está disponible? Enlace: " . url()->current());
        $whatsappApartar = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Quiero apartar el reloj Invicta {$product->modelo} con el pago inicial de ₡" . number_format($layawayAmount, 0) . ". ¿Cómo procedo? Enlace: " . url()->current());
        $whatsappVideo = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! ¿Me podrías enviar un video real del Invicta {$product->modelo}? " . url()->current());
        $whatsappInfo = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Quiero más información sobre el reloj Invicta {$product->modelo}: " . url()->current());
        $shareUrl = urlencode(url()->current());
        $shareTitle = urlencode("¡Mira este reloj Invicta!: {$product->title}");
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 lg:py-4">
        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-1.5 text-xs lg:text-sm text-slate-400 dark:text-gray-500 mb-1.5 overflow-x-auto whitespace-nowrap pb-1">
            <a href="/" class="hover:text-[#00C4FF] transition-colors">Inicio</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="/relojes" class="hover:text-[#00C4FF] transition-colors">Relojes</a>
            @if($product->genero && strtolower($product->genero) !== 'unisex')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="/relojes/{{ $product->genero }}" class="hover:text-[#00C4FF] transition-colors capitalize">{{ $product->genero }}</a>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-white/60 dark:text-gray-400 font-medium truncate max-w-[200px]">{{ $product->modelo }}</span>
        </nav>

        {{-- Mobile Header: Title above media --}}
        <div class="lg:hidden">
            <h1 class="text-md leading-snug font-black text-gray-800 dark:text-white tracking-tight mb-2 uppercase">
                {{ $displayTitle }}
            </h1>
            <div class="flex items-center gap-2 flex-wrap">
                @if($isUpcoming)
                <div class="flex items-center gap-2 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider">Próximamente</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Main Product Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 items-start gap-3 lg:gap-8">
            {{-- Left Column: Media --}}
            <div class="lg:col-span-6">
                {{-- Desktop: Sticky media wrapper --}}
                <div class="hidden lg:block lg:sticky lg:top-0">
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="aspect-square flex items-center justify-center">
                            <img src="{{ $mediumImage }}" alt="{{ $displayTitle }}" class="w-full h-full object-contain" loading="eager" />
                        </div>

                        {{-- Action buttons overlay at the bottom --}}
                        <div class="absolute bottom-6 left-0 right-0 flex items-center justify-center gap-4 z-20">
                            <button type="button" onclick="event.preventDefault(); openImageModal('{{ $product->imagen }}', '{{ $displayTitle }}')" class="flex items-center gap-2.5 px-5 py-2.5 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-full text-sm font-black uppercase tracking-wider transition-all shadow-2xl border-2 border-gray-300 dark:border-gray-600 cursor-pointer">
                                <i class="fa-solid fa-expand text-sm"></i>
                                Ver imagen
                            </button>
                            @if($product->video)
                            <button type="button" onclick="event.preventDefault(); openVimeoModal('{{ $product->video }}')" class="relative flex items-center gap-2.5 px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white rounded-full text-sm font-black uppercase tracking-wider transition-all shadow-2xl border-2 border-red-600 cursor-pointer group">
                                <div class="absolute inset-0 rounded-full bg-red-600 animate-ping-soft opacity-25 group-hover:opacity-40 transition-opacity"></div>
                                <i class="fa-solid fa-play text-sm relative z-10"></i>
                                <span class="relative z-10">Ver video</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Mobile: Side-by-side grid (image col-span-3, buy box col-span-2) --}}
                <div class="lg:hidden grid grid-cols-5 gap-1">
                    {{-- Image --}}
                    <div class="col-span-3">
                        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="flex items-center justify-center" style="min-height: 200px;">
                                <img src="{{ $mediumImage }}" alt="{{ $product->title }}" class="w-full max-h-[50vh] object-contain" id="main-image-mobile" loading="eager" />
                            </div>

                            {{-- Action buttons overlay at the bottom --}}
                            <div class="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-2.5 z-20">
                                <button type="button" onclick="event.preventDefault(); openImageModal('{{ $product->imagen }}', '{{ $product->title }}')" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-full text-xs font-black uppercase tracking-wider transition-all shadow-xl border-2 border-gray-300 dark:border-gray-600 cursor-pointer">
                                    <i class="fa-solid fa-expand text-xs"></i>
                                    Imagen
                                </button>
                                @if($product->video)
                                <button type="button" onclick="event.preventDefault(); openVimeoModal('{{ $product->video }}')" class="relative flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-full text-xs font-black uppercase tracking-wider transition-all shadow-xl border-2 border-red-600 cursor-pointer group">
                                    <div class="absolute inset-0 rounded-full bg-red-600 animate-ping-soft opacity-25 group-hover:opacity-40 transition-opacity"></div>
                                    <i class="fa-solid fa-play text-xs relative z-10"></i>
                                    <span class="relative z-10">Video</span>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Buy box (price, buttons, specs) --}}
                    <div class="col-span-2 flex flex-col items-center justify-center gap-1.5">
                        @if($isUpcoming)
                        <span class="text-2xl font-black text-amber-500 tracking-tighter">Próx.</span>
                        @elseif(!$isAgotado)
                        <span class="text-2xl font-black text-red-600 dark:text-red-400 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                        @endif

                        @if(!$isAgotado && !$isUpcoming)
                        <a href="{{ $whatsappBuy }}" data-conversion="whatsapp-comprar" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-1 py-2 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs min-[360px]:text-[13px] min-[390px]:text-sm transition-all active:scale-95 no-underline shadow-sm">
                            <i class="fa-brands fa-whatsapp text-sm"></i> Comprar
                        </a>
                        <a href="{{ $whatsappApartar }}" data-conversion="whatsapp-apartar" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-1 py-2 bg-blue-300 hover:bg-amber-600 text-white rounded-xl font-extrabold uppercase tracking-tight text-xs min-[360px]:text-[13px] min-[390px]:text-sm transition-all active:scale-95 no-underline shadow-sm">
                            <i class="fa-solid fa-hand-holding-dollar text-sm"></i> Apartar
                        </a>
                        @endif

                        @auth
                            @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.products.edit', $product->id) }}" target="_blank" class="w-full flex items-center justify-center gap-1 py-1 bg-[#00C4FF]/10 border border-[#00C4FF]/30 rounded-lg text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-all text-[10px] font-bold uppercase tracking-wider">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                Editar
                            </a>
                            @endif
                        @endauth

                        {{-- Mobile: Toggle specs button --}}
                        <button type="button" onclick="toggleMobileSpecs()" class="w-full flex items-center justify-center gap-1 py-1.5 text-gray-700 dark:text-gray-300 rounded-xl font-bold uppercase tracking-tight text-[11px] transition-all bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10">
                            <i class="fa-solid fa-list-ul text-[11px]" id="mobile-specs-icon"></i>
                            <span id="mobile-specs-label">Ver detalles</span>
                        </button>

                        {{-- Mobile inline specs card (hidden by default) --}}
                        <div id="mobile-specs-card" class="hidden flex-col gap-0 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 overflow-hidden">
                            <div class="flex justify-between items-center px-2.5 py-1.5 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Para</span>
                                <span class="text-xs lg:text-sm font-semibold text-gray-800 dark:text-white capitalize">{{ $product->genero ?? 'Unisex' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2.5 py-1.5 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Caja</span>
                                <span class="text-xs lg:text-sm font-semibold text-gray-800 dark:text-white">{{ $size ? $size . 'mm' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2.5 py-1.5 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Tipo</span>
                                <span class="text-xs lg:text-sm font-semibold text-gray-800 dark:text-white capitalize">{{ $product->tipo_movimiento === 'cuarzo' ? 'Batería' : ($product->tipo_movimiento ?? 'Especial') }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2.5 py-1.5">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Agua</span>
                                <span class="text-xs lg:text-sm font-semibold text-gray-800 dark:text-white">{{ $product->resistencia_agua ? $product->resistencia_agua . 'm' : 'Resistente' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                 {{-- Mobile: Relojes Similares slider (replaces thumbnail strip, since all watches have a single image) --}}
                 @if($relatedProducts->count() > 0)
                 <div class="lg:hidden mt-3">
                     <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 px-1">Relojes Similares</h3>
                     <div class="flex gap-1.5 overflow-x-auto pb-2 -mx-1 px-1 snap-x snap-mandatory scrollbar-hide">
                        @foreach($relatedProducts as $related)
                        <div class="flex-shrink-0 w-24 snap-start">
                            <x-product-card :product="$related" compact />
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Right Column: Buy Box --}}
            <div class="lg:col-span-6 flex flex-col">
                {{-- Desktop Title Header --}}
                <div class="hidden lg:block mb-1">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl text-left font-black text-gray-800 dark:text-white tracking-tight leading-[1.1] mb-1 uppercase">
                        {{ $displayTitle }}
                    </h1>
                    @auth
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.products.edit', $product->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#00C4FF]/10 border border-[#00C4FF]/30 rounded-lg text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-all text-xs font-bold uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Editar producto
                        </a>
                        @endif
                    @endauth
                    <div class="flex items-center justify-center md:justify-start gap-3">
                        @if($isUpcoming)
                        <div class="flex items-center gap-2 px-4 py-1.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-full w-fit">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            <span class="text-xs font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider">Próximamente</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Agotado State --}}
                @if($isUpcomingYAgotado)
                <div class="bg-gradient-to-br from-red-50 to-amber-50 dark:from-red-900/10 dark:to-amber-900/10 border border-red-100 dark:border-red-900/50 rounded-2xl p-6 text-center mb-4">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-clock text-amber-600 dark:text-amber-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-amber-700 dark:text-amber-400 mb-1 leading-tight">Agotado / Próximo</h3>
                    <p class="text-sm text-amber-600/70 dark:text-amber-300/60 mb-4">Actualmente sin stock, pero pronto estará disponible. ¡Reserva tu unidad!</p>
                </div>
                @elseif($isAgotado)
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/50 rounded-2xl p-6 text-center mb-10">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-circle-xmark text-red-600 dark:text-red-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-red-700 dark:text-red-400 mb-1 leading-tight">Vendido / Agotado</h3>
                    <p class="text-sm text-red-600/70 dark:text-red-300/60 mb-4">Este reloj ya no se encuentra en stock disponible.</p>
                    <a href="{{ $product->coleccion && strtolower($product->coleccion) !== 'otros' ? url('/relojes?coleccion=' . urlencode($product->coleccion)) : url('/relojes') }}" class="inline-flex items-center text-sm font-bold text-red-700 dark:text-red-300 hover:underline gap-2">
                        Ver similares{{ $product->coleccion && strtolower($product->coleccion) !== 'otros' ? ' en ' . $product->coleccion : '' }}
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
                @else
                    {{-- Mobile shipping banner --}}
                    <div class="w-full flex lg:hidden items-center justify-center gap-2 py-2 px-4 mt-2">
                        <i class="fa-solid fa-truck text-[#00C4FF] text-xs"></i>
                        <span class="text-[10px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Envío Gratis y Pago contra entrega*</span>
                    </div>

                    {{-- Desktop Price & Action Buttons --}}
                    <div class="hidden lg:flex flex-col items-start gap-2.5 mb-3.5">
                        <div class="flex flex-col gap-1">
                            @if($isUpcoming)
                            <span class="text-2xl font-black text-amber-500 tracking-tighter uppercase">Próximamente</span>
                            @else
                            <div class="flex items-baseline gap-3">
                                <span class="text-2xl lg:text-3xl font-black text-red-600 dark:text-red-400 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                                @if(($product->descuento ?? 0) > 0)
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400 line-through font-medium">₡{{ number_format($product->precio_venta, 0) }}</span>
                                    <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded">-{{ $product->descuento }}%</span>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        {{-- Shipping info --}}
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 py-1.5 px-2.5 rounded-lg border border-gray-100 dark:border-gray-800">
                            <i class="fa-solid fa-truck text-[#00C4FF] text-xs"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Envío Gratis y Pago contra entrega*</span>
                        </div>

                        {{-- Desktop Action buttons --}}
                        @if(!$isUpcoming)
                        <div class="grid grid-cols-2 gap-2.5 w-full">
                            <a href="{{ $whatsappBuy }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-1 py-2 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                                <i class="fa-brands fa-whatsapp text-base"></i> Comprar
                            </a>
                            <a href="{{ $whatsappApartar }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-1 py-2 bg-blue-300 hover:bg-amber-600 text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                                <i class="fa-solid fa-hand-holding-dollar text-base"></i> Apartar
                            </a>

                        </div>
                        @endif
                    </div>
                @endif

                {{-- Description --}}
                @if($product->descripcion)
                <div class="mt-4">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wider text-sm">Descripción</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">{{ $product->descripcion }}</p>
                </div>
                @endif

                {{-- Tabbed Card: Compartir / Especificaciones --}}
                <div class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm mb-3.5 mt-4">
                    {{-- Tab Buttons --}}
                    <div class="flex border-b border-gray-200 dark:border-gray-600" role="tablist">
                        <button data-tab="share" class="product-tab-btn flex-1 flex items-center justify-center gap-2 py-1.5 px-3 text-xs md:text-sm font-medium transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" type="button">
                            <i class="fa-solid fa-share-nodes text-xs"></i> Compartir
                        </button>
                        <button data-tab="specs" class="product-tab-btn hidden lg:flex flex-1 items-center justify-center gap-2 py-1.5 px-3 text-xs md:text-sm font-medium transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" type="button">
                            <i class="fa-solid fa-list-ul text-xs"></i> Especificaciones
                        </button>
                    </div>

                    {{-- Tab Panel: Compartir --}}
                    <div data-tab-panel="share" class="product-tab-panel hidden" role="tabpanel">
                        <div class="p-2 grid grid-cols-3 sm:grid-cols-6 gap-2">
                            <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#25D366] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
                                <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}&quote={{ $shareTitle }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#1877F2] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
                                <i class="fa-brands fa-facebook text-lg"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-gray-900 text-white text-[11px] font-semibold hover:bg-gray-800 transition-all no-underline">
                                <i class="fa-brands fa-x-twitter text-lg"></i> X
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareTitle }}&media={{ urlencode($product->imagen) }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#E60023] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
                                <i class="fa-brands fa-pinterest text-lg"></i> Pinterest
                            </a>
                            <button type="button" onclick="copyProductUrl()" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-gray-500 text-white text-[11px] font-semibold hover:bg-gray-600 transition-all">
                                <i class="fa-solid fa-link text-lg"></i> Copiar
                            </button>
                            <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#0088cc] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
                                <i class="fa-brands fa-telegram text-lg"></i> Telegram
                            </a>
                        </div>
                    </div>

                    {{-- Tab Panel: Especificaciones --}}
                    <div data-tab-panel="specs" class="product-tab-panel hidden" role="tabpanel">
                        <div class="grid grid-cols-2">
                            <div class="p-2.5 border-b border-r border-gray-100 dark:border-gray-700 flex items-start gap-2">
                                <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-venus-mars text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Para</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white capitalize">{{ $product->genero ?? 'Unisex' }}</p>
                                </div>
                            </div>
                            <div class="p-2.5 border-b border-gray-100 dark:border-gray-700 flex items-start gap-2">
                                <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-arrows-up-down-left-right text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Caja</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $size ? $size . 'mm' : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="p-2.5 border-r border-gray-100 dark:border-gray-700 flex items-start gap-2">
                                <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-gear text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Movimiento</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white capitalize line-clamp-1">{{ $product->tipo_movimiento === 'cuarzo' ? 'Batería' : ($product->tipo_movimiento ?? 'Especial') }}</p>
                                </div>
                            </div>
                            <div class="p-2.5 flex items-start gap-2">
                                <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-droplet text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Agua</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white whitespace-nowrap">{{ $product->resistencia_agua ? $product->resistencia_agua . 'm' : 'Resistente' }}</p>
                                </div>
                            </div>
                            <div class="p-2.5 border-r border-gray-100 dark:border-gray-700 flex items-start gap-2">
                                <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-layer-group text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Colección</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $product->coleccion ?? '—' }}</p>
                                </div>
                            </div>
                            <div class="p-2.5 flex items-start gap-2">
                                <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-clock text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Brazalete</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white capitalize">{{ $product->brazalete ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Desktop: Related products slider with nav buttons --}}
                @if($relatedProducts->count() > 0)
                <div class="hidden lg:block mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Productos Relacionados</h2>
                        <div class="flex gap-1.5">
                            <button type="button" onclick="scrollRelated(-1)" aria-label="Anterior" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>
                            <button type="button" onclick="scrollRelated(1)" aria-label="Siguiente" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div id="related-slider" class="flex gap-3 overflow-x-auto pb-1 snap-x snap-mandatory scrollbar-hide" style="scroll-behavior: smooth;">
                        @foreach($relatedProducts as $related)
                        <div class="flex-shrink-0 w-40 snap-start">
                            <x-product-card :product="$related" />
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Próximamente Reservation Card --}}
                @if($isUpcoming)
                <div class="mb-8 p-5 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 rounded-2xl border border-amber-200 dark:border-amber-800/30">
                    <h3 class="text-sm font-black text-amber-800 dark:text-amber-300 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-star text-amber-500 animate-pulse"></i> Reserva tu unidad:
                    </h3>
                    <ul class="space-y-4 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-check text-amber-500 mt-1"></i>
                            <span><strong>Prioridad:</strong> Te avisaremos apenas el reloj llegue a bodega antes de publicarlo en redes.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-check text-amber-500 mt-1"></i>
                            <span><strong>Congela el precio:</strong> Apártalo con solo <strong>₡{{ number_format(19000, 0) }}</strong> y asegura el precio de lanzamiento.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-check text-amber-500 mt-1"></i>
                            <span><strong>Sin compromiso:</strong> Consulta la fecha estimada y detalles técnicos por WhatsApp.</span>
                        </li>
                    </ul>
                    <a href="{{ $whatsappInfo }}" target="_blank" rel="noopener noreferrer" class="mt-4 flex items-center justify-center gap-2 w-full bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-wider px-6 py-3 rounded-xl transition-all duration-300 active:scale-95 shadow-lg">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span>Consultar por WhatsApp</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Floating WhatsApp buttons
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
        <a href="{{ $whatsappBuy }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 active:scale-95">
            <i class="fab fa-whatsapp text-lg"></i>
            <span class="hidden sm:inline">Comprar</span>
        </a>
    </div> --}}

    <script>
        var pixelModel = "{{ $product->modelo }}";
        var pixelTitle = "{{ $product->title }}";
        var pixelPrice = {{ $product->precio_venta }};
    </script>

    @push('scripts')
    <script>
        // Related products slider navigation
        function scrollRelated(dir) {
            var slider = document.getElementById("related-slider");
            if (!slider) return;
            var card = slider.querySelector(".flex-shrink-0");
            var step = card ? card.offsetWidth + 12 : 176;
            slider.scrollBy({ left: step * dir, behavior: "smooth" });
        }

        // Toggle mobile inline specs card
        function toggleMobileSpecs() {
            var card = document.getElementById("mobile-specs-card");
            var label = document.getElementById("mobile-specs-label");
            var icon = document.getElementById("mobile-specs-icon");
            if (!card) return;
            var isHidden = card.classList.contains("hidden");
            if (isHidden) {
                card.classList.remove("hidden");
                card.classList.add("flex");
                if (label) label.textContent = "Ocultar detalles";
                if (icon) icon.className = "fa-solid fa-chevron-up text-[11px]";
            } else {
                card.classList.add("hidden");
                card.classList.remove("flex");
                if (label) label.textContent = "Ver detalles";
                if (icon) icon.className = "fa-solid fa-list-ul text-[11px]";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Product tabs (Compartir / Especificaciones)
            function initProductTabs() {
                const tabs = document.querySelectorAll(".product-tab-btn");
                const panels = document.querySelectorAll(".product-tab-panel");
                if (!tabs.length) return;

                function setActive(name) {
                    tabs.forEach(function(btn) {
                        const isActive = btn.dataset.tab === name;
                        btn.classList.toggle("is-active", isActive);
                        btn.classList.toggle("border-[#00C4FF]", isActive);
                        btn.classList.toggle("text-[#00C4FF]", isActive);
                        btn.classList.toggle("font-bold", isActive);
                        btn.classList.toggle("border-transparent", !isActive);
                        btn.classList.toggle("text-gray-500", !isActive);
                        btn.classList.toggle("font-medium", !isActive);
                    });
                    panels.forEach(function(panel) {
                        panel.classList.toggle("hidden", panel.dataset.tabPanel !== name);
                    });
                }

                tabs.forEach(function(btn) {
                    btn.addEventListener("click", function() {
                        if (!btn.dataset.tab) return;
                        // Toggle: if already active, collapse; otherwise activate
                        var isActive = btn.classList.contains("is-active");
                        if (isActive) {
                            // Collapse all
                            tabs.forEach(function(b) {
                                b.classList.remove("is-active", "border-[#00C4FF]", "text-[#00C4FF]", "font-bold");
                                b.classList.add("border-transparent", "text-gray-500", "font-medium");
                            });
                            panels.forEach(function(panel) {
                                panel.classList.add("hidden");
                            });
                        } else {
                            setActive(btn.dataset.tab);
                        }
                    });
                });

                // Both panels stay collapsed by default until user clicks a tab
            }

            initProductTabs();
        });

        // Copy URL functionality
        function copyProductUrl() {
            const url = window.location.href;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    const btn = event.currentTarget;
                    const icon = btn.querySelector("i");
                    if (icon) {
                        const originalClass = icon.className;
                        icon.className = "fa-solid fa-check text-green-300";
                        setTimeout(function() {
                            icon.className = originalClass;
                        }, 2000);
                    }
                });
            } else {
                const ta = document.createElement("textarea");
                ta.value = url;
                ta.style.position = "fixed";
                ta.style.opacity = "0";
                document.body.appendChild(ta);
                ta.select();
                document.execCommand("copy");
                document.body.removeChild(ta);
                const btn = event.currentTarget;
                const icon = btn.querySelector("i");
                if (icon) {
                    icon.className = "fa-solid fa-check text-green-300";
                    setTimeout(function() {
                        icon.className = "fa-solid fa-link text-lg";
                    }, 2000);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
