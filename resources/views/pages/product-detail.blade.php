@php
    $size = $product->size ? preg_replace('/\s*mm$/i', '', $product->size) : '';
    $displayTitle = $product->display_title;

    $seoTitleParts = array_filter([
        'Reloj Invicta',
        $product->coleccion && strtolower($product->coleccion) !== 'otros' ? $product->coleccion : null,
        $product->modelo,
        $size ? $size . ' mm' : null,
    ]);
    $seoTitle = trim(implode(' ', $seoTitleParts)) . ' | Invicta Costa Rica';

    $ogImage = route('og.product', $product->slug);

    $galleryItemsForLcp = $galleryItems ?? [];
    $lcpImageUrl = !empty($galleryItemsForLcp) ? ($galleryItemsForLcp[0]['url'] ?? '') : $product->imagen;
    // Para JSON-LD Product schema Google prefiere la foto real del producto (no video thumb ni OG wrapper)
    $schemaImage = collect($galleryItemsForLcp)->firstWhere('type', 'image')['url'] ?? $lcpImageUrl ?: $ogImage;

    $priceFmt = '₡' . number_format((float) ($product->price_after_discount ?? $product->precio_venta ?? 0), 0);
    $descParts = array_filter([
        $product->coleccion && strtolower($product->coleccion) !== 'otros' ? $product->coleccion : null,
        $product->size ? $size . ' mm' : null,
        $product->tipo_movimiento ? $product->tipo_movimiento : null,
    ]);
    $isUpcomingForSeo = ($product->proximo ?? false) || (float) ($product->precio_venta ?? 0) <= 0;
    $seoDescription = $product->descripcion
        ?: ('Reloj Invicta ' . ($descParts ? implode(' · ', $descParts) . ' ' : '') . ($isUpcomingForSeo ? '— Próximamente. ' : '— ' . $priceFmt . '. ') . 'Envío gratis en GAM. Pago contra entrega. WhatsApp +506 8671-1422.');

    $productName = 'Reloj Invicta ' . ($product->coleccion && strtolower($product->coleccion) !== 'otros' ? $product->coleccion . ' ' : '') . ($product->genero && strtolower($product->genero) !== 'unisex' ? 'para ' . $product->genero . ' ' : '') . '(' . $product->modelo . ')';
    $price = $product->price_after_discount ?? $product->precio_venta ?? 0;
    $availability = ($product->stock ?? 0) > 0 && ($product->disponibilidad ?? 'disponible') !== 'agotado' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
