<x-app-layout title="Iniciar Sesión">
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-slate-100 dark:border-white/5 p-8">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white text-center mb-6 uppercase tracking-tight">Iniciar Sesión</h1>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Correo electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-white dark:bg-[#0a0f1c] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 text-gray-900 dark:text-white" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
                    <input type="password" name="password" id="password" required
                           class="w-full bg-white dark:bg-[#0a0f1c] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 text-gray-900 dark:text-white" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <input type="checkbox" name="remember" class="text-[#00C4FF] rounded">
                        Recordarme
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black py-3 rounded-xl transition-all active:scale-95 uppercase tracking-wider text-sm">
                    Ingresar
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-[#00C4FF] hover:underline font-bold">Regístrate aquí</a>
            </p>
        </div>
    </div>
</x-app-layout>