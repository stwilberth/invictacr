@php
    $size = $product->size ? preg_replace('/\s*mm$/i', '', $product->size) : '';
    $displayTitle = $product->display_title;
    $seoTitle = $displayTitle . ' | Comprar en Costa Rica';

    $cdnBase = 'https://cdn.invictacostarica.com';
    $ogImage = $product->imagen;
    $rawImagen = $product->getRawOriginal('imagen');
    if ($rawImagen) {
        $imgModelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
        $r2 = \Illuminate\Support\Facades\Storage::disk('r2');
        if ($r2->exists("relojes/large/{$imgModelo}.webp")) {
            $ogImage = "{$cdnBase}/relojes/large/{$imgModelo}.webp";
        } elseif ($r2->exists("relojes/medium/{$imgModelo}.webp")) {
            $ogImage = "{$cdnBase}/relojes/medium/{$imgModelo}.webp";
        }
    }

    $productName = 'Reloj Invicta ' . ($product->coleccion && strtolower($product->coleccion) !== 'otros' ? $product->coleccion . ' ' : '') . ($product->genero && strtolower($product->genero) !== 'unisex' ? 'para ' . $product->genero . ' ' : '') . '(' . $product->modelo . ')';
    $price = $product->price_after_discount ?? $product->precio_venta ?? 0;
    $availability = ($product->stock ?? 0) > 0 && ($product->disponibilidad ?? 'disponible') !== 'agotado' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';

    $vimeoId = null;
    if ($product->video) {
        preg_match('/(\d+)/', basename($product->video), $matches);
        $vimeoId = $matches[1] ?? null;
    }
