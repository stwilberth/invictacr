<x-app-layout title="Mi Cuenta">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-5xl mx-auto px-4">

            @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-sm font-bold text-emerald-700 dark:text-emerald-400 text-center">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold text-red-700 dark:text-red-400">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-10">
                <div class="w-16 h-16 rounded-full bg-[#00C4FF]/10 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user text-2xl text-[#00C4FF]"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Mi Cuenta</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                </div>
            </div>

            {{-- Acciones Rápidas --}}
            <div class="mb-10">
                <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight mb-5">Acciones Rápidas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow group">
                        <div class="w-10 h-10 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Mi Perfil</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Editar datos personales</p>
                        </div>
                    </a>
                    <a href="{{ route('profile.show') }}#direccion" class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow group">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Mi Dirección</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dirección de envío guardada</p>
                        </div>
                    </a>
                    <a href="{{ route('order-tracking.show') }}" class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow group">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Mis Pedidos</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Ver historial completo</p>
                        </div>
                    </a>
                    <a href="{{ route('cart.show') }}" class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow group">
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Mi Carrito</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Revisar compras pendientes</p>
                        </div>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow group">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Catálogo</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Explorar relojes</p>
                        </div>
                    </a>

                    {{-- contactar whatsapp --}}
                    <a href="https://wa.me/50686711422" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow group">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400 shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fa-brands fa-whatsapp text-[#25D366]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Contactar</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">WhatsApp</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Cerrar Sesión --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-6 py-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-xl font-bold text-sm uppercase tracking-tight transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar Sesión
                </button>
            </form>

        </div>
    </section>
</x-app-layout>
