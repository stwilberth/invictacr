<x-app-layout title="Buscar: {{ $query }}" :q="$query">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-6">
            Resultados para "{{ $query }}"
        </h1>

        @if($products->count() > 0)
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
</x-app-layout>