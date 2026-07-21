<x-app-layout title="Registro">
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-slate-100 dark:border-white/5 p-8">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white text-center mb-6 uppercase tracking-tight">Crear Cuenta</h1>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           class="w-full bg-white dark:bg-[#0a0f1c] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 text-gray-900 dark:text-white" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Correo electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full bg-white dark:bg-[#0a0f1c] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 text-gray-900 dark:text-white" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono') }}" required placeholder="8888-8888"
                           class="w-full bg-white dark:bg-[#0a0f1c] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 text-gray-900 dark:text-white" />
                    @error('telefono')
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

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full bg-white dark:bg-[#0a0f1c] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50 text-gray-900 dark:text-white" />
                </div>

                <button type="submit" class="w-full bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black py-3 rounded-xl transition-all active:scale-95 uppercase tracking-wider text-sm">
                    Crear Cuenta
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-[#00C4FF] hover:underline font-bold">Inicia sesión</a>
            </p>
        </div>
    </div>
</x-app-layout>