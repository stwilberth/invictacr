<x-app-layout title="Buscar Relojes | Invicta Costa Rica">
    <div class="min-h-[70vh] bg-[#0a0f1c] relative overflow-hidden pt-24 pb-12">
        {{-- Background Decorative Elements --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-blue-500/5 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-[#00C4FF]/5 blur-[120px] rounded-full"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4">
            <x-page-title
                title="Busca tu Invicta"
                highlight="Invicta"
                subtitle="Encuentra el modelo perfecto entre nuestra colección exclusiva y llévatelo hoy mismo."
            />

            <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-4 md:p-8 rounded-[2.5rem] shadow-2xl">
                <form action="/relojes" method="GET" class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/40 text-lg"></i>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Escribe el modelo (ej: 3044), color o colección..."
                            class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/10 rounded-2xl text-white placeholder-white/40 focus:outline-none focus:border-[#00C4FF]/50 focus:ring-2 focus:ring-[#00C4FF]/20 transition-all text-lg"
                        />
                    </div>
                    <button
                        type="submit"
                        class="px-8 py-4 bg-[#00C4FF] hover:bg-[#00a0cc] text-white font-bold uppercase tracking-wider rounded-2xl transition-all active:scale-95 flex items-center gap-2"
                    >
                        <i class="fa-solid fa-search"></i>
                        Buscar
                    </button>
                </form>

                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 rounded-2xl bg-white/5 border border-white/5">
                        <i class="fa-solid fa-magnifying-glass text-[#00C4FF] text-xl mb-2"></i>
                        <h3 class="text-white font-bold text-xs uppercase tracking-wider">Búsqueda Rápida</h3>
                    </div>
                    <div class="text-center p-4 rounded-2xl bg-white/5 border border-white/5">
                        <i class="fa-solid fa-bolt text-[#00C4FF] text-xl mb-2"></i>
                        <h3 class="text-white font-bold text-xs uppercase tracking-wider">Resultados en Vivo</h3>
                    </div>
                    <div class="text-center p-4 rounded-2xl bg-white/5 border border-white/5">
                        <i class="fa-solid fa-image text-[#00C4FF] text-xl mb-2"></i>
                        <h3 class="text-white font-bold text-xs uppercase tracking-wider">Vista Previa</h3>
                    </div>
                    <div class="text-center p-4 rounded-2xl bg-white/5 border border-white/5">
                        <i class="fa-solid fa-check-double text-[#00C4FF] text-xl mb-2"></i>
                        <h3 class="text-white font-bold text-xs uppercase tracking-wider">Stock Real</h3>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center">
                <h2 class="text-white/40 text-sm font-bold uppercase tracking-[0.3em] mb-8">Búsquedas Populares</h2>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach(['Pro Diver', 'Speedway', 'S1 Rally', 'Automatico', 'Mujer', 'Hombre', 'Angel', 'Reserve'] as $term)
                    <a
                        href="/relojes?q={{ urlencode($term) }}"
                        class="px-6 py-2 rounded-full bg-white/5 border border-white/10 text-white/80 hover:text-[#00C4FF] hover:border-[#00C4FF]/50 transition-all duration-300 text-sm font-medium"
                    >
                        {{ $term }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if(request()->filled('q'))
    <div class="bg-white dark:bg-[#0a0f1c] py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-8">
                Resultados para &quot;{{ request('q') }}&quot;
            </h2>

            @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-20">
                <i class="fa-solid fa-search text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-500 dark:text-gray-400">No se encontraron resultados</h3>
                <p class="text-gray-400 dark:text-gray-500 mt-2">Intenta con otros términos de búsqueda</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</x-app-layout>
