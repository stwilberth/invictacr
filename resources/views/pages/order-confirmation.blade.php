<x-app-layout title="Pedido Confirmado - Invicta Costa Rica" :hideWhatsApp="true" noindex>
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-3xl mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-check text-4xl text-emerald-500"></i>
                </div>
                <h1 class="text-2xl md:text-4xl font-black uppercase tracking-tighter text-gray-900 dark:text-white leading-none mb-3">
                    ¡Pedido <span class="text-[#00C4FF]">Recibido</span>!
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gracias por tu compra. Tu número de factura es:</p>
                <p class="text-xl font-black text-[#00C4FF] mt-2">{{ $invoice->invoice_number }}</p>
            </div>

            {{-- Instrucciones --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8 mb-6">
                @if($invoice->status === 'facturado')
                <div class="text-center">
                    <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-check-circle text-2xl text-emerald-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Pago Confirmado</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tu pago ha sido verificado exitosamente y tu factura oficial ha sido generada.</p>
                </div>
                @else
                    @if($paymentMethod === 'paypal')
                    <div class="text-center">
                        <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-brands fa-paypal text-2xl text-[#003087]"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Pago con PayPal Completado</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tu pago ha sido procesado exitosamente. Recibirás un correo de confirmación de PayPal.</p>
                        @if($invoice->paypal_transaction_id)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-3">ID de transacción: {{ $invoice->paypal_transaction_id }}</p>
                        @endif
                    </div>
                    @elseif($paymentMethod === 'sinpe')
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-500 shrink-0">
                            <i class="fa-solid fa-mobile-screen text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Instrucciones SINPE Móvil</h3>
                    </div>
                    <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4 sm:p-5 border border-gray-200 dark:border-white/10 space-y-4 mb-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-4 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 shrink-0">Teléfono:</span>
                            <span class="font-bold text-gray-900 dark:text-white sm:text-right">8671-1422</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-4 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 shrink-0">Nombre:</span>
                            <span class="font-bold text-gray-900 dark:text-white sm:text-right">Wilberth Stanley Loría Vega</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-4 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 shrink-0">Monto:</span>
                            <span class="font-bold text-red-600 dark:text-red-400 sm:text-right">₡{{ number_format($invoice->total, 0) }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-4 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 shrink-0">Referencia:</span>
                            <span class="font-bold text-gray-900 dark:text-white sm:text-right break-all">{{ $invoice->invoice_number }}</span>
                        </div>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-200 dark:border-emerald-800/50 flex items-start gap-3">
                        <i class="fa-brands fa-whatsapp text-emerald-500 text-xl mt-0.5"></i>
                        <p class="text-sm text-emerald-700 dark:text-emerald-300">Envía el comprobante por WhatsApp al <strong>8671-1422</strong> para confirmar tu pedido.</p>
                    </div>
                    @elseif($paymentMethod === 'transferencia')
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-500 shrink-0">
                            <i class="fa-solid fa-building-columns text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Transferencia Bancaria</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Solicita los datos bancarios enviando un mensaje por WhatsApp con tu número de factura:</p>
                    <a href="https://wa.me/50686711422?text={{ urlencode("Hola, realicé la transferencia para el pedido {$invoice->invoice_number} por ₡" . number_format($invoice->total, 0)) }}"
                       target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-bold text-sm transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                        <i class="fa-brands fa-whatsapp text-lg"></i> Enviar por WhatsApp
                    </a>
                    @elseif($paymentMethod === 'contra_entrega')
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-500 shrink-0">
                            <i class="fa-solid fa-hand-holding-dollar text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pago Contra Entrega</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Pagarás al recibir tu pedido en la dirección indicada. Asegúrese de tener el monto exacto.</p>
                    <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-5 border border-gray-200 dark:border-white/10">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Monto a pagar:</span>
                            <span class="font-bold text-red-600 dark:text-red-400">₡{{ number_format($invoice->total, 0) }}</span>
                        </div>
                    </div>
                    @endif
                @endif
            </div>

            {{-- Resumen --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8 mb-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Resumen del Pedido</h2>
                <div class="space-y-3 mb-5">
                    @foreach($invoice->items as $item)
                    <div class="flex items-center gap-4 bg-gray-50 dark:bg-white/5 p-3 rounded-xl border border-gray-200 dark:border-white/10">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Modelo: {{ $item->product_model }} · Cant: {{ $item->quantity }}</p>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">₡{{ number_format($item->subtotal, 0) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-base font-bold text-gray-900 dark:text-white">Total Pagado</span>
                    <span class="text-xl font-black text-red-600 dark:text-red-400">₡{{ number_format($invoice->total, 0) }}</span>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="text-center space-y-4">
                @if($invoice->status === 'facturado' || $paymentMethod === 'paypal')
                <a href="{{ route('invoice.pdf', $invoice->id) }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-file-pdf text-base"></i> Descargar Factura PDF
                </a>
                @else
                    @if($paymentMethod === 'sinpe')
                    <div class="inline-flex items-center gap-2 px-8 py-3.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-xl font-bold text-sm">
                        <i class="fa-solid fa-clock text-base"></i> Pendiente de Pago
                    </div>
                    @elseif($paymentMethod === 'transferencia')
                    <div class="inline-flex items-center gap-2 px-8 py-3.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-xl font-bold text-sm">
                        <i class="fa-solid fa-clock text-base"></i> Pendiente de Confirmación
                    </div>
                    @elseif($paymentMethod === 'contra_entrega')
                    <div class="inline-flex items-center gap-2 px-8 py-3.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-xl font-bold text-sm">
                        <i class="fa-solid fa-truck text-base"></i> Pago al Recibir
                    </div>
                    @endif
                @endif
                
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-eye text-base"></i> Seguir Comprando
                </a>
                <a href="https://wa.me/50686711422?text={{ urlencode("Tengo una consulta sobre mi pedido {$invoice->invoice_number}") }}"
                   target="_blank" class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                    <i class="fa-brands fa-whatsapp text-base"></i> Contactar Soporte
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
