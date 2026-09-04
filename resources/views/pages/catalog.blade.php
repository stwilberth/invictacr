@if(request('partial'))
@foreach($products as $product)
    <x-product-card :product="$product" />
@endforeach
@else
@php
    $pageSize = 24;
    $totalCount = $products->count();
    $firstPage = $products->take($pageSize);
@endphp

<x-app-layout :title="'Relojes Invicta ' . ($gender ? ucfirst($gender) : 'Originales')" :description="'Explora nuestra colección de relojes Invicta ' . ($gender ? 'para ' . $gender : 'originales') . '. Envío gratis en GAM.'">
    @push('json-ld')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "CollectionPage",
        "name": "Relojes Invicta {{ $gender ? ucfirst($gender) : 'Originales' }} en Costa Rica",
        "url": "{{ url('/relojes') }}",
        "mainEntity": {
            "@@type": "ItemList",
            "itemListElement": [
                @foreach($products->take(20) as $i => $product)
                {
                    "@@type": "ListItem",
                    "position": {{ $i + 1 }},
                    "url": "{{ route('products.show', $product->slug) }}",
                    "name": "Reloj Invicta {{ $product->modelo }}"
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ]
        }
    }
    </script>
    @endpush
    <div class="bg-white dark:bg-[#0a0f1c]">
        <div class="max-w-7xl mx-auto px-4 pt-6 md:pt-12">
            <x-page-title
                :title="'Relojes Invicta'"
                :subtitle="''"
            />

            <div class="text-center -mt-4 mb-4">
                <span class="text-xs md:text-sm font-semibold text-emerald-600 dark:text-emerald-400 leading-tight">Envío gratis <span class="text-slate-400 dark:text-slate-500">•</span> Paga al recibir</span>
            </div>

            <div class="flex flex-col md:flex-row gap-8 pb-12" x-data="{ filterOpen: false, searchOpen: false }">

                {{-- Mobile: Filtros + ordenar (flotante al hacer scroll) --}}
                <div class="sticky top-2 z-30 md:hidden">
                    <div class="catalog-toolbar flex items-center gap-2.5">
                        <button @click="filterOpen = true" class="flex-1 min-w-0 flex items-center justify-center gap-1.5 bg-[#59D9FF] hover:bg-[#39CEFF] text-[#0a0f1c] rounded-xl px-2.5 py-2.5 font-bold text-xs uppercase tracking-wider active:scale-95 transition-all">
                            <i class="fa-solid fa-sliders text-[#0a0f1c] text-[11px]"></i>
                            Filtrar
                        </button>
                        <button @click="searchOpen = true" class="flex-1 min-w-0 flex items-center justify-center gap-1.5 bg-[#59D9FF] hover:bg-[#39CEFF] text-[#0a0f1c] rounded-xl px-2.5 py-2.5 font-bold text-xs uppercase tracking-wider active:scale-95 transition-all">
                            <i class="fa-solid fa-search text-[#0a0f1c] text-[11px]"></i>
                            Buscar
                        </button>
                        <div class="flex-1 min-w-0 flex items-center justify-center gap-1.5 bg-[#59D9FF] rounded-xl px-2.5 py-2.5">
                            <i class="fa-solid fa-arrow-down-wide-short text-[#0a0f1c] text-xs"></i>
                            <select
                                id="catalog-sort-mobile"
                                onchange="window.CatalogManager && window.CatalogManager.setFilter('sort', this.value)"
                                class="min-w-0 bg-transparent text-[#0a0f1c] appearance-none -webkit-appearance-none uppercase text-xs font-bold focus:outline-none transition-all"
                            >
                                <option value="" {{ !request('sort') ? 'selected' : '' }}>Ordenar</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más nuevos</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Search popup modal --}}
                <div x-show="searchOpen" @click="searchOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60]" style="display:none;"></div>
                <div
                    class="fixed inset-0 z-[70] flex items-start justify-center px-4 pt-20"
                    x-show="searchOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    @keydown.escape="searchOpen = false"
                    x-effect="searchOpen && $nextTick(() => $refs.searchInput && $refs.searchInput.focus())"
                    style="display:none;"
                >
                    <div class="w-full max-w-xl bg-white dark:bg-[#0f172a] rounded-2xl shadow-2xl border border-gray-100 dark:border-white/10 overflow-hidden" @click.stop>
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-white/10">
                            <span class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Buscar reloj</span>
                            <button @click="searchOpen = false" aria-label="Cerrar" class="text-gray-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input
                                        x-ref="searchInput"
                                        id="catalog-search-input"
                                        type="text"
                                        value="{{ $searchQuery ?? request('q') }}"
                                        placeholder="Escribí un modelo o colección..."
                                        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF]/50 focus:ring-2 focus:ring-[#00C4FF]/20 transition-all text-sm pr-10"
                                        autocomplete="off"
                                    />
                                    <button type="button" id="catalog-search-clear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" style="{{ ($searchQuery ?? request('q')) ? '' : 'display:none' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    id="catalog-search-btn"
                                    class="px-5 py-3 bg-[#00C4FF] hover:bg-[#00a0cc] text-white font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 text-sm flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-search"></i>
                                    <span class="hidden sm:inline">Buscar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Drawer overlay (móvil y desktop) --}}
                <div x-show="filterOpen" @click="filterOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" style="display:none;"></div>

                {{-- Drawer (móvil y desktop) --}}
                <aside
                    class="fixed inset-y-0 left-0 z-50 w-[85%] max-w-sm bg-white dark:bg-gray-800 shadow-2xl overflow-y-auto p-4 pb-24"
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

                <div class="flex-1 min-w-0 relative">
                    {{-- Results info bar --}}
                    <div id="catalog-results-info"></div>

                    {{-- Ordenar (desktop, flotante) --}}
                    <div class="catalog-toolbar hidden md:flex md:sticky md:top-2 z-20 items-center justify-center gap-2 mb-2">
                        <button @click="filterOpen = true" class="shrink-0 inline-flex items-center justify-center gap-1.5 bg-[#59D9FF] hover:bg-[#39CEFF] rounded-xl px-2.5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#0a0f1c] active:scale-95 transition-all">
                            <i class="fa-solid fa-sliders text-[#0a0f1c]"></i>
                            Filtrar
                        </button>
                        <div class="shrink-0 flex items-center justify-center gap-1.5 bg-[#59D9FF] rounded-xl px-2.5 py-2.5">
                            <i class="fa-solid fa-arrow-down-wide-short text-[#0a0f1c] text-xs"></i>
                            <select
                                id="catalog-sort"
                                onchange="window.CatalogManager && window.CatalogManager.setFilter('sort', this.value)"
                                class="min-w-0 bg-transparent text-[#0a0f1c] appearance-none -webkit-appearance-none uppercase text-xs font-bold focus:outline-none transition-all"
                            >
                                <option value="" {{ !request('sort') ? 'selected' : '' }}>Ordenar</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más nuevos</option>
                            </select>
                        </div>
                        <button @click="searchOpen = true" class="shrink-0 inline-flex items-center justify-center gap-1.5 bg-[#59D9FF] hover:bg-[#39CEFF] rounded-xl px-2.5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#0a0f1c] active:scale-95 transition-all">
                            <i class="fa-solid fa-search text-[#0a0f1c]"></i>
                            Buscar
                        </button>
                    </div>

                    {{-- Active filters chips --}}
                    <div id="catalog-active-filters"></div>

                    {{-- Loading overlay --}}
                    <div id="catalog-loading" class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 dark:bg-[#0a0f1c]/70 backdrop-blur-sm rounded-2xl" style="display:none;">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fa-solid fa-spinner fa-spin text-4xl text-[#00C4FF]"></i>
                            <span class="text-sm font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">Cargando relojes…</span>
                        </div>
                    </div>

                    @if($totalCount > 0)
                    <div
                        id="products-grid"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4"
                        data-total="{{ $totalCount }}"
                        data-page-size="{{ $pageSize }}"
                    >
                        @foreach($firstPage as $product)
                            <x-product-card :product="$product" :priority="$loop->index < 4" />
                        @endforeach
                    </div>
                    @else
                    <div id="catalog-empty-state" class="text-center py-6 px-4">
                        <div class="max-w-2xl mx-auto">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No encontramos lo que buscás</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 leading-relaxed">
                                No hay resultados para esa búsqueda. Podés intentar con otro modelo, colección o filtro.
                            </p>
                            @if($suggestions->isNotEmpty())
                            <div class="mb-6">
                                <p class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">Tal vez te interese</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($suggestions as $suggestion)
                                        <x-product-card :product="$suggestion" />
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('products.index') }}"
                                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-xl text-sm transition-all border border-gray-200 dark:border-gray-700">
                                    <i class="fa-solid fa-clock"></i> Ver todo el catálogo
                                </a>
                                <a href="https://wa.me/50686711422?text=Hola%2C%20busco%20un%20reloj%20Invicta"
                                   target="_blank"
                                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-green-500/20">
                                    <i class="fa-brands fa-whatsapp text-lg"></i> Escribinos por WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Sentinel para scroll infinito --}}
                    <div id="catalog-sentinel" class="flex flex-col items-center justify-center">
                        <div id="catalog-sentinel-spinner" class="items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400" style="display:none;">
                            <i class="fa-solid fa-spinner fa-spin text-[#00C4FF]"></i> Cargando más relojes…
                        </div>
                        <div id="catalog-sentinel-end" class="items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-300 dark:text-gray-600" style="display:none;">
                            <span class="h-px w-6 bg-gray-300 dark:bg-gray-700"></span> Fin del catálogo <span class="h-px w-6 bg-gray-300 dark:bg-gray-700"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        /**
         * CatalogManager — Single Source of Truth for search, filters & scroll infinito.
         *
         * All filter/search state lives in the URL query params.
         * La grilla se carga por bloques (scroll infinito): el servidor renderiza la
         * primera página y aquí se piden los siguientes bloques vía /api/live-search.
         */
        (function() {
            'use strict';

            var FILTER_KEYS = ['q', 'gender', 'color', 'coleccion', 'brazalete', 'tipo_movimiento', 'caja', 'resistencia_agua', 'size', 'precio_min', 'precio_max', 'sort'];
            var DEBOUNCE_MS = 300;
            var DEFAULT_PAGE_SIZE = 24;

            var state = {
                abortController: null,
                searchTimer: null,
                filters: {},
                total: 0,
                loaded: 0,
                pageSize: DEFAULT_PAGE_SIZE,
                allLoaded: false,
                loadingMore: false,
                gen: 0,
                observer: null
            };

            // ─── DOM refs ───
            var els = {};
            function cacheEls() {
                els.searchInput = document.getElementById('catalog-search-input');
                els.searchClear = document.getElementById('catalog-search-clear');
                els.searchBtn = document.getElementById('catalog-search-btn');
                els.grid = document.getElementById('products-grid');
                els.resultsInfo = document.getElementById('catalog-results-info');
                els.activeFilters = document.getElementById('catalog-active-filters');
                els.loading = document.getElementById('catalog-loading');
                els.sentinel = document.getElementById('catalog-sentinel');
            }

            // ─── Read state from URL ───
            function getFiltersFromURL() {
                var url = new URL(window.location.href);
                var filters = {};
                FILTER_KEYS.forEach(function(key) {
                    var val = url.searchParams.get(key);
                    if (val) filters[key] = val;
                });
                return filters;
            }

            // ─── Build URL from filters ───
            function buildURL(filters) {
                var url = new URL(window.location.origin + '/relojes');
                Object.keys(filters).forEach(function(key) {
                    if (filters[key]) url.searchParams.set(key, filters[key]);
                });
                return url;
            }

            // ─── Build /api/live-search URL from filters ───
            function buildFetchURL(filters) {
                var url = new URL(window.location.origin + '/api/live-search');
                Object.keys(filters).forEach(function(key) {
                    if (filters[key]) url.searchParams.set(key, filters[key]);
                });
                return url;
            }

            // ─── UI helpers ───
            function showLoadingUI() {
                if (els.grid) {
                    els.grid.style.opacity = '0.5';
                    els.grid.style.pointerEvents = 'none';
                }
                if (els.loading) els.loading.style.display = 'flex';
            }
            function hideLoadingUI() {
                if (els.grid) {
                    els.grid.style.opacity = '1';
                    els.grid.style.pointerEvents = '';
                }
                if (els.loading) els.loading.style.display = 'none';
            }

            function ensureGridExists() {
                if (els.grid) return;
                var container = document.querySelector('.flex-1.min-w-0');
                if (!container) return;

                var emptyEl = document.getElementById('catalog-empty-state');
                if (emptyEl) emptyEl.remove();

                var gridDiv = document.createElement('div');
                gridDiv.id = 'products-grid';
                gridDiv.className = 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4';
                gridDiv.setAttribute('data-page-size', String(state.pageSize));

                if (els.sentinel && els.sentinel.parentNode === container) {
                    container.insertBefore(gridDiv, els.sentinel);
                } else {
                    container.appendChild(gridDiv);
                }

                cacheEls();
            }

            function updateSentinelUI(showSpinner) {
                if (!els.sentinel) return;
                var spin = document.getElementById('catalog-sentinel-spinner');
                var end = document.getElementById('catalog-sentinel-end');
                if (!spin || !end) return;

                if (showSpinner) {
                    spin.style.display = 'flex';
                    end.style.display = 'none';
                    return;
                }

                spin.style.display = 'none';
                end.style.display = (state.allLoaded && state.total > 0) ? 'flex' : 'none';
            }

            function emptyStateHTML() {
                return '<div class="col-span-full text-center py-16 px-4"><div class="max-w-md mx-auto"><i class="fa-solid fa-clock-rotate-left text-5xl text-[#00C4FF]/30 mb-6"></i><h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No encontramos lo que buscás</h3><p class="text-gray-500 dark:text-gray-400 text-sm mb-6 leading-relaxed">No hay resultados para esa búsqueda. Podés intentar con otro modelo, colección o filtro.</p><div class="flex flex-col sm:flex-row gap-3 justify-center"><a href="/relojes" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-xl text-sm transition-all border border-gray-200 dark:border-gray-700"><i class="fa-solid fa-clock"></i> Ver todo el catálogo</a><a href="https://wa.me/50686711422?text=Hola%2C%20busco%20un%20reloj%20Invicta" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-green-500/20"><i class="fa-brands fa-whatsapp text-lg"></i> Escribinos por WhatsApp</a></div></div></div>';
            }

            // ─── Core: apply filters → fetch primera página ───
            function applyFilters(filters, pushHistory) {
                if (state.abortController) state.abortController.abort();
                state.abortController = new AbortController();
                var gen = ++state.gen;

                state.filters = filters || {};
                state.loadingMore = false;
                state.allLoaded = false;
                state.loaded = 0;

                var url = buildURL(state.filters);

                if (pushHistory !== false) {
                    var urlStr = url.pathname + url.search;
                    window.history.pushState({ catalogFilters: state.filters }, '', urlStr);
                }

                if (els.searchInput) {
                    els.searchInput.value = state.filters.q || '';
                    if (els.searchClear) els.searchClear.style.display = state.filters.q ? '' : 'none';
                }

                showLoadingUI();

                var fetchUrl = buildFetchURL(state.filters);
                fetchUrl.searchParams.set('offset', '0');
                fetchUrl.searchParams.set('limit', String(state.pageSize));

                fetch(fetchUrl.toString(), { signal: state.abortController.signal })
                    .then(function(res) {
                        if (!res.ok) throw new Error('Fetch failed');
                        return res.json();
                    })
                    .then(function(data) {
                        if (gen !== state.gen) return;
                        renderFirstPage(data);
                    })
                    .catch(function(e) {
                        if (e.name !== 'AbortError' && gen === state.gen) {
                            console.error('CatalogManager fetch error:', e);
                            hideLoadingUI();
                        }
                    });
            }

            function renderFirstPage(data) {
                hideLoadingUI();
                ensureGridExists();
                if (!els.grid) return;

                var emptyEl = document.getElementById('catalog-empty-state');
                if (emptyEl) emptyEl.remove();

                if (data.html && data.html.trim()) {
                    els.grid.innerHTML = data.html;
                    state.total = data.total || 0;
                    state.loaded = data.count || 0;
                    state.allLoaded = state.loaded >= state.total;
                    if (window.PCardSlider) window.PCardSlider.init(els.grid);
                } else {
                    els.grid.innerHTML = emptyStateHTML();
                    state.total = data.total || 0;
                    state.loaded = 0;
                    state.allLoaded = true;
                }

                updateSentinelUI(false);
                renderResultsInfo(state.total, state.filters);
                renderActiveFilters(state.filters);

                if (window.closeFilters) window.closeFilters();
            }

            // ─── Scroll infinito ───
            function sentinelInReach() {
                if (!els.grid || !els.sentinel) return false;
                var rect = els.sentinel.getBoundingClientRect();
                var vh = window.innerHeight || document.documentElement.clientHeight;
                return rect.top < (vh + 800);
            }

            function maybeLoadMore() {
                if (!els.grid) return;
                if (state.loadingMore || state.allLoaded) {
                    if (state.loaded >= state.total && state.total > 0) {
                        state.allLoaded = true;
                        updateSentinelUI(false);
                    }
                    return;
                }
                if (state.total > 0 && state.loaded >= state.total) {
                    state.allLoaded = true;
                    updateSentinelUI(false);
                    return;
                }
                loadMore();
            }

            function loadMore() {
                if (state.loadingMore || state.allLoaded || !els.grid) return;
                if (state.total > 0 && state.loaded >= state.total) {
                    state.allLoaded = true;
                    updateSentinelUI(false);
                    return;
                }

                state.loadingMore = true;
                var gen = state.gen;
                updateSentinelUI(true);

                if (state.abortController) state.abortController.abort();
                state.abortController = new AbortController();

                var fetchUrl = buildFetchURL(state.filters);
                fetchUrl.searchParams.set('offset', String(state.loaded));
                fetchUrl.searchParams.set('limit', String(state.pageSize));

                fetch(fetchUrl.toString(), { signal: state.abortController.signal })
                    .then(function(res) {
                        if (!res.ok) throw new Error('Fetch failed');
                        return res.json();
                    })
                    .then(function(data) {
                        if (gen !== state.gen) return;
                        state.loadingMore = false;

                        if (data.html && els.grid) {
                            els.grid.insertAdjacentHTML('beforeend', data.html);
                            state.loaded = data.offset + data.count;
                            if (window.PCardSlider) window.PCardSlider.init(els.grid);
                        }

                        if (state.total === 0) state.total = data.total || 0;
                        if (state.loaded >= data.total) {
                            state.allLoaded = true;
                        }

                        updateSentinelUI(false);

                        // Si la pantalla aún no se llena, seguimos cargando.
                        requestAnimationFrame(function() {
                            if (sentinelInReach()) maybeLoadMore();
                        });
                    })
                    .catch(function(e) {
                        if (e.name === 'AbortError') return;
                        if (gen !== state.gen) return;
                        state.loadingMore = false;
                        updateSentinelUI(false);
                    });
            }

            function setupObserver() {
                if (state.observer || !els.sentinel) return;
                if (!('IntersectionObserver' in window)) {
                    window.addEventListener('scroll', function() {
                        if (sentinelInReach()) maybeLoadMore();
                    }, { passive: true });
                    return;
                }

                state.observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) maybeLoadMore();
                    });
                }, { rootMargin: '800px 0px' });

                state.observer.observe(els.sentinel);
            }

            // ─── Results info bar ───
            function renderResultsInfo(total, filters) {
                if (!els.resultsInfo) return;

                if (filters.q) {
                    els.resultsInfo.innerHTML = '<div class="mb-4 flex items-center justify-between">' +
                        '<span class="text-sm text-gray-500 dark:text-gray-400 font-medium">' +
                        total + ' ' + (total === 1 ? 'resultado' : 'resultados') +
                        ' para <strong class="text-gray-800 dark:text-white">"' + escapeHtml(filters.q) + '"</strong></span>' +
                        '<button onclick="window.CatalogManager.setFilter(\'q\', \'\')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg text-xs font-bold hover:bg-red-100 dark:hover:bg-red-900/40 transition-all">' +
                        '<i class="fa-solid fa-xmark text-[10px]"></i> Limpiar búsqueda</button></div>';
                } else {
                    els.resultsInfo.innerHTML = '';
                }
            }

            // ─── Active filter chips ───
            function renderActiveFilters(filters) {
                if (!els.activeFilters) return;

                var chips = [];

                if (filters.gender) chips.push({ key: 'gender', label: 'Género: ' + capitalize(filters.gender) });
                if (filters.color) chips.push({ key: 'color', label: 'Color: ' + capitalize(filters.color) });
                if (filters.coleccion) chips.push({ key: 'coleccion', label: 'Colección: ' + capitalize(filters.coleccion) });
                if (filters.brazalete) chips.push({ key: 'brazalete', label: 'Brazalete: ' + capitalize(filters.brazalete) });
                if (filters.tipo_movimiento) chips.push({ key: 'tipo_movimiento', label: 'Movimiento: ' + (filters.tipo_movimiento === 'cuarzo' ? 'Batería' : capitalize(filters.tipo_movimiento)) });
                if (filters.caja) chips.push({ key: 'caja', label: 'Caja: ' + capitalize(filters.caja) });
                if (filters.resistencia_agua) chips.push({ key: 'resistencia_agua', label: 'Resistencia: ' + filters.resistencia_agua + 'M' });
                if (filters.size) chips.push({ key: 'size', label: 'Tamaño: ' + filters.size + 'mm' });

                if (chips.length === 0) {
                    els.activeFilters.innerHTML = '';
                    return;
                }

                var html = '<div class="flex flex-wrap items-center gap-1.5 mb-4">';
                chips.forEach(function(chip) {
                    html += '<div class="inline-flex items-center bg-[#00C4FF]/10 text-[#00C4FF] px-2.5 py-1 rounded-full text-xs font-medium border border-[#00C4FF]/20">' +
                        '<span>' + escapeHtml(chip.label) + '</span>' +
                        '<button onclick="window.CatalogManager.removeFilter(\'' + chip.key + '\')" class="ml-1.5 hover:text-[#00a0cc]">' +
                        '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>' +
                        '</button></div>';
                });
                html += '<button onclick="window.CatalogManager.clearAll()" class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-xs font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">' +
                    '<i class="fa-solid fa-xmark text-[10px]"></i> Limpiar todo</button>';
                html += '</div>';

                els.activeFilters.innerHTML = html;
            }

            // ─── Sync filter UI (radios) across desktop and mobile forms ───
            function syncFilterUI(key, value) {
                var radioNames = [key + '_filter-form', key + '_filter-form-mobile'];
                radioNames.forEach(function(name) {
                    var radios = document.querySelectorAll('input[name="' + name + '"]');
                    radios.forEach(function(radio) {
                        radio.checked = (radio.value === (value || ''));
                    });
                });

                if (key === 'sort') {
                    var sortSelect = document.getElementById('catalog-sort');
                    if (sortSelect) sortSelect.value = value || '';
                    var sortMobile = document.getElementById('catalog-sort-mobile');
                    if (sortMobile) sortMobile.value = value || '';
                }
            }

            // ─── Utilities ───
            function escapeHtml(t) {
                var d = document.createElement('div');
                d.appendChild(document.createTextNode(t || ''));
                return d.innerHTML;
            }

            function capitalize(s) {
                return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
            }

            // ─── Public API ───
            window.CatalogManager = {
                setFilter: function(key, value) {
                    var filters = getFiltersFromURL();

                    if (value) {
                        filters[key] = value;
                    } else {
                        delete filters[key];
                    }

                    syncFilterUI(key, value);

                    if (key === 'q') {
                        if (els.searchInput && els.searchInput.value !== (value || '')) {
                            els.searchInput.value = value || '';
                        }
                        if (els.searchClear) els.searchClear.style.display = value ? '' : 'none';

                        if (state.searchTimer) clearTimeout(state.searchTimer);
                        state.searchTimer = setTimeout(function() {
                            applyFilters(filters);
                        }, DEBOUNCE_MS);
                    } else {
                        applyFilters(filters);
                    }
                },

                removeFilter: function(key) {
                    this.setFilter(key, '');
                },

                clearAll: function() {
                    FILTER_KEYS.forEach(function(key) {
                        syncFilterUI(key, '');
                    });
                    applyFilters({});
                },

                /** Programmatic re-search (used by navbar) */
                search: function(q) {
                    this.setFilter('q', q);
                }
            };

            // ─── Handle browser back/forward ───
            window.addEventListener('popstate', function() {
                var filters = getFiltersFromURL();

                FILTER_KEYS.forEach(function(key) {
                    syncFilterUI(key, filters[key] || '');
                });

                applyFilters(filters, false);
            });

            // ─── Init ───
            document.addEventListener('DOMContentLoaded', function() {
                cacheEls();

                window.closeFilters = function() {
                    var aside = document.querySelector('aside[x-show="filterOpen"]');
                    if (aside && window.Alpine) {
                        var el = aside.closest('[x-data]');
                        if (el && el._x_dataStack) el._x_dataStack[0].filterOpen = false;
                    }
                    document.body.style.overflow = '';
                };

                if (els.searchInput) {
                    els.searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            var q = this.value.trim();
                            var url = new URL(window.location.origin + '/relojes');
                            if (q) url.searchParams.set('q', q);
                            window.location.href = url.toString();
                        }
                    });
                }

                if (els.searchClear) {
                    els.searchClear.addEventListener('click', function() {
                        window.location.href = '/relojes';
                    });
                }

                if (els.searchBtn) {
                    els.searchBtn.addEventListener('click', function() {
                        var q = els.searchInput ? els.searchInput.value.trim() : '';
                        var url = new URL(window.location.origin + '/relojes');
                        if (q) url.searchParams.set('q', q);
                        window.location.href = url.toString();
                    });
                }

                // Estado inicial desde el HTML servido por el servidor
                var initialFilters = getFiltersFromURL();
                state.filters = initialFilters;

                if (els.grid) {
                    state.total = parseInt(els.grid.getAttribute('data-total') || '0', 10) || 0;
                    state.pageSize = parseInt(els.grid.getAttribute('data-page-size') || '0', 10) || DEFAULT_PAGE_SIZE;
                    state.loaded = els.grid.children.length;
                    state.allLoaded = state.total <= state.loaded;
                } else {
                    state.total = 0;
                    state.loaded = 0;
                    state.allLoaded = true;
                }

                updateSentinelUI(false);
                renderActiveFilters(initialFilters);

                @if($searchQuery)
                renderResultsInfo({{ $totalCount }}, { q: {!! json_encode($searchQuery) !!} });
                @endif

                setupObserver();

                // Si el contenido inicial no llena la pantalla, precargamos más.
                requestAnimationFrame(function() {
                    if (sentinelInReach()) maybeLoadMore();
                });
            });
        })();
    </script>
    @endpush
</x-app-layout>
@endif
