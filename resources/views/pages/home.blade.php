<x-app-layout title="Relojes Invicta Costa Rica" hide-nav>
    <!-- Search Bar Hero -->
    <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-40"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-24">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight">
                    Relojes Invicta <span class="text-[#00C4FF] whitespace-nowrap">Costa Rica</span>
                </h1>
                <p class="mt-3 text-base md:text-lg text-gray-400 max-w-xl mx-auto">
                    Encuentra el reloj perfecto entre cientos de modelos 100% originales
                </p>
            </div>
            <div class="max-w-xl mx-auto">
                <form action="/relojes" method="GET">
                    <div class="relative group">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Buscar por modelo, colección, color..."
                            class="w-full px-5 py-3.5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF]/50 focus:ring-2 focus:ring-[#00C4FF]/20 transition-all text-sm md:text-base pr-28"
                        />
                        @if(request('q'))
                        <button type="button" onclick="window.location.href='/relojes'" class="absolute right-20 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        @endif
                        <button
                            type="submit"
                            class="absolute right-1.5 top-1/2 -translate-y-1/2 px-4 py-2 bg-[#00C4FF] hover:bg-[#00a0cc] text-white rounded-xl transition-all active:scale-95 flex items-center gap-2"
                        >
                            <i class="fa-solid fa-search text-sm"></i>
                            <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline">Buscar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Feature Badges -->
    <section class="py-3 md:py-5 bg-white dark:bg-gray-950 overflow-hidden">
            <div id="badgesScroll" class="md:overflow-visible md:flex md:justify-center">
                <div class="badges-track md:flex md:flex-wrap md:justify-center md:gap-x-6 md:gap-y-1">
                    <div class="flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-shield-heart text-[#00C4FF] text-sm"></i>
                        <span>Garantía</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-truck-fast text-[#00C4FF] text-sm"></i>
                        <span>Envío Gratis</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-hand-holding-dollar text-[#00C4FF] text-sm"></i>
                        <span>Pago contra entrega*</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-calendar-check text-[#00C4FF] text-sm"></i>
                        <span>Sistema de apartado</span>
                    </div>
                    <!-- Duplicado para móvil (loop infinito) -->
                    <div class="md:hidden flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-shield-heart text-[#00C4FF] text-sm"></i>
                        <span>Garantía</span>
                    </div>
                    <div class="md:hidden flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-truck-fast text-[#00C4FF] text-sm"></i>
                        <span>Envío Gratis</span>
                    </div>
                    <div class="md:hidden flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-hand-holding-dollar text-[#00C4FF] text-sm"></i>
                        <span>Pago contra entrega*</span>
                    </div>
                    <div class="md:hidden flex items-center gap-1.5 sm:gap-2 text-gray-700 dark:text-gray-300 font-bold text-[10px] sm:text-xs uppercase tracking-wider whitespace-nowrap">
                        <i class="fa-solid fa-calendar-check text-[#00C4FF] text-sm"></i>
                        <span>Sistema de apartado</span>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <!-- Reseñas de Clientes -->
    <section class="py-8 md:py-16 bg-white dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-6 md:mb-10">
                <h2 class="text-lg md:text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-1 md:mb-2">Lo Que Dicen Nuestros Clientes</h2>
                <p class="text-xs md:text-base text-gray-500 dark:text-gray-400">Reseñas reales en video</p>
            </div>
            <div class="relative group">
                <button onclick="event.stopPropagation();this.parentElement.querySelector('.scroll-container').scrollBy({left: -600, behavior: 'smooth'});" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-white dark:bg-gray-800 rounded-full shadow-xl flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-all opacity-0 group-hover:opacity-100 -ml-5 border border-gray-200 dark:border-gray-700 cursor-pointer" aria-label="Anterior">
                    <i class="fa-solid fa-chevron-left text-gray-700 dark:text-gray-300 text-base"></i>
                </button>
                <button onclick="event.stopPropagation();this.parentElement.querySelector('.scroll-container').scrollBy({left: 600, behavior: 'smooth'});" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-white dark:bg-gray-800 rounded-full shadow-xl flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-all opacity-0 group-hover:opacity-100 -mr-5 border border-gray-200 dark:border-gray-700 cursor-pointer" aria-label="Siguiente">
                    <i class="fa-solid fa-chevron-right text-gray-700 dark:text-gray-300 text-base"></i>
                </button>
                <div class="overflow-x-auto scrollbar-hide scroll-container flex gap-3 sm:gap-4 pb-2">
                    @foreach(['1182052076', '1192763867', '1175093984', '1175094082', '1175094337', '1175094102', '1175094166', '1175094314'] as $vimeoId)
                    <div class="flex-shrink-0 w-[240px] sm:w-[280px]">
                        <button type="button" onclick="openVimeoModal('{{ $vimeoId }}')" class="relative group cursor-pointer rounded-xl overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800 w-full text-left">
                            <img src="https://vumbnail.com/{{ $vimeoId }}.jpg" alt="Reseña de cliente" class="w-full aspect-video object-cover" loading="lazy" />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition-all">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-play text-gray-900 text-xl ml-1"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="text-center mt-6 md:mt-8">
                <a href="/resenas" class="inline-flex items-center gap-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold px-8 py-3.5 rounded-full hover:opacity-90 transition-all duration-300 shadow-lg">
                    Ver todas las reseñas
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Productos Destacados -->
    @if($featuredProducts->count() > 0)
    <section class="py-12 md:py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8 md:mb-10">
                <h2 class="text-lg md:text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-1 md:mb-2">Productos Destacados</h2>
                <p class="text-xs md:text-base text-gray-500 dark:text-gray-400">Entrega inmediata en el GAM</p>
            </div>
            <div class="relative group">
                <button onclick="event.stopPropagation();this.parentElement.querySelector('.scroll-container').scrollBy({left: -400, behavior: 'smooth'});" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-white dark:bg-gray-800 rounded-full shadow-xl flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-all opacity-0 group-hover:opacity-100 -ml-5 border border-gray-200 dark:border-gray-700 cursor-pointer" aria-label="Anterior">
                    <i class="fa-solid fa-chevron-left text-gray-700 dark:text-gray-300 text-base"></i>
                </button>
                <button onclick="event.stopPropagation();this.parentElement.querySelector('.scroll-container').scrollBy({left: 400, behavior: 'smooth'});" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-white dark:bg-gray-800 rounded-full shadow-xl flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-all opacity-0 group-hover:opacity-100 -mr-5 border border-gray-200 dark:border-gray-700 cursor-pointer" aria-label="Siguiente">
                    <i class="fa-solid fa-chevron-right text-gray-700 dark:text-gray-300 text-base"></i>
                </button>
                <div class="overflow-x-auto scrollbar-hide scroll-container flex gap-3 sm:gap-4 pb-2">
                    @foreach($featuredProducts as $product)
                    <div class="flex-shrink-0 w-[140px] sm:w-[180px] md:w-[220px]">
                        <x-priority-card :product="$product" />
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Categorías por Género -->
    <section class="py-10 md:py-16 bg-white dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-lg md:text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-1 md:mb-2">Explora Por Género</h2>
                <p class="text-xs md:text-base text-gray-500 dark:text-gray-400">Encuentra el reloj perfecto para ti</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-8">
                <a href="/relojes/hombre" class="group relative block w-full aspect-[3/4] overflow-hidden rounded-xl md:rounded-[2.5rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 bg-gray-900">
                    <img src="{{ asset('images/banners/hombre.png') }}" alt="Hombre" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 z-20 flex flex-col items-center justify-center p-3 md:p-10 text-center">
                        <h3 class="text-xl md:text-5xl font-black text-white uppercase leading-none mb-1 md:mb-4">Hombre</h3>
                        <div class="flex items-center gap-2 text-white/70 font-semibold text-[10px] md:text-base group-hover:text-white transition-colors">
                            Ver Colección
                            <i class="fa-solid fa-arrow-right text-xs md:text-sm group-hover:translate-x-1 transition-transform text-amber-500"></i>
                        </div>
                    </div>
                    <div class="absolute inset-0 border border-white/5 rounded-[1.5rem] md:rounded-[2.5rem] z-30 group-hover:border-white/20 transition-colors pointer-events-none"></div>
                </a>
                <a href="/relojes/mujer" class="group relative block w-full aspect-[3/4] overflow-hidden rounded-xl md:rounded-[2.5rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 bg-gray-900">
                    <img src="{{ asset('images/banners/mujer.png') }}" alt="Mujer" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 z-20 flex flex-col items-center justify-center p-3 md:p-10 text-center">
                        <h3 class="text-xl md:text-5xl font-black text-white uppercase leading-none mb-1 md:mb-4">Mujer</h3>
                        <div class="flex items-center gap-2 text-white/70 font-semibold text-[10px] md:text-base group-hover:text-white transition-colors">
                            Ver Colección
                            <i class="fa-solid fa-arrow-right text-xs md:text-sm group-hover:translate-x-1 transition-transform text-rose-400"></i>
                        </div>
                    </div>
                    <div class="absolute inset-0 border border-white/5 rounded-[1.5rem] md:rounded-[2.5rem] z-30 group-hover:border-white/20 transition-colors pointer-events-none"></div>
                </a>
                <a href="/relojes/unisex" class="group relative block w-full aspect-[3/4] overflow-hidden rounded-xl md:rounded-[2.5rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 bg-gray-900">
                    <img src="{{ asset('images/banners/unisex.png') }}" alt="Unisex" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 z-20 flex flex-col items-center justify-center p-3 md:p-10 text-center">
                        <h3 class="text-xl md:text-5xl font-black text-white uppercase leading-none mb-1 md:mb-4">Unisex</h3>
                        <div class="flex items-center gap-2 text-white/70 font-semibold text-[10px] md:text-base group-hover:text-white transition-colors">
                            Ver Colección
                            <i class="fa-solid fa-arrow-right text-xs md:text-sm group-hover:translate-x-1 transition-transform text-emerald-400"></i>
                        </div>
                    </div>
                    <div class="absolute inset-0 border border-white/5 rounded-[1.5rem] md:rounded-[2.5rem] z-30 group-hover:border-white/20 transition-colors pointer-events-none"></div>
                </a>
            </div>
        </div>
    </section>

    <!-- WhatsApp Section -->
    <section id="newsletter" class="py-5 bg-[#00C4FF] dark:bg-blue-800">
        <div class="max-w-4xl mx-auto px-4 text-center text-gray-800 dark:text-white">
            <h2 class="text-2xl font-bold mb-4">¡Escríbenos por WhatsApp!</h2>
            <p class="mb-4">Envíanos un mensaje y te contactaremos con las mejores ofertas y novedades de relojes Invicta.</p>
            <a href="https://wa.me/50686711422?text=Hola,%20me%20interesan%20las%20ofertas%20y%20novedades%20de%20relojes%20Invicta" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-green-600 text-white font-bold px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-300">
                <i class="fab fa-whatsapp text-lg"></i>
                <span>Enviar mensaje</span>
            </a>
        </div>
    </section>

    @push('scripts')
    <style>
        .badges-track {
            display: flex;
            gap: 2.5rem;
            width: max-content;
        }
        #badgesScroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        #badgesScroll::-webkit-scrollbar {
            display: none;
        }

        .scroll-container.styled {
            scrollbar-width: thin;
            scrollbar-color: #00c4ff #f3f4f6;
            padding-bottom: 20px;
        }

        .scroll-container.styled::-webkit-scrollbar {
            height: 8px;
        }

        .scroll-container.styled::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }

        .scroll-container.styled::-webkit-scrollbar-thumb {
            background: #00c4ff;
            border-radius: 10px;
            border: 2px solid #f3f4f6;
        }

        .scroll-container.styled::-webkit-scrollbar-thumb:hover {
            background: #00b0e6;
        }

        .dark .scroll-container.styled::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .dark .scroll-container.styled::-webkit-scrollbar-thumb {
            border-color: #1f2937;
        }
    </style>

    <script>
    // Badges auto-scroll on mobile
    (function() {
        const el = document.getElementById('badgesScroll');
        if (!el || window.innerWidth >= 768) return;
        let interval, pauseTimer;
        function start() {
            stop();
            interval = setInterval(() => {
                if (el.scrollLeft >= el.scrollWidth / 2) {
                    el.scrollLeft = 0;
                } else {
                    el.scrollLeft += 0.5;
                }
            }, 16);
        }
        function stop() { if (interval) { clearInterval(interval); interval = null; } }
        function pause() { stop(); clearTimeout(pauseTimer); pauseTimer = setTimeout(start, 3000); }
        start();
        el.addEventListener('touchstart', pause, { passive: true });
        el.addEventListener('mousedown', pause);
        el.addEventListener('scroll', () => { if (el.scrollLeft >= el.scrollWidth / 2) el.scrollLeft = 0; });
    })();
    </script>
    @endpush
</x-app-layout>
