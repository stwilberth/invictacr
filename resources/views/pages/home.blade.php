<x-app-layout title="InvictaCostaRica.com - Relojes Invicta 100% originales">
    <!-- Hero Slider -->
    <section>
        <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
            <div
                id="heroSlider"
                class="relative w-full aspect-[16/9] max-h-[75vh] rounded-2xl md:rounded-3xl overflow-hidden"
            >
                <div
                    class="flex transition-transform duration-700 ease-in-out w-full h-full"
                    id="sliderTrack"
                >
                    <a href="/relojes?brazalete=silicona" class="block w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('images/banners/silicona.png') }}" alt="Relojes Invicta con brazalete de silicona" class="w-full h-full object-contain" loading="eager" fetchpriority="high" />
                    </a>
                    <a href="/relojes/mujer" class="block w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('images/banners/relojes_invicta_mujer.png') }}" alt="Relojes Invicta para mujer" class="w-full h-full object-contain" loading="lazy" />
                    </a>
                    <a href="/relojes/hombre" class="block w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('images/banners/relojes_dia_del_padre.png') }}" alt="Relojes Invicta día del padre" class="w-full h-full object-contain" loading="lazy" />
                    </a>
                    <a href="/relojes?tipo_movimiento=automatico" class="block w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('images/banners/automaticos.png') }}" alt="Relojes Invicta automáticos" class="w-full h-full object-contain" loading="lazy" />
                    </a>
                    <a href="/relojes/hombre/invicta-50413" class="block w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('images/banners/racing.png') }}" alt="Reloj Invicta Speedway 50413" class="w-full h-full object-contain" loading="lazy" />
                    </a>
                    <a href="/resenas" class="block w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('images/banners/resennas.png') }}" alt="Reseñas de clientes Invicta" class="w-full h-full object-contain" loading="lazy" />
                    </a>
                </div>

                <button id="sliderPrev" aria-label="Anterior" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center transition-all backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button id="sliderNext" aria-label="Siguiente" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center transition-all backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <button data-slide="0" class="w-2.5 h-2.5 rounded-full bg-white/80 transition-all slider-dot"></button>
                    <button data-slide="1" class="w-2.5 h-2.5 rounded-full bg-white/40 transition-all slider-dot"></button>
                    <button data-slide="2" class="w-2.5 h-2.5 rounded-full bg-white/40 transition-all slider-dot"></button>
                    <button data-slide="3" class="w-2.5 h-2.5 rounded-full bg-white/40 transition-all slider-dot"></button>
                    <button data-slide="4" class="w-2.5 h-2.5 rounded-full bg-white/40 transition-all slider-dot"></button>
                    <button data-slide="5" class="w-2.5 h-2.5 rounded-full bg-white/40 transition-all slider-dot"></button>
                </div>
            </div>
        </div>

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
                        <div class="relative group cursor-pointer rounded-xl overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800">
                            <img src="https://vumbnail.com/{{ $vimeoId }}.jpg" alt="Reseña de cliente" class="w-full aspect-video object-cover" loading="lazy" />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition-all">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-play text-gray-900 text-xl ml-1"></i>
                                </div>
                            </div>
                            <a href="https://vimeo.com/{{ $vimeoId }}" target="_blank" rel="noopener" class="absolute inset-0 z-10"></a>
                        </div>
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

    // Hero Slider
    const track = document.getElementById('sliderTrack');
    const dots = document.querySelectorAll('.slider-dot');
    let current = 0;
    const total = dots.length;

    function goTo(index) {
        current = ((index % total) + total) % total;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, i) => {
            d.classList.toggle('bg-white/80', i === current);
            d.classList.toggle('bg-white/40', i !== current);
        });
    }

    dots.forEach((d) => { d.addEventListener('click', () => goTo(parseInt(d.dataset.slide))); });

    document.getElementById('sliderPrev')?.addEventListener('click', () => goTo(current - 1));
    document.getElementById('sliderNext')?.addEventListener('click', () => goTo(current + 1));

    // Touch/Swipe support
    let touchStartX = 0;
    const slider = document.getElementById('heroSlider');
    slider?.addEventListener('touchstart', (e) => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
    slider?.addEventListener('touchend', (e) => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) goTo(current + 1);
            else goTo(current - 1);
        }
    });

    let autoInterval = setInterval(() => goTo(current + 1), 5000);
    function restartAuto() {
        clearInterval(autoInterval);
        autoInterval = setInterval(() => goTo(current + 1), 5000);
    }
    function pauseAuto() {
        clearInterval(autoInterval);
    }
    // Reiniciar el contador tras interacción
    slider?.addEventListener('click', restartAuto);
    // Pausar autoplay en hover
    slider?.addEventListener('mouseenter', pauseAuto);
    slider?.addEventListener('mouseleave', restartAuto);
    // Pausar autoplay cuando la pestaña no es visible
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) pauseAuto();
        else restartAuto();
    });
    </script>
    @endpush
</x-app-layout>
