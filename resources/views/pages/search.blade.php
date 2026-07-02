<x-app-layout title="Buscar Relojes | Invicta Costa Rica">
    <div class="bg-white py-4">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 p-3 md:p-4 rounded-xl shadow-sm">
                <form action="/buscar" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Buscar por modelo, colección, color..."
                            class="w-full px-3 py-2.5 bg-white dark:bg-white/10 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF]/50 focus:ring-2 focus:ring-[#00C4FF]/20 transition-all text-sm"
                        />
                    </div>
                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-[#00C4FF] hover:bg-[#00a0cc] text-white font-bold uppercase tracking-wider rounded-lg transition-all active:scale-95 text-sm flex items-center gap-1.5"
                    >
                        <i class="fa-solid fa-search"></i>
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(request()->filled('q'))
    <div class="bg-white py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-lg md:text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-4">
                Resultados para &quot;{{ request('q') }}&quot;
            </h2>

            @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
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
