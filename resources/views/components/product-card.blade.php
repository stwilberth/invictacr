@props(['product', 'priority' => false])
@php
    $productUrl = route('products.show', ['slug' => $product->slug]);
    $whatsappLink = 'https://wa.me/50686711422?text=' . urlencode("Hola, me interesa el reloj Invicta {$product->modelo}: " . url($productUrl));
    $priceAfterDiscount = $product->precio_final;
    $priceBaseFinal = \App\Models\Product::precioConIva($product->precio_venta ?? 0);
    $apartadoMinimo = (float) $priceAfterDiscount > 0 ? round($priceAfterDiscount * 0.2, -3) : 0;
    $model = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
    $cdnBase = 'https://cdn.invictacostarica.com';

    // Imagen principal de calidad (medium) con fallback al JPG original. Solo una imagen, sin slider.
    $originalImg = $product->imagen;
    $mainSrc = $originalImg && $model !== '' ? "{$cdnBase}/relojes/medium/{$model}.webp" : null;
    if ($mainSrc === $originalImg) $mainSrc = null;
    $primary = $mainSrc ?: $originalImg;

    $coleccion = trim($product->coleccion ?? '');
    $cardTitle = 'Reloj Invicta';
    if ($coleccion !== '' && strtolower($coleccion) !== 'otros') {
        $cardTitle .= ' ' . $coleccion;
    }
    $cardTitle .= ' ' . ($model !== '' ? $model : 'Reloj');

    $sizeDigits = trim((string) preg_replace('/[^0-9.,]/', '', (string) ($product->size ?? '')));

    // Género y tamaño para el card de /relojes (visible en mobile y desktop)
    $generoLabel = trim((string) ($product->genero ?? ''));
    $generoLabel = $generoLabel !== '' ? ucfirst(mb_strtolower($generoLabel, 'UTF-8')) : 'Unisex';
    $sizeLabel = $sizeDigits !== '' && (float) $sizeDigits > 0 ? $sizeDigits . ' mm' : trim((string) ($product->size ?? ''));
@endphp

<div class="group relative flex flex-col h-full rounded-2xl bg-white dark:bg-[#0d1424] overflow-hidden">
    {{-- Imagen principal (sin slider) --}}
    <div class="relative w-full pt-[100%] overflow-hidden bg-white">
        @if($primary)
        <a href="{{ $productUrl }}" class="absolute inset-0 flex items-center justify-center focus-visible:outline-none" aria-label="Ver {{ $cardTitle }}">
            <img
                src="{{ $primary }}"
                alt="{{ $cardTitle }}"
                class="absolute inset-0 w-full h-full object-contain p-1.5 select-none"
                loading="{{ $priority ? 'eager' : 'lazy' }}"
                {{ $priority ? 'fetchpriority="high"' : '' }}
                @if($mainSrc && $originalImg) data-original="{{ $originalImg }}" @endif
                draggable="false"
                onerror="window.invictaImgFallback ? invictaImgFallback(this) : (this.style.display='none');"
            />
        </a>
        @else
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="font-black text-slate-300 dark:text-slate-600 text-2xl tracking-tighter">{{ $model }}</span>
            <span class="text-[8px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Invicta</span>
        </div>
        @endif

        {{-- Badges --}}
        @if(($product->descuento ?? 0) > 0 && $product->precio_venta > 0)
        <div class="absolute top-1 left-1 md:top-2 md:left-2 z-10">
            <span class="inline-flex items-center rounded-full bg-red-600 px-1 py-0.5 text-[8px] md:px-2 md:py-1 md:text-[10px] font-black text-white shadow-lg border border-white/10">
                -{{ $product->descuento }}%
            </span>
        </div>
        @endif

        @if($product->tipo_movimiento && in_array(strtolower($product->tipo_movimiento), ['automatico', 'automático', 'automatic'], true))
        <div class="absolute top-1 right-1 md:top-2 md:right-2 z-10">
            <span class="inline-flex border border-gray-400 dark:border-gray-500 items-center rounded-full bg-[#facc15] dark:bg-[#facc15] px-1 py-0.5 text-[7px] md:px-2 md:py-1 md:text-[9px] font-black text-black dark:text-black shadow-lg uppercase tracking-wide">
                Automático
            </span>
        </div>
        @endif

    </div>

    <div class="p-2 md:p-4 flex flex-col flex-grow">
        <a href="{{ $productUrl }}" class="block focus-visible:outline-none" aria-label="Ver {{ $cardTitle }}">
            <h3 class="text-[11px] md:text-sm w-full font-bold text-slate-700 dark:text-slate-100 leading-snug uppercase tracking-wide line-clamp-2 min-h-[2.75em] text-center">
                {{ $cardTitle }}
            </h3>
        </a>
        <p class="mt-1 w-full text-center text-[10px] md:text-[11px] font-semibold text-slate-500 dark:text-slate-400 leading-snug">
            {{ $generoLabel }}@if($sizeLabel !== '') • {{ $sizeLabel }}@endif
        </p>

        <div class="mt-2 md:mt-4 text-center">
            @if($product->proximo || $product->precio_venta <= 0)
                <span class="text-[9px] md:text-xs font-bold px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md uppercase tracking-wide">Próximamente</span>
            @elseif($product->precio_venta > 0)
                <span class="text-base md:text-xl font-black text-red-600 dark:text-red-500 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
            @else
                <span class="text-[9px] md:text-xs font-bold px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md uppercase tracking-wide">Agotado</span>
            @endif
        </div>

        <div class="mt-auto pt-2 md:pt-3 flex flex-col gap-1.5 md:gap-2">
            <a href="{{ $productUrl }}" aria-label="Ver {{ $cardTitle }}"
                    class="w-full inline-flex items-center justify-center gap-1.5 px-2 py-2.5 md:py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/15 text-slate-700 dark:text-slate-100 rounded-none font-black uppercase tracking-wide text-[13px] md:text-sm leading-none no-underline hover:border-[#00C4FF] hover:text-[#00C4FF] transition-all">
                    <span>Ver</span>
                </a>
            @if((int) ($product->stock ?? 0) <= 0 || (($product->disponibilidad ?? '') === 'agotado'))
                <span class="w-full inline-flex items-center justify-center px-2 py-2.5 md:py-3 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-none font-black uppercase tracking-wide text-[13px] md:text-sm leading-none border border-red-200 dark:border-red-800">Agotado</span>
            @else
            <button type="button" onclick="addToCart({{ $product->id }}, this)"
                class="w-full inline-flex items-center justify-center gap-1.5 px-2 py-2.5 md:py-3 bg-[#B3E9FF] hover:bg-[#8FDDFF] text-[#0a0f1c] rounded-none font-black uppercase tracking-wide text-[13px] md:text-sm leading-none transition-all">
                <i class="fa-solid fa-cart-plus text-sm shrink-0"></i>
                <span>Comprar</span>
            </button>
            @endif
        </div>
    </div>
</div>
