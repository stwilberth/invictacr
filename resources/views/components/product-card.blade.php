@props(['product', 'compact' => false])
@php
    $productUrl = route('products.show', ['slug' => $product->slug]);
    $whatsappLink = 'https://wa.me/50686711422?text=' . urlencode("Hola, me interesa el reloj Invicta {$product->modelo}: " . url($productUrl));
    $priceAfterDiscount = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);
    $model = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
    $imagePath = "images/relojes/{$model}.jpg";

    $thumbUrl = null;
    if ($product->imagen && str_starts_with($product->imagen, '/storage/relojes/')) {
        $basename = basename($product->imagen);
        $thumbModelo = pathinfo($basename, PATHINFO_FILENAME);
        $thumbCandidate = public_path("storage/relojes/thumbs/{$thumbModelo}.webp");
        if (file_exists($thumbCandidate)) {
            $thumbUrl = "/storage/relojes/thumbs/{$thumbModelo}.webp";
        }
    } elseif (file_exists(public_path("storage/relojes/thumbs/{$model}.webp"))) {
        $thumbUrl = "/storage/relojes/thumbs/{$model}.webp";
    }

    $imageUrl = null;
    if ($thumbUrl) {
        $imageUrl = $thumbUrl;
    } elseif ($product->imagen && !str_starts_with($product->imagen, 'http')) {
        $imageUrl = $product->imagen;
    } elseif (file_exists(public_path("storage/relojes/{$model}.jpg"))) {
        $imageUrl = asset("storage/relojes/{$model}.jpg");
    } elseif (file_exists(public_path($imagePath))) {
        $imageUrl = asset($imagePath);
    }
@endphp

<div class="group relative flex flex-col h-full bg-white dark:bg-[#0f172a] rounded-2xl border border-slate-100 dark:border-white/5 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 overflow-hidden">
    <a href="{{ $productUrl }}" class="w-full pt-[100%] relative block">
        <div class="absolute inset-0 flex items-center justify-center pt-1">
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 dark:from-[#0a0f1c] dark:to-[#1a2332]" @if($imageUrl) style="display:none" @endif>
                <span class="font-black text-slate-300 dark:text-slate-600 {{ $compact ? 'text-lg' : 'text-2xl' }} tracking-tighter">{{ $model }}</span>
                <span class="text-[8px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Invicta</span>
            </div>
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $product->title }}" class="absolute max-w-full max-h-full object-contain" loading="lazy" onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';" />
            @endif
        </div>

        @if(($product->descuento ?? 0) > 0 && $product->precio_venta > 0)
        <div class="absolute {{ $compact ? 'top-1 left-1' : 'top-1 left-1 md:top-2 md:left-2' }} z-10">
            <span class="inline-flex items-center rounded-full bg-red-600 {{ $compact ? 'px-1 py-0.5 text-[8px]' : 'px-1 py-0.5 text-[8px] md:px-2 md:py-1 md:text-[10px]' }} font-black text-white shadow-lg border border-white/10">
                -{{ $product->descuento }}%
            </span>
        </div>
        @endif


    </a>

    <div class="{{ $compact ? 'p-1' : 'p-1 md:p-2' }} flex flex-col flex-grow bg-slate-50 dark:bg-[#0a0f1c]/50">
        <a href="{{ $productUrl }}" class="{{ $compact ? 'mb-1' : 'mb-1 md:mb-3' }} flex-grow block hover:text-blue-600 transition-colors">
            <h3 class="{{ $compact ? 'text-[10px] h-6' : 'text-[10px] h-6 md:text-sm md:h-10' }} font-black text-slate-800 dark:text-white line-clamp-2 leading-tight uppercase tracking-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                Reloj Invicta {{ $product->coleccion && strtolower($product->coleccion) !== 'otros' ? $product->coleccion : '' }} {{ $product->modelo }}
            </h3>
        </a>
        <div class="flex items-center gap-1 {{ $compact ? 'mb-1' : 'mb-1 md:gap-2 md:mb-3' }}">
            @if($product->size)
            <span class="text-[8px] md:text-[10px] font-bold text-slate-500 dark:text-gray-400 bg-slate-200/50 dark:bg-white/5 px-1 md:px-2 py-0.5 rounded-md">{{ preg_replace('/\s*mm$/i', '', $product->size) }}MM</span>
            @endif
            @if($product->tipo_movimiento)
            <span class="text-[8px] md:text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">{{ $product->tipo_movimiento }}</span>
            @endif
            <span class="text-[8px] md:text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">{{ $product->genero }}</span>
        </div>

        <div class="{{ $compact ? 'my-0.5' : 'my-0.5 md:my-2' }} flex items-center justify-between gap-1">
            @if($product->video)
            <button type="button" onclick="openVimeoModal('{{ $product->video }}')" title="Ver video" class="inline-flex items-center gap-0.5 md:gap-1 {{ $compact ? 'px-1 py-0.5 text-[7px]' : 'px-1 py-0.5 md:px-2 md:py-1 text-[7px] md:text-[9px]' }} font-black uppercase tracking-wide text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors shadow-sm">
                <i class="fa-solid fa-play"></i> Ver Video
            </button>
            @endif
            <div class="ml-auto text-right">
            @if($product->proximo && ($product->stock ?? 0) > 0)
                <div class="py-1">
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md">Próximamente</span>
                </div>
            @elseif($product->proximo || $product->precio_venta <= 0)
                <div class="py-1 flex items-center justify-end gap-1">
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-red-100 text-red-600 rounded-md">AGOTADO</span>
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md">PRÓXIMO</span>
                </div>
            @elseif($product->precio_venta > 0)
                <div class="flex flex-col items-end w-full">
                    <div class="flex items-baseline gap-1 md:gap-2">
                        <span class="{{ $compact ? 'text-sm' : 'text-sm md:text-xl' }} font-black text-red-600 dark:text-red-500 tracking-tighter">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                        @if(($product->descuento ?? 0) > 0)
                            <span class="text-[9px] md:text-xs font-bold text-slate-400 dark:text-gray-500 line-through">₡{{ number_format($product->precio_venta, 0) }}</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="py-1 text-right">
                    <span class="text-[9px] md:text-xs font-bold px-1.5 md:px-2 py-0.5 bg-red-100 text-red-600 rounded-md">AGOTADO</span>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
