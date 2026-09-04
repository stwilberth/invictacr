@props(['product', 'compact' => false, 'priority' => false])
@php
    $productUrl = route('products.show', ['slug' => $product->slug]);
    $whatsappLink = 'https://wa.me/50686711422?text=' . urlencode("Hola, me interesa el reloj Invicta {$product->modelo}: " . url($productUrl));
    $priceAfterDiscount = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);
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

    // "Datos del reloj" (specs cortos que caben en la tarjeta)
    $specs = [];
    $mov = trim(mb_strtolower((string) ($product->tipo_movimiento ?? ''), 'UTF-8'));
    if (in_array($mov, ['automatico', 'automático', 'automatic', 'mecanico'], true)) {
        $specs[] = 'Movimiento automático';
    } elseif (in_array($mov, ['cuarzo', 'solar'], true)) {
        $specs[] = $mov === 'solar' ? 'Movimiento solar' : 'Movimiento de cuarzo';
    }
    $sizeDigits = trim((string) preg_replace('/[^0-9.,]/', '', (string) ($product->size ?? '')));
    if ($sizeDigits !== '' && (float) $sizeDigits > 0) {
        $specs[] = 'Caja ' . $sizeDigits . ' mm';
    }
    $brazalete = trim((string) ($product->brazalete ?? ''));
    if ($brazalete !== '' && $brazalete !== 'Otros') {
        $specs[] = 'Correa ' . $brazalete;
    } elseif (($caja = trim((string) ($product->caja ?? ''))) !== '' && $caja !== 'Otros') {
        $specs[] = 'Caja ' . $caja;
    }
    $water = trim((string) ($product->resistencia_agua ?? ''));
    if ($water !== '') {
        $waterDigits = preg_replace('/[^0-9]/', '', $water);
        $specs[] = 'Resistencia al agua ' . ($waterDigits !== '' ? $waterDigits : $water) . ' M';
    }
@endphp