@endphp
@push('json-ld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Product",
    "name": {!! json_encode($productName) !!},
    "image": {!! json_encode(asset($ogImage)) !!},
    "description": {!! json_encode($product->descripcion ?? 'Reloj Invicta ' . $product->modelo) !!},
    "sku": {!! json_encode($product->modelo) !!},
    "brand": {"@type": "Brand", "name": "Invicta"},
    @if($vimeoId)
    "video": {
        "@type": "VideoObject",
        "name": {!! json_encode($productName) !!},
        "description": {!! json_encode($product->descripcion ?? 'Video del reloj Invicta ' . $product->modelo) !!},
        "thumbnailUrl": {!! json_encode('https://vumbnail.com/' . $vimeoId . '.jpg') !!},
        "contentUrl": {!! json_encode('https://player.vimeo.com/video/' . $vimeoId) !!},
        "embedUrl": {!! json_encode('https://player.vimeo.com/video/' . $vimeoId) !!},
        "uploadDate": {!! json_encode($product->created_at ? $product->created_at->toIso8601String() : date('c')) !!}
    },
    @endif
    "offers": {
        "@type": "Offer",
        "url": {!! json_encode(url()->current()) !!},
        "priceCurrency": "CRC",
        "price": {!! json_encode($price) !!},
        "availability": {!! json_encode($availability) !!}
    }
}
</script>
@endpush
<x-app-layout :title="$seoTitle" :description="$product->descripcion ?? 'Reloj Invicta ' . $product->modelo" :ogImage="asset($ogImage)" ogType="product" :hideWhatsApp="true" :head="'<link rel=&quot;preload&quot; href=&quot;' . $ogImage . '&quot; as=&quot;image&quot; fetchpriority=&quot;high&quot;>'" >
    @php
        $isAgotado = ($product->stock ?? 0) <= 0 || ($product->disponibilidad ?? 'disponible') === 'agotado';
        $isUpcoming = $product->proximo || $product->precio_venta <= 0;
        $isUpcomingYAgotado = $isUpcoming && $isAgotado;
        $priceAfterDiscount = $product->price_after_discount;
        $whatsappBuy = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Me interesa el reloj Invicta {$product->modelo}");
        $shareUrl = urlencode(url()->current());
        $shareTitle = urlencode("¡Mira este reloj Invicta!: {$product->title}");

        $inCart = false;
        if (session()->has('cart_session_id') || auth()->check()) {
            try {
                $inCart = app(\App\Services\CartService::class)->getCart()->items->contains('product_id', $product->id);
            } catch (\Exception $e) {}
        }
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 lg:py-4">
        {{-- Breadcrumbs 
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
            <a href="/relojes?gender={{ $product->genero }}" class="hover:text-[#00C4FF] transition-colors capitalize">{{ $product->genero }}</a>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-white/60 dark:text-gray-400 font-medium truncate max-w-[200px]">{{ $product->modelo }}</span>
        </nav>
        --}}

        {{-- Mobile Header: Title above media --}}
        <div class="lg:hidden">
            <div class="flex items-center gap-2 mb-2">
                <h1 class="text-md leading-snug font-black text-gray-800 dark:text-white tracking-tight uppercase">
                    {{ $displayTitle }}
                </h1>
            </div>
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

        {{-- Mobile: Agotado banner at top --}}
        @if($isAgotado && !$isUpcoming)
        <div class="lg:hidden flex items-center gap-3 mt-2 mb-1 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/50 rounded-xl p-3">
            <div class="flex-shrink-0 w-9 h-9 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-circle-xmark text-red-600 dark:text-red-400 text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-black text-red-700 dark:text-red-400 uppercase leading-tight">Vendido / Agotado</span>
                <span class="text-[11px] text-red-600/70 dark:text-red-300/60 leading-tight">Este reloj ya no se encuentra en stock disponible.</span>
            </div>
            <a href="{{ $product->coleccion && strtolower($product->coleccion) !== 'otros' ? url('/relojes?coleccion=' . urlencode($product->coleccion)) : url('/relojes') }}" class="ml-auto inline-flex items-center text-[11px] font-bold text-red-700 dark:text-red-300 hover:underline gap-1 flex-shrink-0">
                Ver similares <i class="fa-solid fa-chevron-right text-[8px]"></i>
            </a>
        </div>
        @endif

        {{-- Main Product Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 items-start gap-3 lg:gap-8">
            {{-- Left Column: Media --}}
            <div class="lg:col-span-6">
                <div class="hidden lg:block lg:sticky lg:top-0">
                    <div class="relative overflow-hidden group/image" x-data='{
                        galleryItems: @json($galleryItems),
                        currentIndex: 0,
                        init() {
                            if (this.galleryItems.length > 1) {
                                setInterval(() => {
                                    this.currentIndex = (this.currentIndex + 1) % this.galleryItems.length;
                                }, 6000);
                            }
                        },
                        prev() {
                            this.currentIndex = (this.currentIndex - 1 + this.galleryItems.length) % this.galleryItems.length;
                        },
                        next() {
                            this.currentIndex = (this.currentIndex + 1) % this.galleryItems.length;
                        }
                    }'>
                        <div class="relative overflow-hidden" style="min-height: 400px;">
                            <div class="flex" :style="`transform: translateX(-${currentIndex * 100}%); transition: transform 1.5s cubic-bezier(0.4, 0, 0.2, 1);`">
                                <template x-for="(item, idx) in galleryItems" :key="idx">
                                    <div class="w-full flex-shrink-0 flex items-center justify-center aspect-square relative">
                                        <template x-if="item.type === 'image'">
                                            <div class="absolute inset-0 flex items-center justify-center cursor-zoom-in" @click="openImageModal(item.zoomUrl, '{{ $displayTitle }}')">
                                                <img :src="item.url" :alt="'{{ $displayTitle }} - ' + (idx + 1)" class="w-full h-full object-contain transition-transform duration-500 hover:scale-[1.02]" :loading="idx === 0 ? 'eager' : 'lazy'" :fetchpriority="idx === 0 ? 'high' : 'auto'" />
                                            </div>
                                        </template>
                                        <template x-if="item.type === 'video'">
                                            <div class="absolute inset-0 flex items-center justify-center cursor-pointer" @click="openVimeoModal(item.vimeoUrl)">
                                                <img :src="item.thumbnail || galleryItems[0].url" alt="Video del reloj" class="w-full h-full object-cover" loading="lazy" />
                                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                                                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 hover:border-white/60 transition-all duration-300 hover:scale-110">
                                                        <i class="fa-solid fa-play text-white text-2xl ml-1"></i>
                                                    </div>
                                                    <span class="bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full">
                                                        <i class="fa-solid fa-play text-[9px] mr-1"></i> Ver video
                                                    </span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- Navigation buttons --}}
                            <button type="button" @click="prev()" x-show="galleryItems.length > 1" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 dark:bg-gray-900/90 hover:bg-white dark:hover:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:text-[#00C4FF] rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 group-hover/image:opacity-100 z-20">
                                <i class="fa-solid fa-chevron-left text-sm"></i>
                            </button>
                            <button type="button" @click="next()" x-show="galleryItems.length > 1" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 dark:bg-gray-900/90 hover:bg-white dark:hover:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:text-[#00C4FF] rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 group-hover/image:opacity-100 z-20">
                                <i class="fa-solid fa-chevron-right text-sm"></i>
                            </button>
                        </div>

                        {{-- Thumbnail gallery --}}
                        @if(count($galleryItems) > 1)
                        <div class="flex gap-1.5 px-3 pb-3 overflow-x-auto mt-1">
                            @foreach($galleryItems as $i => $item)
                                @if($item['type'] === 'image')
                                <button type="button" @click="currentIndex = {{ $i }}" data-gallery-img="{{ $item['zoomUrl'] }}" class="w-16 h-16 flex-shrink-0 rounded-lg border-2 overflow-hidden bg-gray-50 dark:bg-gray-900 transition-all gallery-thumb"
                                    :class="currentIndex === {{ $i }} ? 'border-[#00C4FF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                                    <img src="{{ $item['url'] }}" alt="" class="w-full h-full object-contain" loading="lazy" onerror="this.closest('.gallery-thumb').style.display='none'" />
                                </button>
                                @else
                                <button type="button" @click="currentIndex = {{ $i }}" class="w-16 h-16 flex-shrink-0 rounded-lg border-2 overflow-hidden bg-gray-900 transition-all gallery-thumb relative"
                                    :class="currentIndex === {{ $i }} ? 'border-[#00C4FF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                                    <img src="{{ $galleryItems[0]['url'] }}" alt="" class="w-full h-full object-contain opacity-40" loading="lazy" />
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center shadow-lg border-2 border-white/40">
                                            <i class="fa-solid fa-play text-white text-[8px] ml-0.5"></i>
                                        </div>
                                    </div>
                                </button>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        {{-- Zoom button bottom-right (only on images) --}}
                        <button type="button" x-show="galleryItems[currentIndex]?.type === 'image'" @click="event.preventDefault(); openImageModal(galleryItems[currentIndex].zoomUrl, '{{ $displayTitle }}')" class="absolute bottom-4 right-4 w-9 h-9 bg-white/95 dark:bg-gray-900/95 border border-gray-200 dark:border-gray-700/80 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg shadow-sm flex items-center justify-center transition-all duration-300 opacity-0 group-hover/image:opacity-100 scale-95 group-hover/image:scale-100 z-30 cursor-pointer">
                            <i class="fa-solid fa-expand text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Mobile: Image (left, larger) + Buy Box (right) side-by-side, image not cropped --}}
                <div class="lg:hidden grid grid-cols-5 gap-1.5 items-start">
                    {{-- Image / Video (3/5 = 60% width) --}}
                    <div class="col-span-3">
                        <div class="relative" x-data='{
                            galleryItems: @json($galleryItems),
                            currentIndex: 0,
                            init() {
                                if (this.galleryItems.length > 1) {
                                    setInterval(() => {
                                        this.currentIndex = (this.currentIndex + 1) % this.galleryItems.length;
                                    }, 6000);
                                }
                            },
                            prev() {
                                this.currentIndex = (this.currentIndex - 1 + this.galleryItems.length) % this.galleryItems.length;
                            },
                            next() {
                                this.currentIndex = (this.currentIndex + 1) % this.galleryItems.length;
                            }
                        }'>
                            <div class="relative overflow-hidden group/image w-full aspect-square">
                                <div class="absolute inset-0 flex" :style="`transform: translateX(-${currentIndex * 100}%); transition: transform 1.5s cubic-bezier(0.4, 0, 0.2, 1);`">
                                    <template x-for="(item, idx) in galleryItems" :key="idx">
                                        <div class="relative w-full h-full flex-shrink-0 flex items-center justify-center">
                                            <template x-if="item.type === 'image'">
                                                <div class="absolute inset-0 flex items-center justify-center cursor-zoom-in" @click="openImageModal(item.zoomUrl, '{{ $product->title }}')">
                                                    <img :src="item.url" :alt="'{{ $product->title }} - ' + (idx + 1)" class="w-full h-full object-contain" :loading="idx === 0 ? 'eager' : 'lazy'" :fetchpriority="idx === 0 ? 'high' : 'auto'" />
                                                </div>
                                            </template>
                                            <template x-if="item.type === 'video'">
                                                <div class="absolute inset-0 flex items-center justify-center cursor-pointer" @click="openVimeoModal(item.vimeoUrl)">
                                                    <img :src="item.thumbnail || galleryItems[0].url" alt="Video del reloj" class="w-full h-full object-cover" loading="lazy" />
                                                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5">
                                                        <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 hover:border-white/60 transition-all duration-300 hover:scale-110">
                                                            <i class="fa-solid fa-play text-white text-lg ml-1"></i>
                                                        </div>
                                                        <span class="bg-black/60 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                                            <i class="fa-solid fa-play text-[8px] mr-1"></i> Ver video
                                                        </span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                {{-- Mobile navigation buttons (smaller) --}}
                                <button type="button" @click="prev()" x-show="galleryItems.length > 1" class="absolute left-1 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 dark:bg-gray-900/80 text-gray-600 dark:text-gray-300 rounded-full shadow flex items-center justify-center transition-all opacity-60 z-20">
                                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                </button>
                                <button type="button" @click="next()" x-show="galleryItems.length > 1" class="absolute right-1 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 dark:bg-gray-900/80 text-gray-600 dark:text-gray-300 rounded-full shadow flex items-center justify-center transition-all opacity-60 z-20">
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Buy box (2/5 = 40% width): price, buttons, specs alongside image --}}
                    <div class="col-span-2 flex flex-col items-stretch justify-start gap-1">
                        @if($isUpcoming)
                        <span class="text-lg font-black text-amber-500 tracking-tight text-center leading-none">Próx.</span>
                        @elseif(!$isAgotado)
                        <span class="text-xl font-black text-red-600 dark:text-red-400 tracking-tight text-center leading-none">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                        @endif

                        @if(!$isAgotado || $isUpcoming)
                            @if(!$isAgotado && !$isUpcoming && ($product->stock ?? 0) > 0)
                                @if($inCart)
                                <a href="{{ route('cart.show') }}" class="w-full flex items-center justify-center gap-1 py-1.5 bg-gray-800 hover:bg-gray-700 text-white rounded-lg font-bold uppercase tracking-wide text-[11px] transition-all shadow-sm">
                                    <i class="fa-solid fa-cart-shopping text-xs"></i> Ver Carrito
                                </a>
                                @else
                                <button type="button" onclick="addToCart({{ $product->id }}, this)" class="w-full flex items-center justify-center gap-1 py-1.5 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-lg font-bold uppercase tracking-wide text-[11px] transition-all active:scale-95 shadow-sm">
                                    <i class="fa-solid fa-cart-plus text-xs"></i> Agregar
                                </button>
                                @endif
                            @endif
                            <a href="{{ $whatsappBuy }}" data-conversion="whatsapp-comprar" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-1 py-1.5 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-lg font-bold uppercase tracking-wide text-[11px] transition-all active:scale-95 no-underline shadow-sm">
                                <i class="fa-brands fa-whatsapp text-xs"></i> Contactar
                            </a>
                        @endif

                        {{-- Mobile inline specs card --}}
                        <div id="mobile-specs-card" class="flex-col gap-0 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 overflow-hidden">
                            <div class="flex justify-between items-center px-2 py-1.5 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wide">Para</span>
                                <span class="text-[10px] font-semibold text-gray-800 dark:text-white capitalize">{{ $product->genero ?? 'Unisex' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2 py-1.5 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wide">Caja</span>
                                <span class="text-[10px] font-semibold text-gray-800 dark:text-white">{{ $size ? $size . 'mm' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2 py-1.5 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wide">Tipo</span>
                                <span class="text-[10px] font-semibold text-gray-800 dark:text-white capitalize">{{ $product->tipo_movimiento === 'cuarzo' ? 'Batería' : ($product->tipo_movimiento ?? 'Especial') }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2 py-1.5">
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wide">Agua</span>
                                <span class="text-[10px] font-semibold text-gray-800 dark:text-white">{{ $product->resistencia_agua ? $product->resistencia_agua . 'm' : 'Resistente' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                                    {{-- Mobile shipping banner --}}
                    <div class="lg:hidden w-full py-2 px-4 mt-2 overflow-hidden bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-emerald-900/20 dark:to-blue-900/20 rounded-lg">
                        <div class="flex animate-scroll" style="width: max-content;">
                            <div class="flex items-center gap-3 shrink-0 pr-3">
                                <i class="fa-solid fa-truck text-emerald-500 text-xs flex-shrink-0"></i>
                                <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider whitespace-nowrap">Envío Gratis*</span>
                                <span class="text-gray-400 dark:text-gray-600 flex-shrink-0">•</span>
                                <i class="fa-solid fa-hand-holding-dollar text-[#00C4FF] text-xs flex-shrink-0"></i>
                                <span class="text-[9px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Pago contra entrega*</span>
                                <span class="text-gray-400 dark:text-gray-600 flex-shrink-0">•</span>
                                <i class="fa-solid fa-clock text-[#00C4FF] text-xs flex-shrink-0"></i>
                                <span class="text-[9px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Entrega Inmediata</span>
                                <span class="text-gray-400 dark:text-gray-600 flex-shrink-0">•</span>
                            </div>
                            <div class="flex items-center gap-3 shrink-0 pr-3">
                                <i class="fa-solid fa-truck text-emerald-500 text-xs flex-shrink-0"></i>
                                <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider whitespace-nowrap">Envío Gratis*</span>
                                <span class="text-gray-400 dark:text-gray-600 flex-shrink-0">•</span>
                                <i class="fa-solid fa-hand-holding-dollar text-[#00C4FF] text-xs flex-shrink-0"></i>
                                <span class="text-[9px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Pago contra entrega*</span>
                                <span class="text-gray-400 dark:text-gray-600 flex-shrink-0">•</span>
                                <i class="fa-solid fa-clock text-[#00C4FF] text-xs flex-shrink-0"></i>
                                <span class="text-[9px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Entrega Inmediata</span>
                                <span class="text-gray-400 dark:text-gray-600 flex-shrink-0">•</span>
                            </div>
                        </div>
                    </div>

                 {{-- Mobile: Relojes Similares slider (replaces thumbnail strip, since all watches have a single image) --}}
                 @if($relatedProducts->count() > 0)
                 <div class="lg:hidden mt-3" x-data="mobileRelatedSlider()">
                     <h3 class="text-sm font-black text-gray-600 dark:text-gray-500 uppercase tracking-widest mb-3 px-1">Relojes Similares</h3>
                     <div id="mobile-related-slider" class="flex gap-2 overflow-x-auto pb-2 -mx-1 px-1 scrollbar-hide">
                        @foreach($relatedProducts as $related)
                        <div class="flex-shrink-0 w-32 snap-start">
                            <x-product-card-related :product="$related" compact />
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
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-800 dark:text-white tracking-tight leading-[1.1] uppercase">
                            {{ $displayTitle }}
                        </h1>
                    </div>
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

                {{-- Agotado / Próximo State --}}
                @if($isAgotado && !$isUpcoming)
                <div class="hidden lg:block bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/50 rounded-2xl p-6 text-center mb-10">
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
                @elseif(!$isUpcoming)
                    {{-- Desktop Price & Action Buttons --}}
                    <div class="hidden lg:flex flex-col items-start gap-2.5 mb-3.5">
                        <div class="flex items-baseline gap-3">
                            <span class="text-2xl lg:text-3xl font-black text-red-600 dark:text-red-400 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                            @if(($product->descuento ?? 0) > 0)
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400 line-through font-medium">₡{{ number_format($product->precio_venta, 0) }}</span>
                                <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded">-{{ $product->descuento }}%</span>
                            </div>
                            @endif
                        </div>

                        {{-- Desktop Action buttons --}}
                        <div class="flex gap-2.5 w-full">
                            @if(!$isAgotado && !$isUpcoming && ($product->stock ?? 0) > 0)
                                @if($inCart)
                                <a href="{{ route('cart.show') }}" class="flex-1 flex items-center justify-center gap-1 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 shadow-sm hover:shadow-md">
                                    <i class="fa-solid fa-cart-shopping text-base"></i> Ver Carrito
                                </a>
                                @else
                                <button type="button" onclick="addToCart({{ $product->id }}, this)" class="flex-1 flex items-center justify-center gap-1 py-2 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 shadow-sm hover:shadow-md">
                                    <i class="fa-solid fa-cart-plus text-base"></i> Agregar
                                </button>
                                @endif
                            @endif
                            <a href="{{ $whatsappBuy }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1 py-2 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                                <i class="fa-brands fa-whatsapp text-base"></i> Contactar
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Desktop Action buttons (no price for upcoming) --}}
                    <div class="hidden lg:flex flex-col items-center gap-2.5 mb-3.5">
                        <div class="flex gap-2.5 w-full">
                            <a href="{{ $whatsappBuy }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1 py-2 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                                <i class="fa-brands fa-whatsapp text-base"></i> Contactar
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Especificaciones (siempre visible) --}}
                <div class="hidden lg:block w-full mb-3.5 mt-4">
                    <div class="grid grid-cols-2">
                        <div class="p-2.5 flex items-start gap-2">
                            <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-venus-mars text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Para</p>
                                <p class="text-xs font-bold text-gray-900 dark:text-white capitalize">{{ $product->genero ?? 'Unisex' }}</p>
                            </div>
                        </div>
                        <div class="p-2.5 flex items-start gap-2">
                            <div class="mt-1 flex-shrink-0 w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-arrows-up-down-left-right text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Caja</p>
                                <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $size ? $size . 'mm' : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="p-2.5 flex items-start gap-2">
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
                        <div class="p-2.5 flex items-start gap-2">
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
                            <x-product-card-related :product="$related" compact />
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif


            </div>
        </div>
    </div>

    {{-- Vistos Recientemente --}}
    @if($recentlyViewed->count() > 0)
    <div class="max-w-7xl mx-auto px-4 mt-5 mb-8">
        {{-- Mobile: Vistos Recientemente slider --}}
        <div class="lg:hidden" x-data="mobileRecentlyViewedSlider()">
            <h3 class="text-sm font-black text-gray-600 dark:text-gray-500 uppercase tracking-widest mb-3 px-1">Vistos Recientemente</h3>
            <div id="mobile-recently-viewed-slider" class="flex gap-2 overflow-x-auto pb-2 -mx-1 px-1 scrollbar-hide">
                @foreach($recentlyViewed as $recent)
                <div class="flex-shrink-0 w-32 snap-start">
                    <x-product-card-related :product="$recent" compact />
                </div>
                @endforeach
            </div>
        </div>

        {{-- Desktop: Vistos Recientemente slider --}}
        <div class="hidden lg:block">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Vistos Recientemente</h2>
                <div class="flex gap-1.5">
                    <button type="button" onclick="scrollRecentlyViewed(-1)" aria-label="Anterior" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" onclick="scrollRecentlyViewed(1)" aria-label="Siguiente" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            <div id="recently-viewed-slider" class="flex gap-3 overflow-x-auto pb-1 snap-x snap-mandatory scrollbar-hide" style="scroll-behavior: smooth;">
                @foreach($recentlyViewed as $recent)
                <div class="flex-shrink-0 w-40 snap-start">
                    <x-product-card-related :product="$recent" compact />
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Floating WhatsApp buttons
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
        <a href="{{ $whatsappBuy }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 active:scale-95">
            <i class="fab fa-whatsapp text-lg"></i>
            <span class="hidden sm:inline">Comprar</span>
        </a>
    </div> --}}

    {{-- Share Modal --}}
    <div id="shareModal" class="modal-overlay fixed inset-0 z-[100] hidden items-center justify-center bg-black/85 p-4" onclick="if (event.target === this) closeShareModal()">
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
            <button type="button" onclick="closeShareModal()" aria-label="Cerrar" class="absolute top-3 right-3 z-10 flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full text-sm transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="p-4">
                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wide mb-3">Compartir</h3>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
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


        </div>
    </div>

    <script>
        var pixelModel = "{{ $product->modelo }}";
        var pixelTitle = "{{ $product->title }}";
        var pixelPrice = {{ $product->precio_venta }};
        window.invictaProductId = {{ $product->id }};
    </script>

    @push('scripts')
    <script>
        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes scroll-left {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(-50%);
                }
            }
            
            .animate-scroll {
                animation: scroll-left 18s linear infinite;
            }
        `;
        document.head.appendChild(style);

        function slowScrollTo(el, target, duration = 1200) {
            const start = el.scrollLeft;
            const diff = target - start;
            if (!diff) return;
            const t0 = performance.now();
            function step(now) {
                const p = Math.min((now - t0) / duration, 1);
                const ease = p < 0.5 ? 2 * p * p : 1 - Math.pow(-2 * p + 2, 2) / 2;
                el.scrollLeft = start + diff * ease;
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        // Mobile related slider auto-scroll
        function mobileRelatedSlider() {
            return {
                init() {
                    const slider = document.getElementById('mobile-related-slider');
                    if (!slider) return;
                    
                    let currentScroll = 0;
                    const scrollAmount = 148;
                    
                    setInterval(() => {
                        currentScroll += scrollAmount;
                        const maxScroll = slider.scrollWidth - slider.clientWidth;
                        
                        if (currentScroll >= maxScroll) {
                            currentScroll = 0;
                        }
                        
                        slowScrollTo(slider, currentScroll, 2000);
                    }, 7000);
                }
            }
        }

        // Mobile recently viewed slider auto-scroll
        function mobileRecentlyViewedSlider() {
            return {
                init() {
                    const slider = document.getElementById('mobile-recently-viewed-slider');
                    if (!slider) return;
                    
                    let currentScroll = 0;
                    const scrollAmount = 148;
                    
                    setInterval(() => {
                        currentScroll += scrollAmount;
                        const maxScroll = slider.scrollWidth - slider.clientWidth;
                        
                        if (currentScroll >= maxScroll) {
                            currentScroll = 0;
                        }
                        
                        slowScrollTo(slider, currentScroll, 2000);
                    }, 7000);
                }
            }
        }

        // Share modal
        function openShareModal() { var el = document.getElementById('shareModal'); if (!el) return; el.classList.remove('hidden'); el.classList.add('flex'); requestAnimationFrame(function() { el.classList.add('active'); }); document.body.style.overflow = 'hidden'; }
        function closeShareModal() { var el = document.getElementById('shareModal'); if (!el) return; el.classList.remove('active'); document.body.style.overflow = ''; setTimeout(function() { el.classList.add('hidden'); el.classList.remove('flex'); }, 300); }

        // Related products slider navigation
        function scrollRelated(dir) {
            var slider = document.getElementById("related-slider");
            if (!slider) return;
            var card = slider.querySelector(".flex-shrink-0");
            var step = card ? card.offsetWidth + 12 : 176;
            slider.scrollBy({ left: step * dir, behavior: "smooth" });
        }

        // Recently viewed slider navigation
        function scrollRecentlyViewed(dir) {
            var slider = document.getElementById("recently-viewed-slider");
            if (!slider) return;
            var card = slider.querySelector(".flex-shrink-0");
            var step = card ? card.offsetWidth + 12 : 176;
            slider.scrollBy({ left: step * dir, behavior: "smooth" });
        }

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
