<x-app-layout :title="$product->title" :description="$product->descripcion ?? 'Reloj Invicta ' . $product->modelo" :ogImage="$product->imagen" ogType="product">
    @php
        $priceAfterDiscount = $product->precio_venta * (1 - ($product->descuento ?? 0) / 100);
        $whatsappBuy = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Me interesa comprar el reloj Invicta {$product->modelo}: " . url()->current());
        $whatsappApartar = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! Me gustaría APARTAR el reloj Invicta {$product->modelo}: " . url()->current());
        $whatsappVideo = 'https://wa.me/50686711422?text=' . urlencode("¡Hola! ¿Me podrías enviar un video real del Invicta {$product->modelo}? " . url()->current());
    @endphp

    <div class="max-w-7xl mx-auto px-4 py-8">
        <nav class="flex mb-6 text-sm text-gray-500 dark:text-gray-400">
            <a href="/" class="hover:text-[#00C4FF]">Inicio</a>
            <span class="mx-2">/</span>
            <a href="/relojes" class="hover:text-[#00C4FF]">Relojes</a>
            @if($product->genero)
            <span class="mx-2">/</span>
            <a href="/relojes/{{ $product->genero }}" class="hover:text-[#00C4FF]">{{ ucfirst($product->genero) }}</a>
            @endif
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white font-medium">{{ $product->modelo }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            <div class="space-y-4">
                <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
                    <div class="aspect-square flex items-center justify-center p-8">
                        <img src="{{ $product->imagen }}" alt="{{ $product->title }}" class="max-w-full max-h-full object-contain" id="main-image" loading="eager" />
                    </div>
                </div>

                @if($images->count() > 1)
                <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
                    @foreach($images as $index => $image)
                    <button onclick="document.getElementById('main-image').src = '{{ $image }}'; document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('border-[#00C4FF]')); this.classList.add('border-[#00C4FF]')"
                            class="thumb-btn flex-shrink-0 w-20 h-20 rounded-xl border-2 {{ $index === 0 ? 'border-[#00C4FF]' : 'border-transparent' }} bg-white dark:bg-[#0f172a] overflow-hidden hover:border-[#00C4FF]/50 transition-colors">
                        <img src="{{ $image }}" alt="" class="w-full h-full object-contain" loading="lazy" />
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                        Reloj Invicta {{ $product->coleccion }} {{ $product->modelo }}
                    </h1>
                    @if($product->color)
                    <p class="text-gray-500 dark:text-gray-400 mt-1">{{ ucfirst($product->color) }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    @if($product->size)
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-slate-100 dark:bg-white/5 px-3 py-1.5 rounded-lg">{{ $product->size }}MM</span>
                    @endif
                    @if($product->tipo_movimiento)
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-slate-100 dark:bg-white/5 px-3 py-1.5 rounded-lg uppercase">{{ $product->tipo_movimiento }}</span>
                    @endif
                    @if($product->brazalete)
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-slate-100 dark:bg-white/5 px-3 py-1.5 rounded-lg uppercase">{{ $product->brazalete }}</span>
                    @endif
                    @if($product->genero)
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-slate-100 dark:bg-white/5 px-3 py-1.5 rounded-lg uppercase">{{ $product->genero }}</span>
                    @endif
                </div>

                <div class="flex items-baseline gap-3">
                    @if($product->precio_venta > 0)
                        <span class="text-4xl font-black text-red-600 dark:text-red-500">₡{{ number_format($priceAfterDiscount, 0) }}</span>
                        @if(($product->descuento ?? 0) > 0)
                            <span class="text-lg font-bold text-gray-400 dark:text-gray-500 line-through">₡{{ number_format($product->precio_venta, 0) }}</span>
                            <span class="text-sm font-bold text-green-600 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-lg">-{{ $product->descuento }}%</span>
                        @endif
                    @else
                        <span class="text-xl font-bold px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg">Próximamente</span>
                    @endif
                </div>

                @if($product->stock > 0)
                <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                    <i class="fa-solid fa-circle text-[8px]"></i>
                    <span class="font-bold">En stock - Entrega inmediata en GAM</span>
                </div>
                @endif

                @if($product->descripcion)
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wider text-sm">Descripción</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">{{ $product->descripcion }}</p>
                </div>
                @endif

                <div class="space-y-3">
                    <a href="{{ $whatsappBuy }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-3 w-full bg-[#25D366] hover:bg-[#20bd5a] text-white font-black text-sm uppercase tracking-wider px-8 py-4 rounded-xl transition-all duration-300 active:scale-95 shadow-lg">
                        <i class="fab fa-whatsapp text-xl"></i>
                        <span>Comprar por WhatsApp</span>
                    </a>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ $whatsappApartar }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-[#0a0f1c] border-2 border-[#00C4FF] text-[#00C4FF] hover:bg-[#00C4FF]/10 font-bold text-xs uppercase tracking-wider px-4 py-3 rounded-xl transition-all duration-300 active:scale-95">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>Apartar</span>
                        </a>
                        <a href="{{ $whatsappVideo }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-[#0a0f1c] border-2 border-white/10 text-white/80 hover:text-white hover:border-white/20 font-bold text-xs uppercase tracking-wider px-4 py-3 rounded-xl transition-all duration-300 active:scale-95">
                            <i class="fa-solid fa-video"></i>
                            <span>Ver Video</span>
                        </a>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-white/5 pt-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @if($product->caja)
                        <div>
                            <span class="text-gray-400 dark:text-gray-500">Material de caja</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $product->caja }}</p>
                        </div>
                        @endif
                        @if($product->resistencia_agua)
                        <div>
                            <span class="text-gray-400 dark:text-gray-500">Resistencia al agua</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $product->resistencia_agua }}</p>
                        </div>
                        @endif
                        @if($product->coleccion)
                        <div>
                            <span class="text-gray-400 dark:text-gray-500">Colección</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $product->coleccion }}</p>
                        </div>
                        @endif
                        @if($product->modelo)
                        <div>
                            <span class="text-gray-400 dark:text-gray-500">Modelo</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $product->modelo }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <section class="bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-6">Productos Relacionados</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <script>
        var pixelModel = "{{ $product->modelo }}";
        var pixelTitle = "{{ $product->title }}";
        var pixelPrice = {{ $product->precio_venta }};
    </script>
</x-app-layout>