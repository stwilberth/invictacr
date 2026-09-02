@props(['product', 'compact' => false, 'priority' => false])
@php
    $productUrl = route('products.show', ['slug' => $product->slug]);
    $whatsappLink = 'https://wa.me/50686711422?text=' . urlencode("Hola, me interesa el reloj Invicta {$product->modelo}: " . url($productUrl));
    $priceAfterDiscount = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);
    $model = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
    $cdnBase = 'https://cdn.invictacostarica.com';

    $thumbUrl = $product->imagen ? "{$cdnBase}/relojes/thumbs/{$model}.webp" : null;
    $imageUrl = $thumbUrl ?? $product->imagen;

    $coleccion = trim($product->coleccion ?? '');
    $cardTitle = 'Reloj Invicta';
    if ($coleccion !== '' && strtolower($coleccion) !== 'otros') {
        $cardTitle .= ' ' . $coleccion;
    }
    $cardTitle .= ' ' . ($model !== '' ? $model : 'Reloj');
@endphp

<div class="group relative flex flex-col h-full rounded-2xl bg-white dark:bg-[#0d1424] border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
    <a href="{{ $productUrl }}" class="w-full pt-[100%] relative block focus-visible:outline-none" aria-label="Ver {{ $cardTitle }}">
        <div class="absolute inset-0 flex items-center justify-center pt-1">
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 dark:from-[#0a0f1c] dark:to-[#1a2332]" @if($imageUrl) style="display:none" @endif>
                <span class="font-black text-slate-300 dark:text-slate-600 {{ $compact ? 'text-lg' : 'text-2xl' }} tracking-tighter">{{ $model }}</span>
                <span class="text-[8px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Invicta</span>
            </div>
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $product->title }}" class="absolute max-w-full max-h-full object-contain transition-transform duration-500 ease-out group-hover:scale-[1.04]" loading="{{ $priority ? 'eager' : 'lazy' }}" {{ $priority ? 'fetchpriority="high"' : '' }} onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';" />
            @endif
        </div>

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

        @if($product->video_uid)
        <div class="absolute {{ $compact ? 'bottom-1.5 left-1.5' : 'bottom-2 left-2' }} z-10">
            <span class="inline-flex items-center justify-center rounded-full bg-red-600 shadow-lg border border-white/20 {{ $compact ? 'w-5 h-5' : 'w-6 h-6 md:w-8 md:h-8' }}">
                <i class="fa-solid fa-play text-white {{ $compact ? 'text-[7px]' : 'text-[8px] md:text-[11px]' }} ml-0.5"></i>
            </span>
        </div>
        @endif
    </a>

    <div class="{{ $compact ? 'p-2' : 'p-3 md:p-4' }} flex flex-col flex-grow">
        <a href="{{ $productUrl }}" class="block focus-visible:outline-none" aria-label="Ver {{ $cardTitle }}">
            <h3 class="{{ $compact ? 'text-[10px]' : 'text-xs md:text-sm' }} w-full font-bold text-slate-700 dark:text-slate-100 leading-snug uppercase tracking-wide line-clamp-2 min-h-[2.75em] text-center group-hover:text-[#00a3d6] dark:group-hover:text-[#00C4FF] transition-colors">
                {{ $cardTitle }}
            </h3>
        </a>

        <div class="mt-2 flex flex-col items-center text-center">
            @if($product->proximo || $product->precio_venta <= 0)
                <div class="py-1">
                    <span class="text-[9px] md:text-xs font-bold px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md uppercase tracking-wide">Próximamente</span>
                </div>
            @elseif($product->precio_venta > 0)
                <div class="flex items-baseline gap-1.5 md:gap-2 justify-center">
                    @if(($product->descuento ?? 0) > 0)
                        <span class="text-[10px] md:text-sm font-bold text-slate-400 dark:text-gray-500 line-through">₡{{ number_format($product->precio_venta, 0) }}</span>
                    @endif
                    <span class="{{ $compact ? 'text-sm' : 'text-lg md:text-2xl' }} font-black text-red-600 dark:text-red-500 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                </div>
            @else
                <div class="py-1">
                    <span class="text-[9px] md:text-xs font-bold px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md uppercase tracking-wide">Agotado</span>
                </div>
            @endif
        </div>

        <div class="mt-auto pt-3">
            <a href="{{ $whatsappLink }}" data-cta="comprar-whatsapp" data-product-id="{{ $product->id }}" target="_blank" rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center gap-2 py-2 bg-[#00C4FF] hover:bg-[#00a3d6] text-[#0a0f1c] rounded-xl font-extrabold uppercase tracking-wide text-xs md:text-sm transition-all hover:-translate-y-0.5 active:scale-95 no-underline shadow-sm hover:shadow-md">
                <i class="fa-brands fa-whatsapp text-sm md:text-base"></i> Comprar
            </a>
        </div>
    </div>
</div>
