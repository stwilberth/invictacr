@props(['product'])
@php
    $model = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
    $productUrl = route('products.show', [
        'slug' => $product->slug,
    ]);
    $priceAfterDiscount = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);
    $genderLabel = match (strtolower($product->genero ?? '')) {
        'hombre' => 'Hombre',
        'mujer' => 'Mujer',
        default => 'Unisex',
    };
    $cdnBase = 'https://cdn.invictacostarica.com';
    $imageUrl = $product->imagen ?? asset("images/relojes/{$model}.jpg");
@endphp

<a
    href="{{ $productUrl }}"
    class="group relative flex flex-col w-full aspect-[4/5] bg-slate-950 rounded-2xl overflow-hidden border border-white/5 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/20 block"
>
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img
            src="{{ $imageUrl }}"
            alt="{{ $product->title }}"
            class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110"
            loading="lazy"
            decoding="async"
        />
    </div>

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/95 via-black/40 to-transparent opacity-90 group-hover:opacity-80 transition-opacity duration-500"></div>

    <!-- Gender Badge -->
    <div class="absolute top-2 left-2 z-20">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-white/15 backdrop-blur-md text-white/80 px-2 py-0.5 rounded-full border border-white/10">
            {{ $genderLabel }}
        </span>
    </div>

    <!-- Content Overlay -->
    <div class="absolute inset-0 z-20 p-3 sm:p-4 flex flex-col justify-end">
        <div class="flex items-end justify-between transform transition-transform duration-500 group-hover:-translate-y-1">
            <div class="flex flex-col">
                <span class="text-sm sm:text-base font-black text-white">
                    ₡{{ number_format($priceAfterDiscount, 0) }}
                </span>
                @if(($product->descuento ?? 0) > 0)
                    <span class="text-[9px] sm:text-[10px] font-bold text-white/40 line-through">
                        ₡{{ number_format($product->precio_venta, 0) }}
                    </span>
                @endif
            </div>

            @if(($product->descuento ?? 0) > 0)
                <div class="bg-red-600 text-white text-[10px] sm:text-[12px] font-black px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-lg shadow-lg">
                    -{{ $product->descuento }}%
                </div>
            @endif
        </div>
    </div>

    <!-- Border Glow -->
    <div class="absolute inset-0 border border-white/10 rounded-2xl z-30 group-hover:border-blue-500/50 transition-colors duration-500 pointer-events-none"></div>
</a>
