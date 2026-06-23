<x-app-layout :title="'Relojes Invicta ' . ($gender ? ucfirst($gender) : 'Originales')" :description="'Explora nuestra colección de relojes Invicta ' . ($gender ? 'para ' . $gender : 'originales') . '. Envío gratis en GAM.'">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <aside class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-slate-100 dark:border-white/5 p-4 md:p-6 sticky top-24">
                    <h3 class="font-black text-sm uppercase tracking-wider text-gray-900 dark:text-white mb-4">Filtros</h3>
                    <form method="GET" action="{{ url()->current() }}" id="filter-form">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}" />
                        @endif

                        @if($filters['colors']->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Color</h4>
                            <div class="space-y-1">
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                    <input type="radio" name="color" value="" {{ !request('color') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                    <span>Todos</span>
                                </label>
                                @foreach($filters['colors'] as $color)
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" {{ request('color') === $color ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                    <span>{{ ucfirst($color) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($filters['brazaletes']->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Brazalete</h4>
                            <div class="space-y-1">
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                    <input type="radio" name="brazalete" value="" {{ !request('brazalete') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                    <span>Todos</span>
                                </label>
                                @foreach($filters['brazaletes'] as $brazalete)
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                    <input type="radio" name="brazalete" value="{{ $brazalete }}" {{ request('brazalete') === $brazalete ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                    <span>{{ ucfirst($brazalete) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </aside>

            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                            Relojes Invicta {{ $gender ? 'para ' . ucfirst($gender) : 'Originales' }}
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $products->total() }} productos encontrados
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordenar:</label>
                        <select name="sort" onchange="window.location.href = this.value" class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50">
                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>Más nuevos</option>
                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Menor precio</option>
                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Mayor precio</option>
                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'name_asc'])) }}" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'name_desc'])) }}" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </div>
                </div>

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
                    <h3 class="text-xl font-bold text-gray-500 dark:text-gray-400">No se encontraron productos</h3>
                    <p class="text-gray-400 dark:text-gray-500 mt-2">Intenta con otros filtros o categorías</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>