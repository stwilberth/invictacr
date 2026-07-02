<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>{{ $title ?? 'Admin' }} | Invicta Costa Rica</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-[#0a0f1c] text-white flex-shrink-0 hidden md:block overflow-y-auto">
            <div class="p-4 border-b border-white/10">
                <a href="/admin/dashboard" class="text-lg font-black text-[#00C4FF] uppercase tracking-tight">Invicta Admin</a>
            </div>
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-simple w-5"></i> Dashboard
                </a>
                <a href="{{ route('admin.products') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.products*') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-box w-5"></i> Productos
                </a>
                <a href="{{ route('admin.inventory') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.inventory') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-warehouse w-5"></i> Inventario
                </a>
                <a href="{{ route('admin.invoices') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.invoices') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-file-invoice w-5"></i> Facturas
                </a>
                <a href="{{ route('admin.clients') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.clients') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-users w-5"></i> Clientes
                </a>
                <a href="{{ route('admin.expenses') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.expenses') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-dollar-sign w-5"></i> Gastos
                </a>
                <a href="{{ route('admin.marketing') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.marketing') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-bullhorn w-5"></i> Marketing
                </a>
                <a href="{{ route('admin.campaigns') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.campaigns') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-rectangle-ad w-5"></i> Campañas
                </a>
                <a href="{{ route('admin.upcoming') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.upcoming') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-clock w-5"></i> Próximos
                </a>
                <a href="{{ route('admin.sync') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.sync') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-arrows-rotate w-5"></i> Sincronizar
                </a>
                <a href="{{ route('admin.search-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.search-logs') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-magnifying-glass w-5"></i> Búsquedas
                </a>
                <a href="{{ route('admin.optimize-images') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.optimize-images') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-image w-5"></i> Optimizar Imágenes
                </a>
                <hr class="border-white/10 my-4">
                <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/50 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fa-solid fa-arrow-left w-5"></i> Volver al sitio
                </a>
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900">
            <header class="bg-white dark:bg-[#0f172a] border-b border-gray-200 dark:border-white/5 px-4 md:px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-600 dark:text-gray-300 text-xl">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $title ?? 'Admin' }}</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="text-sm text-red-500 hover:text-red-400 font-bold">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>
            <div class="p-6">
                @if(session('message'))
                    <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
                        {{ session('message') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
                        {{ session('error') }}
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Mobile sidebar overlay --}}
    <template x-teleport="body">
        <div x-show="sidebarOpen" class="fixed inset-0 z-50 md:hidden" x-cloak>
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-black/50"></div>
            <aside class="fixed top-0 left-0 w-64 h-full bg-[#0a0f1c] text-white overflow-y-auto z-10">
                <div class="p-4 border-b border-white/10 flex items-center justify-between">
                    <a href="/admin/dashboard" class="text-lg font-black text-[#00C4FF] uppercase tracking-tight">Invicta Admin</a>
                    <button @click="sidebarOpen = false" class="text-white/60 hover:text-white text-xl">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-chart-simple w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.products') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.products*') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-box w-5"></i> Productos
                    </a>
                    <a href="{{ route('admin.inventory') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.inventory') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-warehouse w-5"></i> Inventario
                    </a>
                    <a href="{{ route('admin.invoices') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.invoices') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-file-invoice w-5"></i> Facturas
                    </a>
                    <a href="{{ route('admin.clients') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.clients') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-users w-5"></i> Clientes
                    </a>
                    <a href="{{ route('admin.expenses') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.expenses') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-dollar-sign w-5"></i> Gastos
                    </a>
                    <a href="{{ route('admin.marketing') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.marketing') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-bullhorn w-5"></i> Marketing
                    </a>
                    <a href="{{ route('admin.campaigns') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.campaigns') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-rectangle-ad w-5"></i> Campañas
                    </a>
                    <a href="{{ route('admin.upcoming') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.upcoming') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-clock w-5"></i> Próximos
                    </a>
                    <a href="{{ route('admin.sync') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.sync') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-arrows-rotate w-5"></i> Sincronizar
                    </a>
                    <a href="{{ route('admin.search-logs') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.search-logs') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-magnifying-glass w-5"></i> Búsquedas
                    </a>
                    <a href="{{ route('admin.optimize-images') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-colors {{ request()->routeIs('admin.optimize-images') ? 'bg-[#00C4FF]/10 text-[#00C4FF]' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-image w-5"></i> Optimizar Imágenes
                    </a>
                    <hr class="border-white/10 my-4">
                    <a href="/" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/50 hover:text-white hover:bg-white/5 transition-colors">
                        <i class="fa-solid fa-arrow-left w-5"></i> Volver al sitio
                    </a>
                </nav>
            </aside>
        </div>
    </template>

    @livewireScripts
    @stack('scripts')
</body>
</html>