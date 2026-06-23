@props(['idPrefix' => 'desktop', 'className' => ''])

<div class="relative {{ $className }}" x-data="searchState()" @click.away="results = []">
    <input type="text"
           x-model="query"
           x-on:input.debounce.300ms="search"
           placeholder="Buscar relojes..."
           class="w-full bg-white/10 text-white placeholder-gray-400 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 border border-white/10" />

    <div x-show="results.length > 0"
         class="absolute top-full left-0 right-0 mt-2 bg-[#0f172a] border border-white/10 rounded-xl shadow-2xl z-50 overflow-hidden"
         style="display: none;">
        <template x-for="product in results" :key="product.id">
            <a :href="'/relojes/' + product.genero + '/' + product.slug"
               class="flex items-center gap-3 px-4 py-3 text-white/80 hover:text-white hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                <img :src="product.imagen" :alt="product.title" class="w-10 h-10 object-cover rounded" />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate" x-text="product.title"></p>
                    <p class="text-xs text-gray-400" x-text="product.modelo"></p>
                </div>
            </a>
        </template>
    </div>
</div>

@push('scripts')
<script>
    function searchState() {
        return {
            query: '',
            results: [],
            async search() {
                if (this.query.length < 2) {
                    this.results = [];
                    return;
                }
                try {
                    const response = await fetch('/api/products/search?q=' + encodeURIComponent(this.query));
                    const data = await response.json();
                    this.results = data.slice(0, 6);
                } catch (e) {
                    console.error('Search error', e);
                }
            }
        }
    }
</script>
@endpush