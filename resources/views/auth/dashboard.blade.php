<x-app-layout title="Mi Cuenta">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-8">Mi Cuenta</h1>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-slate-100 dark:border-white/5 p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full bg-[#00C4FF]/10 flex items-center justify-center">
                    <i class="fa-solid fa-user text-2xl text-[#00C4FF]"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 font-bold px-6 py-3 rounded-xl transition-all">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</x-app-layout>