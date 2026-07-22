<x-app-layout title="Mis Pedidos - Invicta Costa Rica">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title title="Mis Pedidos" highlight="Pedidos" subtitle="Consultá el estado de tus compras realizadas en Invicta Costa Rica." />

            @if(session('error'))
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold text-red-700 dark:text-red-400 text-center">
                {{ session('error') }}
            </div>
            @endif

            {{-- ═══════════ USUARIO AUTENTICADO ═══════════ --}}
            @auth
                @if(isset($invoices) && $invoices->isNotEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-8">
                        Tus pedidos registrados con <strong class="text-gray-700 dark:text-gray-200">{{ auth()->user()->email }}</strong>
                    </p>

                    <div class="space-y-6 max-w-4xl mx-auto">
                        @foreach($invoices as $invoice)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-5 sm:p-8">
                            {{-- Header --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-5 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                                        <i class="fa-solid fa-file-invoice text-lg sm:text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Factura</p>
                                        <p class="text-base sm:text-lg font-black text-gray-900 dark:text-white break-all">{{ $invoice->invoice_number }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($invoice->status === 'facturado')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-4 sm:py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-[10px] sm:text-xs font-bold uppercase">
                                        <i class="fa-solid fa-circle-check"></i> Pagado
                                    </span>
                                    @elseif($invoice->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-4 sm:py-1.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full text-[10px] sm:text-xs font-bold uppercase">
                                        <i class="fa-solid fa-clock"></i> Pendiente
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-4 sm:py-1.5 bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400 rounded-full text-[10px] sm:text-xs font-bold uppercase">
                                        {{ $invoice->status }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Grid --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-5">
                                <div class="bg-gray-50 dark:bg-white/5 p-3 rounded-xl border border-gray-200 dark:border-white/10">
                                    <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Fecha</p>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-white/5 p-3 rounded-xl border border-gray-200 dark:border-white/10">
                                    <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Pago</p>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white truncate" title="{{ $invoice->payment_method }}">
                                        @if($invoice->payment_method === 'paypal')
                                            <i class="fa-brands fa-paypal text-[#003087] mr-1"></i>PayPal
                                        @elseif($invoice->payment_method === 'sinpe')
                                            <i class="fa-solid fa-mobile-screen text-emerald-500 mr-1"></i>SINPE
                                        @elseif($invoice->payment_method === 'transferencia')
                                            <i class="fa-solid fa-building-columns text-blue-500 mr-1"></i>Transf.
                                        @elseif($invoice->payment_method === 'contra_entrega')
                                            <i class="fa-solid fa-hand-holding-dollar text-amber-500 mr-1"></i>Efectivo
                                        @else
                                            {{ $invoice->payment_method ?? 'N/A' }}
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 dark:bg-white/5 p-3 rounded-xl border border-gray-200 dark:border-white/10">
                                    <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Envío</p>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white capitalize">{{ $invoice->shipping_status ?? 'Pendiente' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-white/5 p-3 rounded-xl border border-gray-200 dark:border-white/10">
                                    <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Total</p>
                                    <p class="text-xs sm:text-sm font-black text-red-600 dark:text-red-400">₡{{ number_format($invoice->total, 0) }}</p>
                                </div>
                            </div>

                            {{-- Productos --}}
                            <div class="mb-5">
                                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Productos</p>
                                <div class="space-y-3">
                                    @foreach($invoice->items as $item)
                                    <div class="flex items-center gap-3 sm:gap-4 bg-gray-50 dark:bg-white/5 p-3 rounded-xl border border-gray-200 dark:border-white/10">
                                        @php
                                            $model = preg_replace('/^invicta-/i', '', $item->product?->modelo ?? $item->product_model ?? '');
                                            $cdnBase = 'https://cdn.invictacostarica.com';
                                            $thumbUrl = ($item->product?->imagen) ? "{$cdnBase}/relojes/thumbs/{$model}.webp" : null;
                                        @endphp
                                        <div class="w-10 h-10 sm:w-14 sm:h-14 flex-shrink-0 bg-gray-50 dark:bg-white/5 rounded-xl overflow-hidden flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                            @if($thumbUrl)
                                                <img src="{{ $thumbUrl }}" alt="" class="w-full h-full object-contain p-1" />
                                            @else
                                                <span class="text-[8px] sm:text-[9px] font-bold text-gray-400 dark:text-gray-500">{{ $model }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white truncate">{{ $item->product_name }}</p>
                                            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">Mod: {{ $item->product_model }} · Cant: {{ $item->quantity }}</p>
                                        </div>
                                        <span class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white shrink-0">₡{{ number_format($item->subtotal, 0) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Acciones --}}
                            <div class="flex flex-col sm:flex-row gap-3">
                                @if($invoice->status === 'facturado')
                                <a href="{{ route('invoice.pdf', $invoice->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[11px] sm:text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                    <i class="fa-solid fa-file-pdf text-sm sm:text-base"></i> Descargar Factura
                                </a>
                                @endif
                                <a href="https://wa.me/50686711422?text={{ urlencode("Tengo una consulta sobre mi pedido {$invoice->invoice_number}") }}"
                                   target="_blank" class="flex-1 inline-flex items-center justify-center gap-2 py-3 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-bold text-[11px] sm:text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                    <i class="fa-brands fa-whatsapp text-sm sm:text-base"></i> Consultar
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                @else
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-box-open text-3xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aún no tenés pedidos</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto px-4">Cuando realices una compra, tus pedidos aparecerán aquí automáticamente.</p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-eye"></i> Ver Catálogo
                        </a>
                    </div>
                @endif
            @endauth

            {{-- ═══════════ VISITANTE ═══════════ --}}
            @guest
                <div class="max-w-lg mx-auto">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-5 sm:p-8">
                        <div class="flex items-center gap-3 sm:gap-4 mb-6">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                                <i class="fa-solid fa-magnifying-glass text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Buscar Pedido</h2>
                                <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">Ingresa tu factura y correo electrónico</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('order-tracking.search') }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Número de Factura</label>
                                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" required placeholder="Ej: INV-1005"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                    @error('invoice_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Correo Electrónico</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="tucorreo@ejemplo.com"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#00C4FF] focus:ring-1 focus:ring-[#00C4FF] transition-all">
                                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <button type="submit" class="w-full py-3 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Buscar Pedido
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Resultado --}}
                    @if(isset($invoice))
                    <div class="mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-5 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-5 pb-5 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF] shrink-0">
                                    <i class="fa-solid fa-file-invoice text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Factura</p>
                                    <p class="text-sm sm:text-base font-black text-gray-900 dark:text-white break-all">{{ $invoice->invoice_number }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                @if($invoice->status === 'facturado')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-4 sm:py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-[10px] sm:text-xs font-bold uppercase">
                                    <i class="fa-solid fa-circle-check"></i> Pagado
                                </span>
                                @elseif($invoice->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-4 sm:py-1.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full text-[10px] sm:text-xs font-bold uppercase">
                                    <i class="fa-solid fa-clock"></i> Pendiente
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-4 sm:py-1.5 bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400 rounded-full text-[10px] sm:text-xs font-bold uppercase">
                                    {{ $invoice->status }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 mb-5">
                            @foreach($invoice->items as $item)
                            <div class="flex items-center gap-3 sm:gap-4 bg-gray-50 dark:bg-white/5 p-3 rounded-xl border border-gray-200 dark:border-white/10">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white truncate">{{ $item->product_name }}</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">{{ $item->product_model }} · x{{ $item->quantity }}</p>
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white shrink-0">₡{{ number_format($item->subtotal, 0) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700 mb-5">
                            <span class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-lg sm:text-xl font-black text-red-600 dark:text-red-400">₡{{ number_format($invoice->total, 0) }}</span>
                        </div>

                        <div class="flex flex-col gap-3">
                            @if($invoice->status === 'facturado')
                            <a href="{{ route('invoice.pdf', $invoice->id) }}" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[11px] sm:text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                <i class="fa-solid fa-file-pdf text-sm sm:text-base"></i> Descargar Factura
                            </a>
                            @endif
                            <a href="https://wa.me/50686711422?text={{ urlencode("Tengo una consulta sobre mi pedido {$invoice->invoice_number}") }}"
                               target="_blank" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-bold text-[11px] sm:text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                <i class="fa-brands fa-whatsapp text-sm sm:text-base"></i> Consultar
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            @endguest
        </div>
    </section>
</x-app-layout>
