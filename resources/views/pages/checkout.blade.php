<x-app-layout title="Checkout - Invicta Costa Rica">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title title="Finalizar Compra" highlight="Compra" subtitle="Completá tus datos para procesar el pedido." />

            @if(session('error'))
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold text-red-700 dark:text-red-400 text-center">
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('checkout.process') }}" x-data="{ paymentMethod: '{{ old('payment_method', 'paypal') }}', loading: false }" @submit="loading = true">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    {{-- Formulario --}}
                    <div class="lg:col-span-2 space-y-5">
                        {{-- Datos personales --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                                    <i class="fa-solid fa-user text-xl"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Datos Personales</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Nombre completo *</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Correo electrónico *</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Teléfono *</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" required placeholder="8671-1422"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                    @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Dirección de envío --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                                    <i class="fa-solid fa-truck text-xl"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dirección de Envío</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Dirección completa *</label>
                                    <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}" required placeholder="Barrio, calle, avenida, número de casa..."
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Provincia *</label>
                                    <select name="province" required class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                        <option value="">Seleccionar...</option>
                                        @foreach(['San José', 'Alajuela', 'Cartago', 'Heredia', 'Guanacaste', 'Puntarenas', 'Limón'] as $prov)
                                            <option value="{{ $prov }}" {{ old('province', $user->province ?? '') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                        @endforeach
                                    </select>
                                    @error('province')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Cantón *</label>
                                    <input type="text" name="canton" value="{{ old('canton', $user->canton ?? '') }}" required placeholder="Ej: Escazú, Santa Ana..."
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                    @error('canton')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Notas (opcional)</label>
                                    <textarea name="notes" rows="2" placeholder="Instrucciones de entrega, referencias..."
                                              class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all resize-none">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Método de pago --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                                    <i class="fa-solid fa-credit-card text-xl"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Método de Pago</h2>
                            </div>
                            <div class="space-y-3">
                                <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                                       :class="paymentMethod === 'paypal' ? 'border-[#00C4FF] bg-[#00C4FF]/5' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                    <input type="radio" name="payment_method" value="paypal" x-model="paymentMethod" {{ old('payment_method') === 'paypal' ? 'checked' : '' }} class="accent-[#00C4FF]">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-brands fa-paypal text-[#003087] text-2xl"></i>
                                        <div>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">PayPal</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400">Pago seguro con tarjeta o cuenta PayPal</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                                       :class="paymentMethod === 'sinpe' ? 'border-[#00C4FF] bg-[#00C4FF]/5' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                    <input type="radio" name="payment_method" value="sinpe" x-model="paymentMethod" {{ old('payment_method') === 'sinpe' ? 'checked' : '' }} class="accent-[#00C4FF]">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-mobile-screen text-emerald-500 text-2xl"></i>
                                        <div>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">SINPE Móvil</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400">Enviar al 8671-1422</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                                       :class="paymentMethod === 'transferencia' ? 'border-[#00C4FF] bg-[#00C4FF]/5' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                    <input type="radio" name="payment_method" value="transferencia" x-model="paymentMethod" {{ old('payment_method') === 'transferencia' ? 'checked' : '' }} class="accent-[#00C4FF]">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-building-columns text-blue-500 text-2xl"></i>
                                        <div>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">Transferencia Bancaria</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400">Datos bancarios por WhatsApp</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                                       :class="paymentMethod === 'contra_entrega' ? 'border-[#00C4FF] bg-[#00C4FF]/5' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                    <input type="radio" name="payment_method" value="contra_entrega" x-model="paymentMethod" {{ old('payment_method') === 'contra_entrega' ? 'checked' : '' }} class="accent-[#00C4FF]">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-hand-holding-dollar text-amber-500 text-2xl"></i>
                                        <div>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">Contra Entrega</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400">Solo GAM - Paga al recibir</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')<p class="text-xs text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Resumen --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8 sticky top-20">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Tu Pedido</h2>
                            <div class="space-y-3 mb-5 max-h-64 overflow-y-auto">
                                @foreach($cart->items as $item)
                                @if($item->product)
                                <div class="flex gap-3 items-center">
                                    @php
                                        $model = preg_replace('/^invicta-/i', '', $item->product->modelo ?? '');
                                        $thumbUrl = null;
                                        if ($item->product->imagen && str_starts_with($item->product->imagen, '/storage/relojes/')) {
                                            $thumbModelo = pathinfo(basename($item->product->imagen), PATHINFO_FILENAME);
                                            if (file_exists(public_path("storage/relojes/thumbs/{$thumbModelo}.webp"))) {
                                                $thumbUrl = "/storage/relojes/thumbs/{$thumbModelo}.webp";
                                            }
                                        } elseif (file_exists(public_path("storage/relojes/thumbs/{$model}.webp"))) {
                                            $thumbUrl = "/storage/relojes/thumbs/{$model}.webp";
                                        }
                                    @endphp
                                    <div class="w-10 h-10 flex-shrink-0 bg-gray-50 dark:bg-white/5 rounded-lg overflow-hidden flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                        @if($thumbUrl)
                                            <img src="{{ $thumbUrl }}" alt="" class="w-full h-full object-contain" />
                                        @else
                                            <span class="text-[8px] font-bold text-gray-400">{{ $model }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $item->product->modelo }}</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400">x{{ $item->quantity }}</p>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">₡{{ number_format($item->line_total, 0) }}</span>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-2 mb-4">
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Subtotal</span>
                                    <span class="font-bold text-gray-700 dark:text-gray-200">₡{{ number_format($cart->subtotal, 0) }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Envío</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">Gratis</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900 dark:text-white">Total</span>
                                    <span class="text-xl font-black text-red-600 dark:text-red-400">₡{{ number_format($cart->total, 0) }}</span>
                                </div>
                            </div>
                            <button type="submit" :disabled="loading"
                                    class="w-full py-3.5 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <template x-if="!loading">
                                    <span><i class="fa-solid fa-lock mr-2"></i> <span x-text="paymentMethod === 'paypal' ? 'Pagar con PayPal' : 'Confirmar Pedido'"></span></span>
                                </template>
                                <template x-if="loading">
                                    <span><i class="fa-solid fa-spinner fa-spin mr-2"></i> Procesando...</span>
                                </template>
                            </button>
                            <p class="text-[10px] text-center text-gray-400 dark:text-gray-500 mt-4">
                                <i class="fa-solid fa-shield-check mr-1"></i> Tu información está protegida y es segura.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