<div class="group relative flex flex-col h-full rounded-2xl bg-white dark:bg-[#0d1424] overflow-hidden">
    {{-- Imagen principal (sin slider) --}}
    <div class="relative w-full pt-[100%] overflow-hidden bg-white">
        @if($primary)
        <a href="{{ $productUrl }}" class="absolute inset-0 flex items-center justify-center focus-visible:outline-none" aria-label="Ver {{ $cardTitle }}">
            <img
                src="{{ $primary }}"
                alt="{{ $cardTitle }}"
                class="absolute inset-0 w-full h-full object-contain {{ $compact ? 'p-0.5' : 'p-1.5' }} select-none"
                loading="{{ $priority ? 'eager' : 'lazy' }}"
                {{ $priority ? 'fetchpriority="high"' : '' }}
                @if($mainSrc && $originalImg) data-original="{{ $originalImg }}" @endif
                draggable="false"
                onerror="window.invictaImgFallback ? invictaImgFallback(this) : (this.style.display='none');"
            />
        </a>
        @else
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="font-black text-slate-300 dark:text-slate-600 {{ $compact ? 'text-lg' : 'text-2xl' }} tracking-tighter">{{ $model }}</span>
            <span class="text-[8px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Invicta</span>
        </div>
        @endif

        {{-- Badges --}}
        @if(($product->descuento ?? 0) > 0 && $product->precio_venta > 0)
        <div class="absolute {{ $compact ? 'top-1 left-1' : 'top-1 left-1 md:top-2 md:left-2' }} z-10">
            <span class="inline-flex items-center rounded-full bg-red-600 {{ $compact ? 'px-1 py-0.5 text-[8px]' : 'px-1 py-0.5 text-[8px] md:px-2 md:py-1 md:text-[10px]' }} font-black text-white shadow-lg border border-white/10">
                -{{ $product->descuento }}%
            </span>
        </div>
        @endif

        @if($product->tipo_movimiento && in_array(strtolower($product->tipo_movimiento), ['automatico', 'automático', 'automatic'], true))
        <div class="absolute {{ $compact ? 'top-1 right-1' : 'top-1 right-1 md:top-2 md:right-2' }} z-10">
            <span class="inline-flex border border-gray-400 dark:border-gray-500 items-center rounded-full bg-[#facc15] dark:bg-[#facc15] {{ $compact ? 'px-1 py-0.5 text-[7px]' : 'px-1 py-0.5 text-[7px] md:px-2 md:py-1 md:text-[9px]' }} font-black text-black dark:text-black shadow-lg uppercase tracking-wide">
                Automático
            </span>
        </div>
        @endif

    </div>

    <div class="{{ $compact ? 'p-2' : 'p-2 md:p-4' }} flex flex-col flex-grow">
        <a href="{{ $productUrl }}" class="block focus-visible:outline-none" aria-label="Ver {{ $cardTitle }}">
            <h3 class="{{ $compact ? 'text-[10px]' : 'text-[11px] md:text-sm' }} w-full font-bold text-slate-700 dark:text-slate-100 leading-snug uppercase tracking-wide line-clamp-2 min-h-[2.75em] text-center">
                {{ $cardTitle }}
            </h3>
        </a>

        @if(!empty($specs))
        <div class="mt-2 hidden md:grid grid-cols-2 gap-x-2 gap-y-1 justify-items-start text-left">
            @foreach($specs as $spec)
            <span class="inline-flex w-full items-start gap-1.5 min-w-0 text-left text-[11px] md:text-[11px] font-semibold leading-snug text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-circle text-[#00C4FF]/60 text-[3px] mt-1 shrink-0"></i>
                <span class="leading-snug">{{ $spec }}</span>
            </span>
            @endforeach
        </div>
        @endif

        <div class="mt-2 md:mt-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between sm:gap-2">
            @if($product->proximo || $product->precio_venta <= 0)
                <div class="flex-1 text-center">
                    <span class="text-[9px] md:text-xs font-bold px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md uppercase tracking-wide">Próximamente</span>
                </div>
            @elseif($product->precio_venta > 0)
                <div class="flex flex-col items-start gap-1 min-w-0">
                    @if($apartadoMinimo > 0)
                    <span class="inline-flex items-center gap-1 text-[10px] md:text-xs font-semibold text-gray-600 dark:text-gray-300 leading-tight">
                        <i class="fa-solid fa-hand-holding-dollar text-[#00C4FF] text-[10px] md:text-xs"></i>
                        <span>Apartado <span class="font-black text-gray-800 dark:text-white">₡{{ number_format($apartadoMinimo, 0) }}</span></span>
                    </span>
                    @endif
                </div>
                <div class="shrink-0 flex flex-row items-baseline gap-1 sm:flex-col sm:items-end text-right leading-none">
                    @if(($product->descuento ?? 0) > 0)
                        <span class="text-[10px] md:text-xs font-bold text-slate-400 dark:text-gray-500 line-through">₡{{ number_format($product->precio_venta, 0) }}</span>
                    @endif
                    <span class="text-base md:text-xl font-black text-red-600 dark:text-red-500 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                </div>
            @else
                <div class="flex-1 text-center">
                    <span class="text-[9px] md:text-xs font-bold px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md uppercase tracking-wide">Agotado</span>
                </div>
            @endif
        </div>

        <div class="mt-auto pt-2 md:pt-3">
            <a href="{{ $whatsappLink }}" data-cta="comprar-whatsapp" data-product-id="{{ $product->id }}" target="_blank" rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center gap-1 px-2 py-2 md:px-3 md:py-2.5 bg-[#7BD389] hover:bg-[#5EC975] text-[#0a0f1c] rounded-xl font-bold uppercase tracking-wide text-[11px] md:text-sm leading-none no-underline">
                <i class="fa-brands fa-whatsapp text-xs md:text-sm"></i>
                <span>Comprar</span>
            </a>
        </div>
    </div>
</div>
