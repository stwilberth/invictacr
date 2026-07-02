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
                :subtitle="''"
            />

            <div class="max-w-2xl mx-auto mb-6 -mt-2">
                <form action="/relojes" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <input
                            type="text"
                            name="q"
                            value="{{ $searchQuery ?? request('q') }}"
                            placeholder="Buscar por modelo, colección, color..."
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF]/50 focus:ring-2 focus:ring-[#00C4FF]/20 transition-all text-sm pr-10"
                        />
                        @if($searchQuery ?? request('q'))
                        <button type="button" onclick="window.location.href='/relojes'" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        @endif
                    </div>
                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-[#00C4FF] hover:bg-[#00a0cc] text-white font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 text-sm flex items-center gap-1.5"
                    >
                        <i class="fa-solid fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="pb-12">
                    {{-- Header with count and sorting --}}
                    {{--
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
                    --}}

                    @php
                        $hasAnyFilter = request()->anyFilled(['gender', 'color', 'brazalete', 'coleccion', 'tipo_movimiento', 'caja', 'resistencia_agua', 'size', 'precio_min', 'precio_max', 'q'])
                            || (request('sort') && request('sort') !== 'newest');
                    @endphp

                    @if($searchQuery)
                    <div class="mb-4 flex justify-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                            {{ $products->total() }} {{ $products->total() === 1 ? 'resultado' : 'resultados' }} para la búsqueda <strong class="text-gray-800 dark:text-white">"{{ $searchQuery }}"</strong>
                        </span>
                    </div>
                    @elseif($hasAnyFilter)
                    <div class="mb-4 flex justify-center">
                        <span class="text-xs text-gray-400 font-medium">{{ $products->total() }} resultados</span>
                    </div>
                    @endif

                    {{-- Products grid --}}
                    @if($products->count() > 0)
                    <div
                        id="products-grid"
                        class="grid grid-cols-2 md:grid-cols-5 gap-4"
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
                        <i class="fa-regular fa-face-frown text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No se encontraron resultados para esta búsqueda</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Intenta con otros términos o corrige la ortografía</p>
                    </div>
                    @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
