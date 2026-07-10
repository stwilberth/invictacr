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
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input
                            id="catalog-search-input"
                            type="text"
                            value="{{ $searchQuery ?? request('q') }}"
                            placeholder="Escribí un modelo o colección..."
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF]/50 focus:ring-2 focus:ring-[#00C4FF]/20 transition-all text-sm pr-10"
                            autocomplete="off"
                        />
                        <button type="button" id="catalog-search-clear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" style="{{ ($searchQuery ?? request('q')) ? '' : 'display:none' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <button
                        type="button"
                        id="catalog-search-btn"
                        class="px-5 py-2.5 bg-[#00C4FF] hover:bg-[#00a0cc] text-white font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 text-sm flex items-center gap-1.5"
                    >
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
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
                    {{-- Results info bar --}}
                    <div id="catalog-results-info"></div>

                    {{-- Active filters chips --}}
                    <div id="catalog-active-filters"></div>

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
                    <div id="catalog-empty-state" class="text-center py-20">
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
        /**
         * CatalogManager — Single Source of Truth for search & filters.
         *
         * All filter/search state lives in the URL query params.
         * Every interaction updates params → fetches results → updates DOM.
         */
        (function() {
            'use strict';

            var FILTER_KEYS = ['q', 'gender', 'color', 'coleccion', 'brazalete', 'tipo_movimiento', 'caja', 'resistencia_agua', 'size', 'precio_min', 'precio_max', 'sort'];
            var DEBOUNCE_MS = 300;

            var state = {
                abortController: null,
                searchTimer: null,
                infiniteObserver: null,
                infinitePage: 1,
                totalPages: 1,
                isLoadingMore: false,
                allLoaded: false,
            };

            // ─── DOM refs ───
            var els = {};
            function cacheEls() {
                els.searchInput = document.getElementById('catalog-search-input');
                els.searchClear = document.getElementById('catalog-search-clear');
                els.searchBtn = document.getElementById('catalog-search-btn');
                els.grid = document.getElementById('products-grid');
                els.sentinel = document.getElementById('infinite-scroll-sentinel');
                els.paginationNav = document.getElementById('pagination-nav');
                els.resultsInfo = document.getElementById('catalog-results-info');
                els.activeFilters = document.getElementById('catalog-active-filters');
                els.emptyState = document.getElementById('catalog-empty-state');
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
            function buildURL(filters, page) {
                var url = new URL(window.location.origin + '/relojes');
                Object.keys(filters).forEach(function(key) {
                    if (filters[key]) url.searchParams.set(key, filters[key]);
                });
                if (page && page > 1) url.searchParams.set('page', String(page));
                return url;
            }

            // ─── Core: apply filters and fetch ───
            function applyFilters(filters, pushHistory) {
                if (state.abortController) state.abortController.abort();
                state.abortController = new AbortController();

                var url = buildURL(filters);

                // Update browser URL
                if (pushHistory !== false) {
                    var urlStr = url.pathname + url.search;
                    window.history.pushState({ catalogFilters: filters }, '', urlStr);
                }

                // Update search input to reflect current q
                if (els.searchInput) {
                    els.searchInput.value = filters.q || '';
                    els.searchClear.style.display = filters.q ? '' : 'none';
                }

                // Show loading state
                if (els.grid) {
                    els.grid.style.opacity = '0.5';
                    els.grid.style.pointerEvents = 'none';
                }

                // Build fetch URL with all params
                var fetchUrl = new URL(window.location.origin + '/api/live-search');
                Object.keys(filters).forEach(function(key) {
                    if (filters[key]) fetchUrl.searchParams.set(key, filters[key]);
                });

                fetch(fetchUrl.toString(), { signal: state.abortController.signal })
                    .then(function(res) {
                        if (!res.ok) throw new Error('Fetch failed');
                        return res.json();
                    })
                    .then(function(data) {
                        renderResults(data, filters);
                    })
                    .catch(function(e) {
                        if (e.name !== 'AbortError') {
                            console.error('CatalogManager fetch error:', e);
                            if (els.grid) {
                                els.grid.style.opacity = '1';
                                els.grid.style.pointerEvents = '';
                            }
                        }
                    });
            }

            // ─── Render results ───
            function renderResults(data, filters) {
                // Ensure grid exists
                if (!els.grid) {
                    // Create grid if it was removed (was showing empty state)
                    var container = document.querySelector('.flex-1.min-w-0');
                    if (!container) return;

                    // Remove empty state if present
                    var emptyEl = document.getElementById('catalog-empty-state');
                    if (emptyEl) emptyEl.remove();

                    // Create grid
                    var gridDiv = document.createElement('div');
                    gridDiv.id = 'products-grid';
                    gridDiv.className = 'grid grid-cols-2 md:grid-cols-4 gap-4';
                    container.appendChild(gridDiv);

                    // Create sentinel
                    var sentinelDiv = document.createElement('div');
                    sentinelDiv.id = 'infinite-scroll-sentinel';
                    sentinelDiv.className = 'mt-8 mb-6';
                    container.appendChild(sentinelDiv);

                    cacheEls();
                }

                if (data.html && data.html.trim()) {
                    els.grid.innerHTML = data.html;
                    els.grid.style.opacity = '1';
                    els.grid.style.pointerEvents = '';
                    els.grid.dataset.currentPage = String(data.currentPage);
                    els.grid.dataset.totalPages = String(data.totalPages);

                    // Remove empty state if present
                    var emptyEl2 = document.getElementById('catalog-empty-state');
                    if (emptyEl2) emptyEl2.remove();
                } else {
                    els.grid.innerHTML = '<div class="col-span-full text-center py-20"><i class="fa-solid fa-search text-4xl text-gray-300 dark:text-gray-600 mb-4"></i><h3 class="text-xl font-bold text-gray-500 dark:text-gray-400">No se encontraron productos</h3><p class="text-gray-400 dark:text-gray-500 mt-2">Intenta con otros filtros o categorías</p></div>';
                    els.grid.style.opacity = '1';
                    els.grid.style.pointerEvents = '';
                }

                // Update results info
                renderResultsInfo(data.total, filters);

                // Update active filter chips
                renderActiveFilters(filters);

                // Hide pagination (we use infinite scroll)
                if (els.paginationNav) els.paginationNav.style.display = 'none';

                // Reset infinite scroll
                state.infinitePage = data.currentPage || 1;
                state.totalPages = data.totalPages || 1;
                state.allLoaded = state.infinitePage >= state.totalPages;
                state.isLoadingMore = false;

                if (els.sentinel) {
                    els.sentinel.innerHTML = state.allLoaded && data.total > 0 ? '' : '';
                }

                initInfiniteScroll();

                // Close mobile drawer
                if (window.closeFilters) window.closeFilters();
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
                var sortLabels = { price_asc: 'Menor precio', price_desc: 'Mayor precio', name_asc: 'A-Z', name_desc: 'Z-A' };

                if (filters.gender) chips.push({ key: 'gender', label: 'Género: ' + capitalize(filters.gender) });
                if (filters.color) chips.push({ key: 'color', label: 'Color: ' + capitalize(filters.color) });
                if (filters.coleccion) chips.push({ key: 'coleccion', label: 'Colección: ' + capitalize(filters.coleccion) });
                if (filters.brazalete) chips.push({ key: 'brazalete', label: 'Brazalete: ' + capitalize(filters.brazalete) });
                if (filters.tipo_movimiento) chips.push({ key: 'tipo_movimiento', label: 'Movimiento: ' + (filters.tipo_movimiento === 'cuarzo' ? 'Batería' : capitalize(filters.tipo_movimiento)) });
                if (filters.caja) chips.push({ key: 'caja', label: 'Caja: ' + capitalize(filters.caja) });
                if (filters.resistencia_agua) chips.push({ key: 'resistencia_agua', label: 'Resistencia: ' + filters.resistencia_agua + 'M' });
                if (filters.size) chips.push({ key: 'size', label: 'Tamaño: ' + filters.size + 'mm' });
                if (filters.precio_min) chips.push({ key: 'precio_min', label: 'Desde: ₡' + Number(filters.precio_min).toLocaleString('es-CR') });
                if (filters.precio_max) chips.push({ key: 'precio_max', label: 'Hasta: ₡' + Number(filters.precio_max).toLocaleString('es-CR') });
                if (filters.sort && filters.sort !== 'newest') chips.push({ key: 'sort', label: 'Orden: ' + (sortLabels[filters.sort] || filters.sort) });

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

            // ─── Infinite scroll ───
            function initInfiniteScroll() {
                // Tear down previous observer
                if (state.infiniteObserver) {
                    state.infiniteObserver.disconnect();
                    state.infiniteObserver = null;
                }

                if (!els.grid || !els.sentinel) return;
                if (state.totalPages <= 1) return;

                state.infiniteObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting && !state.isLoadingMore && !state.allLoaded) {
                            loadNextPage();
                        }
                    });
                }, { rootMargin: '400px', threshold: 0 });

                state.infiniteObserver.observe(els.sentinel);
            }

            function loadNextPage() {
                state.isLoadingMore = true;
                state.infinitePage++;

                if (state.infinitePage > state.totalPages) {
                    state.allLoaded = true;
                    state.isLoadingMore = false;
                    if (els.sentinel) els.sentinel.innerHTML = '<p class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">— Has visto todos los relojes —</p>';
                    return;
                }

                if (els.sentinel) {
                    els.sentinel.innerHTML = '<div class="flex justify-center py-4"><div class="w-8 h-8 border-2 border-[#00C4FF] border-t-transparent rounded-full animate-spin"></div></div>';
                }

                var filters = getFiltersFromURL();
                var fetchUrl = new URL(window.location.origin + '/relojes');
                Object.keys(filters).forEach(function(key) {
                    if (filters[key]) fetchUrl.searchParams.set(key, filters[key]);
                });
                fetchUrl.searchParams.set('page', String(state.infinitePage));
                fetchUrl.searchParams.set('partial', 'true');

                fetch(fetchUrl.toString())
                    .then(function(res) {
                        if (!res.ok) throw new Error('Failed to fetch');
                        return res.text();
                    })
                    .then(function(html) {
                        if (els.grid) els.grid.insertAdjacentHTML('beforeend', html);
                        if (els.sentinel) els.sentinel.innerHTML = '';

                        // Update browser URL with current page
                        var url = new URL(window.location.href);
                        url.searchParams.set('page', String(state.infinitePage));
                        window.history.replaceState(null, '', url.pathname + url.search);
                    })
                    .catch(function(e) {
                        console.error('Infinite scroll error:', e);
                        if (els.sentinel) els.sentinel.innerHTML = '<p class="text-center text-sm text-red-500 py-4">Error al cargar más productos</p>';
                    })
                    .finally(function() {
                        state.isLoadingMore = false;
                    });
            }

            // ─── Sync filter UI (radios) across desktop and mobile forms ───
            function syncFilterUI(key, value) {
                // Sync radios: find all radios for this filter key in both forms
                var radioNames = [key + '_filter-form', key + '_filter-form-mobile'];
                radioNames.forEach(function(name) {
                    var radios = document.querySelectorAll('input[name="' + name + '"]');
                    radios.forEach(function(radio) {
                        radio.checked = (radio.value === (value || ''));
                    });
                });

                // Sync price inputs
                if (key === 'precio_min' || key === 'precio_max') {
                    ['filter-form', 'filter-form-mobile'].forEach(function(formId) {
                        var input = document.getElementById(key + '_' + formId);
                        if (input) input.value = value || '';
                    });
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

                    // When setting a specific filter, remove page to reset pagination
                    delete filters.page;

                    // Sync the filter UI (radios, inputs) in both forms
                    syncFilterUI(key, value);

                    // For search input, use debounce
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
                    // Reset all filter UI
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

                // Sync all filter UI
                FILTER_KEYS.forEach(function(key) {
                    syncFilterUI(key, filters[key] || '');
                });

                applyFilters(filters, false);
            });

            // ─── Init ───
            document.addEventListener('DOMContentLoaded', function() {
                cacheEls();

                // Close filters helper
                window.closeFilters = function() {
                    var aside = document.querySelector('aside[x-show="filterOpen"]');
                    if (aside && window.Alpine) {
                        var el = aside.closest('[x-data]');
                        if (el && el._x_dataStack) el._x_dataStack[0].filterOpen = false;
                    }
                    document.body.style.overflow = '';
                };

                // Search input events
                if (els.searchInput) {
                    els.searchInput.addEventListener('input', function() {
                        var q = this.value.trim();
                        window.CatalogManager.setFilter('q', q);
                    });

                    els.searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (state.searchTimer) clearTimeout(state.searchTimer);
                            var filters = getFiltersFromURL();
                            filters.q = this.value.trim() || undefined;
                            if (!filters.q) delete filters.q;
                            delete filters.page;
                            applyFilters(filters);
                        }
                    });
                }

                // Clear search button
                if (els.searchClear) {
                    els.searchClear.addEventListener('click', function() {
                        window.CatalogManager.setFilter('q', '');
                    });
                }

                // Search button (magnifier)
                if (els.searchBtn) {
                    els.searchBtn.addEventListener('click', function() {
                        if (state.searchTimer) clearTimeout(state.searchTimer);
                        var filters = getFiltersFromURL();
                        var q = els.searchInput ? els.searchInput.value.trim() : '';
                        if (q) { filters.q = q; } else { delete filters.q; }
                        delete filters.page;
                        applyFilters(filters);
                    });
                }

                // Render initial active filters and results info from server-rendered state
                var initialFilters = getFiltersFromURL();
                renderActiveFilters(initialFilters);

                @if($searchQuery)
                renderResultsInfo({{ $products->total() }}, { q: {!! json_encode($searchQuery) !!} });
                @endif

                // Init infinite scroll for server-rendered page
                if (els.grid) {
                    state.infinitePage = parseInt(els.grid.dataset.currentPage || '1');
                    state.totalPages = parseInt(els.grid.dataset.totalPages || '1');
                    state.allLoaded = state.infinitePage >= state.totalPages;
                }

                if (els.paginationNav) {
                    setTimeout(function() { els.paginationNav.style.display = 'none'; }, 100);
                }

                initInfiniteScroll();
            });
        })();
    </script>
    @endpush
</x-app-layout>
@endif
