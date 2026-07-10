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
                <form action="/relojes" method="GET" class="flex gap-2" onsubmit="this.querySelector('button[type=submit]').disabled = true">
                    <div class="relative flex-1">
                        <input
                            id="live-search-input"
                            type="text"
                            name="q"
                            value="{{ $searchQuery ?? request('q') }}"
                            placeholder="Escribí un modelo o colección..."
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF]/50 focus:ring-2 focus:ring-[#00C4FF]/20 transition-all text-sm pr-10"
                            autocomplete="off"
                        />
                        <button type="button" onclick="clearLiveSearch()" id="live-search-clear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" style="{{ ($searchQuery ?? request('q')) ? '' : 'display:none' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-[#00C4FF] hover:bg-[#00a0cc] text-white font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 text-sm flex items-center gap-1.5"
                    >
                        <i class="fa-solid fa-search"></i>
                    </button>
                </form>
            </div>

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
                    <x-filter-form formId="filter-form-mobile" :showClose="true" :gender="$gender" :filters="$filters" />
                </aside>

                {{-- Desktop sidebar --}}
                <aside class="hidden md:block w-64 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 md:p-6 sticky top-24 relative">
                        <x-filter-form formId="filter-form" :gender="$gender" :filters="$filters" />
                    </div>
                </aside>

                <div class="flex-1 min-w-0">
                    @php
                        $activeFilters = [];
                        if (request('color')) $activeFilters[] = ['key' => 'color', 'label' => 'Color: ' . ucfirst(request('color'))];
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

                    @if($searchQuery)
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                            {{ $products->total() }} {{ $products->total() === 1 ? 'resultado' : 'resultados' }} para <strong class="text-gray-800 dark:text-white">"{{ $searchQuery }}"</strong>
                        </span>
                        <a href="/relojes" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg text-xs font-bold hover:bg-red-100 dark:hover:bg-red-900/40 transition-all">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                            Limpiar búsqueda
                        </a>
                    </div>
                    @endif

                    @if($hasActiveFilters)
                    <div class="flex flex-wrap items-center gap-1.5 mb-4">
                        @foreach($activeFilters as $filter)
                        <div class="inline-flex items-center bg-[#00C4FF]/10 text-[#00C4FF] px-2.5 py-1 rounded-full text-xs font-medium border border-[#00C4FF]/20">
                            <span>{{ $filter['label'] }}</span>
                            <button onclick="removeFilter('{{ $filter['key'] }}')" class="ml-1.5 hover:text-[#00a0cc]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        </div>
                        @endforeach
                        <a href="/relojes" class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-xs font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                            Limpiar todo
                        </a>
                    </div>
                    @endif

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

                    <div id="infinite-scroll-sentinel" class="mt-8 mb-6"></div>

                    @if($products->lastPage() > 1)
                    <nav id="pagination-nav" class="flex items-center justify-center gap-1 sm:gap-2 mt-10 mb-6 flex-wrap" aria-label="Paginación">
                        @if($products->previousPageUrl())
                        <a href="{{ $products->previousPageUrl() }}" class="inline-flex items-center justify-center h-10 min-w-10 px-2 sm:px-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:border-blue-400 dark:hover:bg-gray-700 shadow-sm transition-all">
                            ← Anterior
                        </a>
                        @endif
                        @for($pageNum = 1; $pageNum <= $products->lastPage(); $pageNum++)
                            @if($pageNum == $products->currentPage())
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-[#00C4FF] text-white rounded-lg text-sm font-bold shadow-md" aria-current="page">{{ $pageNum }}</span>
                            @else
                            <a href="{{ $products->url($pageNum) }}" class="inline-flex items-center justify-center w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:border-blue-400 dark:hover:bg-gray-700 rounded-lg text-sm font-semibold shadow-sm transition-all">{{ $pageNum }}</a>
                            @endif
                        @endfor
                        @if($products->nextPageUrl())
                        <a href="{{ $products->nextPageUrl() }}" class="inline-flex items-center justify-center h-10 min-w-10 px-2 sm:px-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:border-blue-400 dark:hover:bg-gray-700 shadow-sm transition-all">
                            Siguiente →
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
        window.closeFilters = function() {
            const aside = document.querySelector('aside[x-show="filterOpen"]');
            if (aside && window.Alpine) {
                const el = aside.closest('[x-data]');
                if (el && el._x_dataStack) el._x_dataStack[0].filterOpen = false;
            }
            document.body.style.overflow = '';
        };

        document.addEventListener("DOMContentLoaded", function() {
            window.removeFilter = function(filterKey) {
                const url = new URL(window.location.href);
                url.searchParams.delete(filterKey);
                if (filterKey === 'sort') url.searchParams.set('sort', 'newest');
                window.location.href = url.pathname + url.search;
            };

            window.clearAllFilters = function() {
                window.location.href = window.location.pathname;
            };

            window.clearLiveSearch = null;

            (function() {
                const input = document.getElementById('live-search-input');
                const clearBtn = document.getElementById('live-search-clear');
                const grid = document.getElementById('products-grid');
                if (!input || !grid) return;

                window.clearLiveSearch = async function() {
                    if (input) { input.value = ''; input.focus(); }
                    if (clearBtn) clearBtn.style.display = 'none';

                    try {
                        const res = await fetch('/relojes');
                        const html = await res.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('products-grid');
                        if (newGrid) {
                            const existingCount = document.getElementById('live-results-count');
                            if (existingCount) existingCount.remove();
                            grid.innerHTML = newGrid.innerHTML;
                            grid.dataset.currentPage = '1';
                            grid.dataset.totalPages = newGrid.dataset.totalPages || '1';
                        }
                        window.history.replaceState(null, '', '/relojes');
                    } catch (e) {
                        window.location.href = '/relojes';
                    }
                };

                let timer = null;
                let lastQuery = input.value.trim();
                let abortController = null;

                input.addEventListener('input', function() {
                    const q = this.value.trim();
                    clearBtn.style.display = q ? '' : 'none';

                    if (q === lastQuery) return;
                    if (timer) clearTimeout(timer);

                    if (q.length < 1) {
                        window.location.href = '/relojes';
                        return;
                    }

                    timer = setTimeout(function() { doLiveSearch(q); }, 200);
                });

                async function doLiveSearch(q) {
                    if (abortController) abortController.abort();
                    abortController = new AbortController();

                    try {
                        const res = await fetch('/api/live-search?q=' + encodeURIComponent(q), { signal: abortController.signal });
                        if (!res.ok) return;
                        const html = await res.text();
                        lastQuery = q;

                        const sentinel = document.getElementById('infinite-scroll-sentinel');
                        const pagNav = document.getElementById('pagination-nav');
                        if (sentinel) sentinel.innerHTML = '';
                        if (pagNav) pagNav.style.display = 'none';

                        const existingCount = document.getElementById('live-results-count');
                        if (existingCount) existingCount.remove();

                        if (!html.trim()) {
                            grid.innerHTML = '<div class="col-span-full text-center py-16"><i class="fa-solid fa-search text-4xl text-gray-300 dark:text-gray-600 mb-4"></i><p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No se encontraron relojes</p><p class="text-sm text-gray-400 mt-1">Intenta con otro término</p></div>';
                            return;
                        }

                        const temp = document.createElement('div');
                        temp.innerHTML = html;
                        const count = temp.children.length;
                        const countDiv = document.createElement('div');
                        countDiv.id = 'live-results-count';
                        countDiv.className = 'mb-4';
                        countDiv.innerHTML = '<span class="text-sm text-gray-500 dark:text-gray-400 font-medium">' + count + ' resultado' + (count !== 1 ? 's' : '') + ' para <strong class="text-gray-800 dark:text-white">\u201c' + escapeHtml(q) + '\u201d</strong></span>';
                        grid.parentElement.insertBefore(countDiv, grid);

                        grid.innerHTML = html;
                        window.history.replaceState(null, '', '/relojes?q=' + encodeURIComponent(q));
                    } catch (e) {
                        if (e.name !== 'AbortError') console.error('Live search error:', e);
                    }
                }

                function escapeHtml(t) {
                    var d = document.createElement('div');
                    d.appendChild(document.createTextNode(t || ''));
                    return d.innerHTML;
                }
            })();

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

                if (paginationNav) {
                    setTimeout(function() { paginationNav.style.display = "none"; }, 100);
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
                }, { rootMargin: "400px", threshold: 0 });

                observer.observe(sentinel);
            }
        });
    </script>
    @endpush
</x-app-layout>
@endif
