@props(['product', 'compact' => false, 'priority' => false])
@php
    $productUrl = route('products.show', ['slug' => $product->slug]);
    $whatsappLink = 'https://wa.me/50686711422?text=' . urlencode("Hola, me interesa el reloj Invicta {$product->modelo}: " . url($productUrl));
    $priceAfterDiscount = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);
    $model = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
    $cdnBase = 'https://cdn.invictacostarica.com';

    $thumbUrl = $product->imagen ? "{$cdnBase}/relojes/thumbs/{$model}.webp" : null;
    $imageUrl = $thumbUrl ?? $product->imagen;
@endphp

<div class="group relative flex flex-col h-full transition-all duration-300 overflow-hidden">
    <a href="{{ $productUrl }}" class="w-full pt-[100%] relative block">
        <div class="absolute inset-0 flex items-center justify-center pt-1">
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 dark:from-[#0a0f1c] dark:to-[#1a2332]" @if($imageUrl) style="display:none" @endif>
                <span class="font-black text-slate-300 dark:text-slate-600 {{ $compact ? 'text-lg' : 'text-2xl' }} tracking-tighter">{{ $model }}</span>
                <span class="text-[8px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Invicta</span>
            </div>
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $product->title }}" class="absolute max-w-full max-h-full object-contain" loading="{{ $priority ? 'eager' : 'lazy' }}" {{ $priority ? 'fetchpriority="high"' : '' }} onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';" />
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


    </a>

    <div class="{{ $compact ? 'p-1' : 'p-1 md:p-2' }} flex flex-col flex-grow">
        <a href="{{ $productUrl }}" class="block hover:text-blue-600 transition-colors">
            <h3 class="{{ $compact ? 'text-[10px]' : 'text-[10px] md:text-xs' }} font-bold text-slate-600 dark:text-gray-200 leading-tight uppercase tracking-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors text-center">
                {{ $product->title }}
            </h3>
        </a>

        <div class="text-center">
            @if($product->proximo && ($product->stock ?? 0) > 0)
                <div class="py-1">
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md">Próximamente</span>
                </div>
            @elseif($product->proximo || $product->precio_venta <= 0)
                <div class="py-1 flex items-center justify-center gap-1">
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-red-100 text-red-600 rounded-md">AGOTADO</span>
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md">PRÓXIMO</span>
                </div>
            @elseif($product->precio_venta > 0)
                <div class="flex flex-col items-center w-full">
                    <div class="flex items-baseline gap-1 md:gap-2 justify-center">
                        <span class="{{ $compact ? 'text-sm' : 'text-sm md:text-xl' }} font-black text-red-600 dark:text-red-500 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                        @if(($product->descuento ?? 0) > 0)
                            <span class="text-[9px] md:text-xs font-bold text-slate-400 dark:text-gray-500 line-through">₡{{ number_format($product->precio_venta, 0) }}</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="py-1 text-center">
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-red-100 text-red-600 rounded-md">AGOTADO</span>
                </div>
            @endif
        </div>

        <div class="mt-1 flex items-center justify-center gap-1">
            @if($product->video)
            <button type="button" onclick="openVimeoModal('{{ $product->video }}')" title="Ver video" class="inline-flex items-center gap-0.5 md:gap-1 {{ $compact ? 'px-1 py-0.5 text-[7px]' : 'px-1 py-0.5 md:px-2 md:py-1 text-[7px] md:text-[9px]' }} uppercase tracking-wide text-red-600 border border-red-600 hover:bg-red-600 hover:text-white rounded-md transition-colors">
                <i class="fa-solid fa-play"></i> Ver Video
            </button>
            @endif
        </div>
    </div>
</div>
