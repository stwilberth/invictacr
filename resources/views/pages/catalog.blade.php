{{-- If partial request, only render the product cards for infinite scroll --}}
@if(request('partial'))
@foreach($products as $product)
    <x-product-card :product="$product" />
@endforeach
@else

<x-app-layout :title="'Relojes Invicta ' . ($gender ? ucfirst($gender) : 'Originales')" :description="'Explora nuestra colección de relojes Invicta ' . ($gender ? 'para ' . $gender : 'originales') . '. Envío gratis en GAM.'">
    <div class="bg-white dark:bg-[#0a0f1c]">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title
                :title="'Relojes Invicta' . ($gender ? ' para ' . ucfirst($gender) : '')"
                :highlight="$gender ? ucfirst($gender) : null"
                :subtitle="'Descubre los mejores relojes Invicta ' . ($gender ? 'para ' . $gender : 'originales') . '. ' . $products->total() . ' modelos con los mejores precios.'"
            />

            <div class="flex flex-col md:flex-row gap-8 pb-12">
                <aside class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 md:p-6 sticky top-24">
                        <h3 class="font-black text-sm uppercase tracking-wider text-gray-900 dark:text-white mb-4">
                            <i class="fa-solid fa-sliders text-[#00C4FF] mr-2"></i>Filtros
                        </h3>
                        <form method="GET" action="{{ url()->current() }}" id="filter-form">
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}" />
                            @endif

                            {{-- Gender filter --}}
                            @if(!$gender)
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Género</h4>
                                <div class="space-y-1">
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="gender" value="" {{ !request('gender') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>Todos</span>
                                    </label>
                                    @foreach(['hombre', 'mujer', 'unisex'] as $g)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="gender" value="{{ $g }}" {{ (request('gender') ?: $gender) === $g ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>{{ ucfirst($g) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Color filter --}}
                            @if($filters['colors']->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Color</h4>
                                <div class="space-y-1 max-h-40 overflow-y-auto">
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

                            {{-- Brazalete filter --}}
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

                            {{-- Colección filter --}}
                            @if(($filters['colecciones'] ?? collect())->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Colección</h4>
                                <div class="space-y-1 max-h-40 overflow-y-auto">
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="coleccion" value="" {{ !request('coleccion') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>Todas</span>
                                    </label>
                                    @foreach($filters['colecciones'] as $coleccion)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="coleccion" value="{{ $coleccion }}" {{ request('coleccion') === $coleccion ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>{{ $coleccion }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Tipo de movimiento filter --}}
                            @if(($filters['movimientos'] ?? collect())->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Movimiento</h4>
                                <div class="space-y-1">
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="tipo_movimiento" value="" {{ !request('tipo_movimiento') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>Todos</span>
                                    </label>
                                    @foreach($filters['movimientos'] as $mov)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="tipo_movimiento" value="{{ $mov }}" {{ request('tipo_movimiento') === $mov ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>{{ $mov === 'cuarzo' ? 'Batería' : ucfirst($mov) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Size filter --}}
                            @if(($filters['sizes'] ?? collect())->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tamaño</h4>
                                <div class="space-y-1">
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="size" value="" {{ !request('size') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>Todos</span>
                                    </label>
                                    @foreach($filters['sizes'] as $size)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="size" value="{{ $size }}" {{ request('size') === $size ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-[#00C4FF]">
                                        <span>{{ $size }}MM</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Price range filter --}}
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Precio</h4>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="precio_min" placeholder="Desde" value="{{ request('precio_min') }}" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-gray-600 rounded-lg text-xs px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50" onchange="document.getElementById('filter-form').submit()" />
                                    <span class="text-gray-400 text-xs">-</span>
                                    <input type="number" name="precio_max" placeholder="Hasta" value="{{ request('precio_max') }}" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-gray-600 rounded-lg text-xs px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50" onchange="document.getElementById('filter-form').submit()" />
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#00C4FF] hover:bg-[#00b3e6] text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all duration-300 active:scale-95 shadow-md">
                                <i class="fa-solid fa-filter mr-1"></i> Filtrar
                            </button>
                        </form>
                    </div>
                </aside>

                <div class="flex-1 min-w-0">
                    {{-- Header with count and sorting --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                <span class="font-bold text-gray-900 dark:text-white">{{ $products->total() }}</span> productos encontrados
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordenar:</label>
                            <select name="sort" onchange="window.location.href = this.value" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 text-gray-700 dark:text-gray-200">
                                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort', 'page'), ['sort' => 'newest'])) }}" {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>Más nuevos</option>
                                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort', 'page'), ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Menor precio</option>
                                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort', 'page'), ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Mayor precio</option>
                                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort', 'page'), ['sort' => 'name_asc'])) }}" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>A-Z</option>
                                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('sort', 'page'), ['sort' => 'name_desc'])) }}" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </div>
                    </div>

                    {{-- Active filter chips --}}
                    @php
                        $activeFilters = [];
                        if (request('color')) $activeFilters[] = ['key' => 'color', 'label' => 'Color: ' . ucfirst(request('color'))];
                        if (request('brazalete')) $activeFilters[] = ['key' => 'brazalete', 'label' => 'Brazalete: ' . ucfirst(request('brazalete'))];
                        if (request('coleccion')) $activeFilters[] = ['key' => 'coleccion', 'label' => 'Colección: ' . request('coleccion')];
                        if (request('tipo_movimiento')) $activeFilters[] = ['key' => 'tipo_movimiento', 'label' => 'Movimiento: ' . (request('tipo_movimiento') === 'cuarzo' ? 'Batería' : ucfirst(request('tipo_movimiento')))];
                        if (request('size')) $activeFilters[] = ['key' => 'size', 'label' => 'Tamaño: ' . request('size') . 'mm'];
                        if (request('precio_min')) $activeFilters[] = ['key' => 'precio_min', 'label' => 'Desde: ₡' . number_format((int)request('precio_min'), 0)];
                        if (request('precio_max')) $activeFilters[] = ['key' => 'precio_max', 'label' => 'Hasta: ₡' . number_format((int)request('precio_max'), 0)];
                        if (request('sort') && request('sort') !== 'newest') {
                            $sortLabels = ['price_asc' => 'Menor precio', 'price_desc' => 'Mayor precio', 'name_asc' => 'A-Z', 'name_desc' => 'Z-A'];
                            $activeFilters[] = ['key' => 'sort', 'label' => 'Orden: ' . ($sortLabels[request('sort')] ?? request('sort'))];
                        }
                        if (request('q')) $activeFilters[] = ['key' => 'q', 'label' => 'Búsqueda: "' . request('q') . '"'];
                        $hasActiveFilters = count($activeFilters) > 0;
                    @endphp

                    @if($hasActiveFilters)
                    <div class="flex flex-wrap items-center gap-1.5 mb-4">
                        @foreach($activeFilters as $filter)
                        <div class="inline-flex items-center bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded-full text-xs font-medium border border-blue-200 dark:border-blue-800">
                            <span>{{ $filter['label'] }}</span>
                            <button
                                onclick="removeFilter('{{ $filter['key'] }}')"
                                class="ml-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 focus:outline-none"
                                title="Remover filtro: {{ $filter['label'] }}"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <button onclick="clearAllFilters()" class="text-xs text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 underline">
                            Limpiar todos los filtros
                        </button>
                    </div>
                    @endif

                    {{-- Products grid --}}
                    @if($products->count() > 0)
                    <div
                        id="products-grid"
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4"
                        data-current-page="{{ $products->currentPage() }}"
                        data-total-pages="{{ $products->lastPage() }}"
                    >
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    {{-- Infinite scroll sentinel --}}
                    <div id="infinite-scroll-sentinel" class="mt-8 mb-6"></div>

                    {{-- Pagination (kept for SEO crawlers) --}}
                    @if($products->lastPage() > 1)
                    <nav id="pagination-nav" class="flex items-center justify-center gap-1 sm:gap-2 mt-10 mb-6 flex-wrap" aria-label="Paginación">
                        {{-- Previous --}}
                        @if($products->previousPageUrl())
                        <a href="{{ $products->previousPageUrl() }}" class="inline-flex items-center justify-center h-10 min-w-10 px-2 sm:px-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:border-blue-400 dark:hover:bg-gray-700 shadow-sm transition-all" aria-label="Página anterior">
                            <span class="inline">← Anterior</span>
                        </a>
                        @endif

                        {{-- Page numbers --}}
                        @for($pageNum = 1; $pageNum <= $products->lastPage(); $pageNum++)
                            @if($pageNum == $products->currentPage())
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg text-sm font-bold shadow-md border border-blue-700" aria-current="page">{{ $pageNum }}</span>
                            @else
                            <a href="{{ $products->url($pageNum) }}" class="inline-flex items-center justify-center w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:border-blue-400 dark:hover:bg-gray-700 rounded-lg text-sm font-semibold shadow-sm transition-all">{{ $pageNum }}</a>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if($products->nextPageUrl())
                        <a href="{{ $products->nextPageUrl() }}" class="inline-flex items-center justify-center h-10 min-w-10 px-2 sm:px-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:border-blue-400 dark:hover:bg-gray-700 shadow-sm transition-all" aria-label="Página siguiente">
                            <span class="inline">Siguiente →</span>
                        </a>
                        @endif
                    </nav>
                    @endif
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
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            initFilterButtons();

            function initFilterButtons() {
                window.removeFilter = function(filterKey) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete(filterKey);
                    if (filterKey === 'sort') {
                        url.searchParams.set('sort', 'newest');
                    }
                    window.location.href = url.pathname + url.search;
                };

                window.clearAllFilters = function() {
                    window.location.href = window.location.pathname;
                };
            }

            initInfiniteScroll();

            function initInfiniteScroll() {
                const grid = document.getElementById("products-grid");
                const sentinel = document.getElementById("infinite-scroll-sentinel");
                const paginationNav = document.getElementById("pagination-nav");

                if (!grid || !sentinel) return;

                const currentPage = parseInt(grid.dataset.currentPage || "1");
                const totalPages = parseInt(grid.dataset.totalPages || "1");

                if (totalPages <= 1) return;

                let page = currentPage;
                let isLoading = false;
                let allLoaded = false;

                // Hide pagination visually once infinite scroll takes over
                if (paginationNav) {
                    setTimeout(function() {
                        paginationNav.style.display = "none";
                    }, 100);
                }

                const observer = new IntersectionObserver(async function(entries) {
                    for (const entry of entries) {
                        if (entry.isIntersecting && !isLoading && !allLoaded) {
                            isLoading = true;

                            sentinel.innerHTML = '<div class="flex justify-center py-4"><div class="w-8 h-8 border-2 border-[#00C4FF] border-t-transparent rounded-full animate-spin"></div></div>';

                            page++;

                            if (page > totalPages) {
                                allLoaded = true;
                                sentinel.innerHTML = '<p class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">— Has visto todos los relojes —</p>';
                                observer.unobserve(sentinel);
                                isLoading = false;
                                return;
                            }

                            try {
                                const url = new URL(window.location.href);
                                url.searchParams.set("page", String(page));

                                const fetchUrl = new URL(url.toString());
                                fetchUrl.searchParams.set("partial", "true");

                                const res = await fetch(fetchUrl.toString());

                                if (!res.ok) throw new Error("Failed to fetch");

                                const html = await res.text();

                                grid.insertAdjacentHTML("beforeend", html);

                                window.history.replaceState(null, "", url.pathname + url.search);

                                sentinel.innerHTML = "";
                            } catch (e) {
                                console.error("Infinite scroll error:", e);
                                sentinel.innerHTML = '<p class="text-center text-sm text-red-500 py-4">Error al cargar más productos</p>';
                            }

                            isLoading = false;
                        }
                    }
                }, {
                    rootMargin: "400px",
                    threshold: 0,
                });

                observer.observe(sentinel);
            }
        });
    </script>
    @endpush
</x-app-layout>
@endif
