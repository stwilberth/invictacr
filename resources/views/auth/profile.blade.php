<x-app-layout title="Mi Perfil">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-3xl mx-auto px-4">

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
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-gray-100 dark:bg-white/5 rounded-xl flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors shrink-0">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Mi Perfil</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Administra tu información personal y dirección de envío</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                {{-- Datos Personales --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 sm:p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Datos Personales</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Nombre *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Correo Electrónico *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Teléfono *</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="8671-1422"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                    </div>
                </div>

                {{-- Dirección de Envío --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 sm:p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dirección de Envío</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Se usará al hacer pedidos</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Dirección completa</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="Barrio, calle, avenida, número de casa..."
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Provincia</label>
                            <select name="province" class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                <option value="">Seleccionar...</option>
                                @foreach(['San José', 'Alajuela', 'Cartago', 'Heredia', 'Guanacaste', 'Puntarenas', 'Limón'] as $prov)
                                    <option value="{{ $prov }}" {{ old('province', $user->province) === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Cantón</label>
                            <input type="text" name="canton" value="{{ old('canton', $user->canton) }}" placeholder="Ej: Escazú, Santa Ana..."
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                    </div>
                </div>

                {{-- Contraseña --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 sm:p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Contraseña</h2>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Dejar vacío si no deseas cambiarla</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Contraseña Actual</label>
                            <input type="password" name="current_password" placeholder="Necesaria para cambiar contraseña"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Nueva Contraseña</label>
                            <input type="password" name="password" placeholder="Mínimo 8 caracteres"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" placeholder="Repetir contraseña"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-check mr-2"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-sm uppercase tracking-tight transition-all text-center">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>
    </section>
</x-app-layout>