@endphp
@push('json-ld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Inicio",
            "item": "{{ url('/') }}"
        },
        {
            "@@type": "ListItem",
            "position": 2,
            "name": "{{ $product->genero && strtolower($product->genero) !== 'unisex' ? 'Relojes ' . ucfirst($product->genero) : 'Relojes' }}",
            "item": "{{ url('/relojes') . ($product->genero && strtolower($product->genero) !== 'unisex' ? '?gender=' . $product->genero : '') }}"
        },
        {
            "@@type": "ListItem",
            "position": 3,
            "name": "{{ $displayTitle }}",
            "item": "{{ url()->current() }}"
        }
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Product",
    "name": {!! json_encode($productName) !!},
    "image": {!! json_encode($schemaImage) !!},
    "description": {!! json_encode($seoDescription) !!},
    "sku": {!! json_encode($product->modelo) !!},
    "brand": {"@type": "Brand", "name": "Invicta"},
    @if($product->video_uid)
    "video": {
        "@@type": "VideoObject",
        "name": {!! json_encode($productName) !!},
        "description": {!! json_encode($seoDescription) !!},
        "thumbnailUrl": {!! json_encode('https://' . config('services.cloudflare.stream_customer_subdomain') . '.cloudflarestream.com/' . $product->video_uid . '/thumbnails/thumbnail.jpg') !!},
        "contentUrl": {!! json_encode('https://' . config('services.cloudflare.stream_customer_subdomain') . '.cloudflarestream.com/' . $product->video_uid . '/iframe') !!},
        "embedUrl": {!! json_encode('https://' . config('services.cloudflare.stream_customer_subdomain') . '.cloudflarestream.com/' . $product->video_uid . '/iframe') !!},
        "uploadDate": {!! json_encode($product->created_at ? $product->created_at->toIso8601String() : date('c')) !!}
    },
    @endif
    "offers": {
        "@@type": "Offer",
        "url": {!! json_encode(url()->current()) !!},
        "priceCurrency": "CRC",
        "price": {!! json_encode($price) !!},
        "availability": {!! json_encode($availability) !!}
    }
}
</script>
@endpush
<x-app-layout :title="$seoTitle" :description="$seoDescription" :ogImage="$ogImage" :ogImageAlt="$displayTitle" ogType="product" :hideWhatsApp="true" :titleSuffix="false" :head="$lcpImageUrl ? '<link rel=&quot;preload&quot; href=&quot;' . $lcpImageUrl . '&quot; as=&quot;image&quot; fetchpriority=&quot;high&quot;>' : ''" >
    @php
        $isAgotado = ($product->stock ?? 0) <= 0 || ($product->disponibilidad ?? 'disponible') === 'agotado';
        $isUpcoming = $product->proximo || $product->precio_venta <= 0;
        $isUpcomingYAgotado = $isUpcoming && $isAgotado;
        $priceAfterDiscount = $product->price_after_discount;
        $apartadoMinimo = round((float) ($priceAfterDiscount ?? $product->precio_venta ?? 0) * 0.2, -3);
        $whatsappBuy = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Me interesa el reloj Invicta {$product->modelo}");
        $whatsappApartado = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Quiero apartar el reloj Invicta {$product->modelo}");
        $shareLinkFor = fn(string $source): string => url()->current() . '?utm_source=' . $source . '&utm_medium=compartir&utm_campaign=ficha_producto';
        $shareUrl = urlencode($shareLinkFor('whatsapp'));
        $shareTitle = urlencode("¡Mira este reloj Invicta!: {$product->title}");

        $inCart = false;
        if (session()->has('cart_session_id') || auth()->check()) {
            try {
                $inCart = app(\App\Services\CartService::class)->getCart()->items->contains('product_id', $product->id);
            } catch (\Exception $e) {}
        }
    @endphp

    <div class="max-w-[1472px] mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-5">
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

        {{-- Main Product Layout (vista única responsive) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 items-start gap-4 lg:gap-10 xl:gap-14">
            {{-- Left Column: Media --}}
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-5">
                    <div class="relative group/image">
                        @if(($product->descuento ?? 0) > 0)
                        <div class="absolute top-4 right-4 z-30">
                            <span class="bg-red-500 text-white text-xs font-black px-3 py-1.5 rounded-lg shadow-sm">-{{ $product->descuento }}%</span>
                        </div>
                        @endif
                        {{-- Badge 100% ORIGINAL (mockup style) --}}
                        <div class="absolute top-2.5 left-2.5 z-30 sm:top-4 sm:left-4">
                            <span class="flex items-center gap-1 sm:gap-2 bg-[#101828] text-white text-[8px] xs:text-[9px] sm:text-xs font-extrabold px-2 py-1 sm:px-4 sm:py-2 rounded-full shadow-md">
                                <i class="fa-solid fa-shield-halved text-[8px] sm:text-sm"></i> 100% ORIGINAL
                            </span>
                        </div>
                        <x-product-gallery :galleryItems="$galleryItems" :title="$displayTitle" />
                    </div>
                </div>

            </div>

            {{-- Right Column: Buy Box --}}
            <div class="lg:col-span-1 flex flex-col">
                {{-- Title Header --}}
                <div class="mb-3">
                    <div class="flex items-start gap-2 sm:gap-3 mb-2">
                        <h1 class="flex-1 text-lg sm:text-2xl lg:text-[28px] xl:text-[32px] font-black text-[#14325E] dark:text-white tracking-tight leading-[1.1] uppercase">
                            {{ $displayTitle }}
                        </h1>
                        <button type="button" onclick="openShareModal()" aria-label="Compartir" class="flex-shrink-0 w-9 h-9 lg:w-10 lg:h-10 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-300 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors" title="Compartir">
                            <i class="fa-solid fa-share-nodes text-sm"></i>
                        </button>
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
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/50 rounded-2xl p-3 mt-2 text-center">
                    <h3 class="text-lg font-bold text-red-700 dark:text-red-400 mb-1 leading-tight">Agotado</h3>
                    <a href="{{ $whatsappBuy }}" data-cta="ver-disponibilidad" data-product-id="{{ $product->id }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-circle-info"></i> Ver disponibilidad
                    </a>
                </div>
                @elseif(!$isUpcoming)
                    {{-- Price & Action Buttons --}}
                    <div class="flex flex-col items-start gap-4 mb-5">
                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                            <span class="text-2xl md:text-[40px] leading-none font-black text-[#0A7CFF] tracking-tight">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                            <span class="text-sm font-bold text-gray-400">+ IVA</span>
                            @if(($product->descuento ?? 0) > 0)
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-400 line-through font-medium">₡{{ number_format($product->precio_venta, 0) }}</span>
                                <span class="bg-red-500 text-white text-[11px] font-black px-2.5 py-1 rounded-lg shadow-sm">-{{ $product->descuento }}% OFF</span>
                            </div>
                            @endif
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex flex-col sm:flex-row gap-3 w-full">
                            <a href="{{ $whatsappBuy }}" data-cta="comprar-whatsapp" data-product-id="{{ $product->id }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 py-3 md:py-3.5 bg-[#0EB45D] hover:bg-[#0aa550] text-white rounded-[10px] font-bold text-sm md:text-[15px] transition-all no-underline shadow-sm">
                                <i class="fa-brands fa-whatsapp text-xl"></i> Contactar
                            </a>
                            @if(!$isAgotado && !$isUpcoming && ($product->stock ?? 0) > 0)
                                @if($inCart)
                                <a href="{{ route('cart.show') }}" class="flex-1 flex items-center justify-center gap-2 py-3 md:py-3.5 bg-[#0A7CFF] hover:bg-[#0869D6] text-white rounded-[10px] font-bold text-sm md:text-[15px] transition-all shadow-sm">
                                    <i class="fa-solid fa-cart-shopping text-lg"></i> Ver Carrito
                                </a>
                                @else
                                <button type="button" data-cta="comprar-ahora" data-product-id="{{ $product->id }}" onclick="addToCart({{ $product->id }}, this)" class="flex-1 flex items-center justify-center gap-2 py-3 md:py-3.5 bg-[#0A7CFF] hover:bg-[#0869D6] text-white rounded-[10px] font-bold text-sm md:text-[15px] transition-all shadow-sm">
                                    <i class="fa-solid fa-cart-shopping text-lg"></i> Comprar ahora
                                </button>
                                @endif
                            @endif
                        </div>

                        <x-product-benefits :apartadoMinimo="$apartadoMinimo" :apartadoWhatsapp="$whatsappApartado" />

                        {{-- Métodos de pago aceptados --}}
                        <x-payment-methods />

                    </div>
                @else
                    {{-- Action buttons (no price for upcoming / agotado) --}}
                    <div class="flex flex-col items-center gap-2.5 mb-3.5">
                        <div class="flex flex-col gap-2 w-full">
                            <a href="{{ $whatsappBuy }}" data-cta="ver-disponibilidad" data-product-id="{{ $product->id }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1 py-2 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                                <i class="fa-solid fa-circle-info text-base"></i> Ver disponibilidad
                            </a>
                            <a href="{{ $whatsappBuy }}" data-cta="comprar-whatsapp" data-product-id="{{ $product->id }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1 py-2 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                                <i class="fa-brands fa-whatsapp text-base"></i> Contactar
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Especificaciones (siempre visible) --}}
                <div class="w-full mb-3.5 mt-1">
                    <div class="grid grid-cols-2 gap-x-4 lg:gap-x-6 gap-y-4 lg:gap-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-[10px] bg-[#EEF3FA] dark:bg-gray-800 flex items-center justify-center text-[#14325E] dark:text-gray-300">
                                <i class="fa-solid fa-venus-mars text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Para</p>
                                <p class="text-[13px] md:text-sm font-bold text-[#14325E] dark:text-white capitalize">{{ $product->genero === 'mujer' ? 'Mujer' : ($product->genero ?? 'Unisex') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-[10px] bg-[#EEF3FA] dark:bg-gray-800 flex items-center justify-center text-[#14325E] dark:text-gray-300">
                                <i class="fa-solid fa-arrows-up-down-left-right text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Tamaño de caja</p>
                                <p class="text-[13px] md:text-sm font-bold text-[#14325E] dark:text-white">{{ $size ? $size . '.0mm' : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-[10px] bg-[#EEF3FA] dark:bg-gray-800 flex items-center justify-center text-[#14325E] dark:text-gray-300">
                                <i class="fa-solid fa-gear text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Movimiento</p>
                                <p class="text-[13px] md:text-sm font-bold text-[#14325E] dark:text-white capitalize line-clamp-1">{{ $product->tipo_movimiento === 'cuarzo' ? 'Batería' : ($product->tipo_movimiento ?? 'Especial') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-[10px] bg-[#EEF3FA] dark:bg-gray-800 flex items-center justify-center text-[#14325E] dark:text-gray-300">
                                <i class="fa-solid fa-droplet text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Resistencia al agua</p>
                                <p class="text-[13px] md:text-sm font-bold text-[#14325E] dark:text-white whitespace-nowrap">{{ $product->resistencia_agua ? $product->resistencia_agua . 'm' : 'Resistente' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-[10px] bg-[#EEF3FA] dark:bg-gray-800 flex items-center justify-center text-[#14325E] dark:text-gray-300">
                                <i class="fa-solid fa-water text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Colección</p>
                                <p class="text-[13px] md:text-sm font-bold text-[#14325E] dark:text-white">{{ $product->coleccion ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-[10px] bg-[#EEF3FA] dark:bg-gray-800 flex items-center justify-center text-[#14325E] dark:text-gray-300">
                                <i class="fa-solid fa-stopwatch text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Brazalete</p>
                                <p class="text-[13px] md:text-sm font-bold text-[#14325E] dark:text-white capitalize">{{ $product->brazalete ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                        {{-- Banner informativo azul (mockup style) --}}
                        <div class="w-full mt-6">
                            <div class="bg-[#EAF2FF] dark:bg-blue-900/20 rounded-xl px-5 py-4 flex items-start gap-3">
                                <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-[#0A7CFF] mt-0.5">
                                    <i class="fa-solid fa-award text-2xl"></i>
                                </span>
                                <div>
                                    <p class="text-[13px] leading-snug font-medium text-[#14325E] dark:text-blue-100">Este modelo es solo uno de los más de 300 estilos Invicta que tenemos disponibles.</p>
                                    <a href="/relojes" class="text-[13px] font-semibold text-[#0A7CFF] hover:underline no-underline">Ver catálogo completo →</a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        {{-- También te puede interesar (ancho completo) --}}
        @if($relatedProducts->count() > 0)
        <div class="mt-8 lg:mt-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-black text-[#14325E] dark:text-white uppercase tracking-wide">También te puede interesar</h2>
                <div class="flex gap-2">
                    <button type="button" onclick="scrollRelated(-1)" aria-label="Anterior" class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#0A7CFF] hover:border-[#0A7CFF] transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" onclick="scrollRelated(1)" aria-label="Siguiente" class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#0A7CFF] hover:border-[#0A7CFF] transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            <div id="related-slider" class="flex gap-3 lg:gap-4 overflow-x-auto pb-1 snap-x snap-mandatory scrollbar-hide" style="scroll-behavior: smooth;">
                @foreach($relatedProducts as $related)
                <div class="flex-shrink-0 w-40 sm:w-44 lg:w-48 snap-start">
                    <x-product-card-related :product="$related" />
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Vistos Recientemente --}}
    @if($recentlyViewed->count() > 0)
    <div class="max-w-7xl mx-auto px-4 mt-5 mb-8">
        <div class="flex items-center justify-between mb-3 lg:mb-4">
            <h2 class="text-sm lg:text-xl font-black text-gray-600 dark:text-gray-400 lg:text-gray-900 lg:dark:text-white uppercase tracking-widest lg:tracking-tight px-1 lg:px-0">Vistos Recientemente</h2>
            <div class="flex gap-1.5">
                <button type="button" onclick="scrollRecentlyViewed(-1)" aria-label="Anterior" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button type="button" onclick="scrollRecentlyViewed(1)" aria-label="Siguiente" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] transition-colors">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>
        <div id="recently-viewed-slider" class="flex gap-2 lg:gap-3 overflow-x-auto pb-2 lg:pb-1 -mx-1 px-1 lg:mx-0 lg:px-0 snap-x snap-mandatory scrollbar-hide" style="scroll-behavior: smooth;">
            @foreach($recentlyViewed as $recent)
            <div class="flex-shrink-0 w-32 sm:w-40 snap-start">
                <x-product-card-related :product="$recent" />
            </div>
            @endforeach
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
                    <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ urlencode($shareLinkFor('whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#25D366] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
                        <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareLinkFor('facebook')) }}&quote={{ $shareTitle }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#1877F2] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
                        <i class="fa-brands fa-facebook text-lg"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ urlencode($shareLinkFor('x')) }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-gray-900 text-white text-[11px] font-semibold hover:bg-gray-800 transition-all no-underline">
                        <i class="fa-brands fa-x-twitter text-lg"></i> X
                    </a>
                    <a href="https://pinterest.com/pin/create/button/?url={{ urlencode($shareLinkFor('pinterest')) }}&description={{ $shareTitle }}&media={{ urlencode($product->imagen) }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#E60023] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
                        <i class="fa-brands fa-pinterest text-lg"></i> Pinterest
                    </a>
                    <button type="button" onclick="copyProductUrl()" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-gray-500 text-white text-[11px] font-semibold hover:bg-gray-600 transition-all">
                        <i class="fa-solid fa-link text-lg"></i> Copiar
                    </button>
                    <a href="https://t.me/share/url?url={{ urlencode($shareLinkFor('telegram')) }}&text={{ $shareTitle }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-1 py-3 rounded-lg bg-[#0088cc] text-white text-[11px] font-semibold hover:brightness-110 transition-all no-underline">
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

        if (typeof fbq !== "undefined") {
            fbq("track", "ViewContent", {
                content_ids: [pixelModel],
                content_name: pixelTitle,
                content_type: "product",
                value: pixelPrice,
                currency: "CRC"
            });
        }
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

        // Copy URL functionality (incluye UTM de compartir)
        function copyProductUrl() {
            const url = "{!! $shareLinkFor('copiar') !!}";
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
