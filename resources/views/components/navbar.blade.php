@props(['q' => null])
@php
    $currentPath = request()->path();
    $cartCount = 0;
    if (session()->has('cart_session_id') || auth()->check()) {
        try {
            $cartService = app(\App\Services\CartService::class);
            $cartCount = $cartService->getItemCount();
        } catch (\Exception $e) {}
    }
@endphp

<nav class="bg-[#0a0f1c] shadow-lg w-full z-[60] print:hidden border-b border-white/5"
     x-data="navbarState()"
     x-init="init()">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 md:h-20 items-center">
            <div class="flex-shrink-0 flex items-center justify-center mr-2 md:mr-8">
                <div class="flex items-center gap-2">
                    <a href="/" class="flex-shrink-0">
                        <img src="{{ asset('logo.webp') }}" alt="Invicta Costa Rica" class="h-12 md:h-11 w-auto hover:opacity-90 transition-opacity" width="160" height="160" onerror="this.src='{{ asset('logo.png') }}'" />
                    </a>
                    <div class="hidden md:grid gap-0.5">
                        <a href="/" class="group text-xs md:text-xl">
                            <p class="text-white/80 font-bold tracking-wider group-hover:text-white transition-colors">Invicta<span class="text-[#00C4FF]">CostaRica</span>.com</p>
                        </a>
                        <a href="https://wa.me/50686711422" target="_blank" rel="noopener noreferrer" class="text-[11px] md:text-xs text-white/50 hover:text-white flex items-center gap-1 transition-colors">
                            <i class="fa-brands fa-whatsapp text-[#25D366]"></i>
                            <span>8671-1422</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-1 lg:space-x-4">
                <div class="hidden lg:block w-64 mr-2">
                    <x-search-bar />
                </div>
                <a href="/relojes"
                   class="{{ str_starts_with($currentPath, 'relojes') ? 'text-[#00C4FF] bg-white/5' : 'text-white/90' }} hover:text-[#00C4FF] px-3 py-2 rounded-md text-sm lg:text-base font-black uppercase tracking-tighter transition-all duration-200">
                    Relojes
                </a>

                <a href="/resenas"
                   class="{{ $currentPath === 'resenas' ? 'text-[#00C4FF] bg-white/5' : 'text-white/90' }} hover:text-[#00C4FF] px-3 py-2 rounded-md text-sm lg:text-base font-black uppercase tracking-tighter transition-all duration-200">
                    Reseñas
                </a>

                <a href="/garantia"
                   class="{{ $currentPath === 'garantia' ? 'text-[#00C4FF] bg-white/5' : 'text-white/90' }} hover:text-[#00C4FF] px-3 py-2 rounded-md text-sm lg:text-base font-black uppercase tracking-tighter transition-all duration-200 flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-check text-xs {{ $currentPath === 'garantia' ? 'text-[#00C4FF]' : 'text-[#00C4FF]/70' }}"></i>
                    Garantía
                </a>


                <a href="{{ route('cart.show') }}" class="relative text-white/80 hover:text-[#00C4FF] p-2 rounded-full transition-all duration-300 hover:bg-white/5" title="Carrito">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if($cartCount > 0)
                     <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>

                <button @click="toggleTheme"
                        class="text-white/80 hover:text-[#00C4FF] p-2 rounded-full transition-all duration-300 hover:bg-white/5"
                        title="Cambiar tema">
                    <template x-if="theme === 'light'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </template>
                    <template x-if="theme === 'dark'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </template>
                </button>

                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                            class="text-white/70 hover:text-white px-3 py-2 rounded-md text-sm lg:text-base font-black uppercase tracking-tighter transition-colors flex items-center gap-1">
                        <span>Soporte</span>
                        <svg class="w-3 h-3 opacity-50 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-[#0f172a] border border-white/10 rounded-xl shadow-2xl py-2 z-50 overflow-hidden"
                         style="display: none;">
                        <a href="/mis-pedidos" class="block px-4 py-2 text-sm text-[#00C4FF] hover:bg-white/5 transition-colors font-bold">Mis Pedidos</a>
                        <a href="/como-comprar" class="block px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/5 transition-colors">Cómo Comprar</a>
                        <a href="/sobre-nosotros" class="block px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/5 transition-colors">Sobre Nosotros</a>
                        <a href="/resistencia-agua" class="block px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/5 transition-colors">Resistencia al Agua</a>
                        <a href="https://correos.go.cr/rastreo/" target="_blank" class="block px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/5 transition-colors">Rastrear Envío</a>
                    </div>
                </div>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-[#00C4FF] px-3 py-2 rounded-md text-sm lg:text-base font-black uppercase tracking-tighter transition-colors flex items-center gap-1.5" title="Mi Cuenta">
                        <i class="fa-solid fa-circle-user text-xs text-[#00C4FF]/70"></i>
                        <span class="hidden xl:inline">Mi Cuenta</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white/80 hover:text-[#00C4FF] px-3 py-2 rounded-md text-sm lg:text-base font-black uppercase tracking-tighter transition-colors">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="bg-[#00C4FF] hover:bg-[#00a3d6] text-white px-4 py-2 rounded-lg text-sm lg:text-base font-black uppercase tracking-tighter transition-colors">
                        Registrarme
                    </a>
                @endauth
            </div>

            <div class="md:hidden flex items-center gap-1">
                <a href="/relojes" class="text-white p-2 hover:bg-white/5 rounded-lg transition-colors" title="Catálogo">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </a>
                <a href="{{ route('cart.show') }}" class="relative text-white p-2 hover:bg-white/5 rounded-lg transition-colors" title="Carrito">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>
                <button @click="mobileSearchOpen = !mobileSearchOpen"
                        class="p-2 rounded-lg transition-colors"
                        :class="mobileSearchOpen ? 'text-[#00C4FF] bg-white/5' : 'text-white hover:bg-white/5'"
                        aria-label="Toggle Search">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-white p-2 hover:bg-white/5 rounded-lg transition-colors"
                        aria-label="Toggle Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileSearchOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-y-4 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-4 opacity-0"
         class="md:hidden absolute top-[56px] left-0 right-0 bg-[#0f172a] z-50 border-b border-white/10"
         style="display: none;">
        <div class="p-4">
            <x-search-bar />
        </div>
    </div>

    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="md:hidden fixed inset-0 bg-[#0a0f1c] z-40 overflow-y-auto"
         style="display: none;">
        <div class="min-h-screen flex flex-col pt-4 pb-6">
            <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Menú</span>
                <button @click="mobileMenuOpen = false"
                        class="text-gray-400 hover:text-white p-2 -mr-2 rounded-lg hover:bg-white/10 transition-colors"
                        aria-label="Cerrar menú">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <a href="/relojes"
               class="{{ str_starts_with($currentPath, 'relojes') ? 'text-[#00C4FF] bg-white/5' : 'text-gray-300' }} hover:text-white block px-4 py-4 text-lg font-black uppercase tracking-tight border-b border-white/5">
                Relojes
            </a>
            <a href="/resenas"
               class="{{ $currentPath === 'resenas' ? 'text-[#00C4FF] bg-white/5' : 'text-gray-300' }} hover:text-white block px-4 py-4 text-lg font-black uppercase tracking-tight border-b border-white/5">
                Reseñas
            </a>
            <a href="/garantia"
               class="{{ $currentPath === 'garantia' ? 'text-[#00C4FF] bg-white/5' : 'text-gray-300' }} hover:text-white block px-4 py-4 text-lg font-black uppercase tracking-tight border-b border-white/5">
                Garantía Real
            </a>

            <div class="border-b border-white/5" x-data="{ mobileSupportOpen: false }">
                <button @click="mobileSupportOpen = !mobileSupportOpen"
                        class="text-gray-300 hover:text-white w-full text-left px-4 py-4 text-lg font-black uppercase tracking-tight border-b border-white/5 flex justify-between items-center">
                    <span>Soporte</span>
                    <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': mobileSupportOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="mobileSupportOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="max-h-0"
                     x-transition:enter-end="max-h-60"
                     style="display: none;">
                    <div class="pl-6 space-y-3 py-3">
                        <a href="/mis-pedidos" class="text-[#00C4FF] hover:text-white block py-1 text-base font-bold transition-colors">Mis Pedidos</a>
                        <a href="/como-comprar" class="text-gray-400 hover:text-white block py-1 text-base font-medium transition-colors">Cómo Comprar</a>
                        <a href="/sobre-nosotros" class="text-gray-400 hover:text-white block py-1 text-base font-medium transition-colors">Sobre Nosotros</a>
                        <a href="/resistencia-agua" class="text-gray-400 hover:text-white block py-1 text-base font-medium transition-colors">Resistencia al Agua</a>
                        <a href="https://correos.go.cr/rastreo/" target="_blank" class="text-gray-400 hover:text-white block py-1 text-base font-medium transition-colors">Rastrear Envío</a>
                    </div>
                </div>
            </div>

            @auth
                <div class="py-6 border-b border-white/5">
                    <div class="px-4 mb-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Sesión activa</p>
                        <p class="text-white font-black truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 px-4">
                        <a href="/dashboard" class="flex items-center justify-center bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl font-bold transition-colors">Mi Cuenta</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center justify-center bg-red-500/10 hover:bg-red-500/20 text-red-400 py-3 rounded-xl font-bold transition-colors">Salir</a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            @else
                <div class="py-6 border-b border-white/5 px-4 space-y-3">
                    <a href="{{ route('login') }}" class="flex items-center justify-center bg-[#00C4FF] hover:bg-[#00a3d6] text-white py-4 rounded-xl font-black uppercase tracking-tighter transition-colors">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center bg-white/5 hover:bg-white/10 text-white py-4 rounded-xl font-bold transition-colors">Registrarme</a>
                </div>
            @endauth

            <div class="pt-6 px-4">
                <button @click="toggleTheme"
                        class="flex items-center justify-center space-x-3 w-full bg-white/5 hover:bg-white/10 text-white py-4 rounded-xl font-bold transition-all duration-300">
                    <template x-if="theme === 'light'">
                        <span class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Cambiar a Modo Oscuro</span>
                        </span>
                    </template>
                    <template x-if="theme === 'dark'">
                        <span class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-[#00C4FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <span>Cambiar a Modo Claro</span>
                        </span>
                    </template>
                </button>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    function navbarState() {
        return {
            mobileMenuOpen: false,
            mobileSearchOpen: false,
            theme: 'light',
            init() {
                this.theme = localStorage.getItem('theme') || 'light';
                if (this.theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                this.$watch('mobileMenuOpen', (val) => {
                    document.body.style.overflow = val ? 'hidden' : '';
                    document.documentElement.style.overflow = val ? 'hidden' : '';
                });
                this.$watch('mobileSearchOpen', (val) => {
                    if (this.mobileMenuOpen) return;
                    document.body.style.overflow = val ? 'hidden' : '';
                    document.documentElement.style.overflow = val ? 'hidden' : '';
                });
            },
            toggleTheme() {
                this.theme = this.theme === 'light' ? 'dark' : 'light';
                localStorage.setItem('theme', this.theme);
                if (this.theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }
    }
</script>
@endpush
