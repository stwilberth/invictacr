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

        <div class="mt-auto pt-2 md:pt-3">
            @if((int) ($product->stock ?? 0) <= 0 || (($product->disponibilidad ?? '') === 'agotado'))
                <span class="w-full inline-flex items-center justify-center px-2 py-2 md:px-3 md:py-2.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl font-black uppercase tracking-wide text-sm md:text-base leading-none border border-red-200 dark:border-red-800">Agotado</span>
            @else
            <a href="{{ $whatsappLink }}" data-cta="comprar-whatsapp" data-product-id="{{ $product->id }}" target="_blank" rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center gap-1.5 px-2 py-2 md:px-3 md:py-2.5 bg-[#7BD389] hover:bg-[#5EC975] text-[#0a0f1c] rounded-xl font-black uppercase tracking-wide text-sm md:text-base leading-none no-underline">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 md:w-6 md:h-6 shrink-0" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span>Comprar</span>
            </a>
            @endif
        </div>
    </div>
</div>
