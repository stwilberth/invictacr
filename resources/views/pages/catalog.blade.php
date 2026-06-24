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

            <div class="flex flex-col md:flex-row gap-8 pb-12" x-data="{ filterOpen: false }">

                {{-- Mobile filter trigger --}}
                <button @click="filterOpen = true" class="md:hidden flex items-center justify-center gap-2 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm px-4 py-3 font-black text-sm uppercase tracking-wider text-gray-700 dark:text-gray-200 active:scale-95 transition-all">
                    <i class="fa-solid fa-sliders text-[#00C4FF]"></i>
                    Filtros
                </button>

                {{-- Mobile drawer overlay --}}
                <div x-show="filterOpen" @click="filterOpen = false" class="md:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40" style="display:none;"></div>

                {{-- Mobile drawer --}}
                <aside
                    class="md:hidden fixed inset-y-0 left-0 z-50 w-[85%] max-w-sm bg-white dark:bg-gray-800 shadow-2xl overflow-y-auto p-4 pb-24"
                    x-show="filterOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    style="display:none;"
                >
                    <x-filter-form formId="filter-form-mobile" :showClose="true" />
                </aside>

                {{-- Desktop sidebar --}}
                <aside class="hidden md:block w-64 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 md:p-6 sticky top-24 relative">
                        <x-filter-form formId="filter-form" />
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
                        class="grid grid-cols-2 md:grid-cols-4 gap-4"
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
        // Close mobile filter drawer
        window.closeFilters = function() {
            const aside = document.querySelector('aside[x-show="filterOpen"]');
            if (aside && window.Alpine) {
                const el = aside.closest('[x-data]');
                if (el && el._x_dataStack) el._x_dataStack[0].filterOpen = false;
            }
            document.body.style.overflow = '';
        };

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

            // Lock body scroll when mobile filter drawer opens
            const filterAside = document.querySelector('aside[x-show="filterOpen"]');
            if (filterAside) {
                const observer = new MutationObserver(function() {
                    document.body.style.overflow = filterAside.style.display !== 'none' ? 'hidden' : '';
                });
                observer.observe(filterAside, { attributes: true, attributeFilter: ['style'] });
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
