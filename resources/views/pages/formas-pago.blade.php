<x-app-layout title="Formas de Pago" description="Pagá tu reloj Invicta como prefieras: PayPal, SINPE Móvil, transferencia bancaria o efectivo contra entrega en el GAM. Métodos 100% seguros.">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title title="Formas de Pago" highlight="Pago" subtitle="Ofrecemos múltiples opciones seguras para que obtengas tu Invicta de la manera más conveniente." />

            <!-- Payment Methods -->
            <div class="space-y-8">
                <!-- PayPal -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#003087]">
                            <i class="fa-brands fa-paypal text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">PayPal</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                        Paga de forma segura con tu tarjeta de crédito, débito o cuenta de PayPal. Aceptamos Visa, Mastercard y American Express a través de PayPal.
                    </p>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-xl border border-blue-200 dark:border-blue-800/50 flex items-start gap-3">
                        <i class="fa-solid fa-shield-check text-blue-500 mt-1"></i>
                        <div>
                            <p class="text-sm text-blue-800 dark:text-blue-200 font-bold">Pago 100% seguro</p>
                            <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">Tus datos están protegidos por PayPal. No almacenamos información de tarjetas.</p>
                        </div>
                    </div>
                </div>

                <!-- SINPE Móvil -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF]">
                            <i class="fa-solid fa-mobile-screen-button text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">SINPE Móvil</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                        Utilizá SINPE Móvil para realizar pagos seguros, sin comisiones y de acreditación inmediata desde cualquier banco del país.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-600 mb-6">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#00C4FF]/10 flex items-center justify-center text-[#00C4FF] shrink-0">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Número SINPE</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">8671-1422</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#00C4FF]/10 flex items-center justify-center text-[#00C4FF] shrink-0">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">A nombre de</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white mt-1 leading-tight">Wilberth Stanley<br />Loría Vega</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 bg-green-50 dark:bg-green-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800/50">
                        <i class="fa-brands fa-whatsapp text-3xl text-green-500 shrink-0"></i>
                        <p class="text-sm text-green-800 dark:text-green-200 text-center sm:text-left">
                            Después de realizar el pago, envíanos el comprobante por WhatsApp para procesar tu pedido inmediatamente. Te confirmaremos en menos de 1 hora.
                        </p>
                    </div>
                </div>

                <!-- Transferencia Bancaria -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF]">
                            <i class="fa-solid fa-building-columns text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Transferencia Bancaria</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                        Realizá tus pagos de forma segura mediante transferencia bancaria directa a nuestras cuentas en Colones. Una vez acreditado el pago, procesamos tu pedido de inmediato y te enviamos la confirmación y el comprobante por WhatsApp.
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">
                        <i class="fa-solid fa-circle-info text-[#00C4FF] mr-1"></i> Solicitá nuestros datos bancarios por WhatsApp y te los compartimos al instante.
                    </p>
                </div>

                <!-- Pago en Efectivo / Contra Entrega -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-[#00C4FF]/10 rounded-xl flex items-center justify-center text-[#00C4FF]">
                            <i class="fa-solid fa-hand-holding-dollar text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pago en Efectivo / Contra Entrega</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                        Aceptamos pagos en efectivo para entregas personales por mensajería en el Gran Área Metropolitana (GAM). Recibí tu reloj, verificalo y pagá en el momento. Sin necesidad de adelantar dinero.
                    </p>
                    <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-200 dark:border-amber-800/50 flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-amber-500 mt-1"></i>
                        <p class="text-sm text-amber-800 dark:text-amber-200">
                            <strong>Disponible en GAM:</strong> El Coyol, Alajuela, Heredia, San José, Curridabat, Zapote, San Pedro, Escazú, Santa Ana, y Cartago.
                        </p>
                    </div>
                </div>
            </div>

            <!-- WhatsApp CTA -->
            <div class="mt-16 text-center">
                <div class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-[2px] shadow-lg shadow-green-500/20">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl px-8 py-6">
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div class="shrink-0">
                                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                    <i class="fa-brands fa-whatsapp text-green-600 dark:text-green-400 text-2xl"></i>
                                </div>
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg">¿Consultas sobre tu pago?</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Escribinos por WhatsApp, te respondemos al instante</p>
                            </div>
                            <a href="https://wa.me/50686711422" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm whitespace-nowrap">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                +506 8671-1422
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <x-faq-section :items="[
                ['q' => '¿Qué métodos de pago aceptan?', 'a' => 'Aceptamos PayPal, SINPE Móvil, transferencia bancaria y efectivo contra entrega (disponible solo en el GAM).'],
                ['q' => '¿Puedo pagar al recibir mi reloj?', 'a' => 'Sí, el pago contra entrega está disponible en el Gran Área Metropolitana: recibís tu reloj, lo verificás y pagás en el momento, sin adelantar dinero.'],
                ['q' => '¿Es seguro pagar con tarjeta?', 'a' => 'Sí. Los pagos con tarjeta se procesan a través de PayPal, que protege tus datos bancarios. No almacenamos información de tarjetas.'],
                ['q' => '¿Por SINPE Móvil a qué número pago?', 'a' => 'Nuestro número SINPE es 8671-1422, a nombre de Wilberth Stanley Loría Vega. Después de pagar, enviá el comprobante por WhatsApp y confirmamos tu pedido en menos de 1 hora.'],
            ]"/>
        </div>
    </section>
</x-app-layout>
