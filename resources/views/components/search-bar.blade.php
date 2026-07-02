@props(['idPrefix' => 'desktop', 'className' => ''])

<div class="relative {{ $className }}">
    <form action="/relojes" method="GET" class="flex items-center gap-0">
        <div class="relative flex-1">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Buscar relojes..."
                   class="w-full bg-white/10 text-white placeholder-gray-400 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 border border-white/10 pr-8" />
            @if(request('q'))
            <button type="button" onclick="window.location.href='/relojes'" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            @endif
        </div>
        <button type="submit" aria-label="Buscar" class="bg-white/10 text-gray-400 hover:text-white hover:bg-white/20 border border-l-0 border-white/10 rounded-r-lg px-3 py-2 text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
        </button>
    </form>
</div>
