<x-app-layout title="Información de Envío" description="Envío gratis en el GAM y a todo Costa Rica. Mensajería privada 24-48 hrs en la Gran Área Metropolitana y Correos de Costa Rica con seguimiento al resto del país.">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title title="Información de Envío" highlight="Envío" subtitle="Todo lo que necesitas saber sobre nuestros métodos de envío para recibir tu Invicta en tiempo récord." />

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Mensajería GAM Details -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                                <i class="fa-solid fa-motorcycle text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Servicio de Mensajería</h3>
                                <p class="text-blue-200 text-sm">Gran Área Metropolitana (GAM)</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Servicio de mensajería personalizado que cubre desde El Coyol hasta Cartago, con entrega directa a tu puerta. Somos los más rápidos de Costa Rica.
                        </p>

                        <!-- Highlights -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 text-center">
                                <i class="fa-solid fa-bolt text-blue-600 dark:text-blue-400 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-blue-800 dark:text-blue-200">24-48 hrs</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-3 text-center">
                                <i class="fa-solid fa-hand-holding-dollar text-green-600 dark:text-green-400 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-green-800 dark:text-green-200">Pago al recibir</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-3 text-center">
                                <i class="fa-solid fa-gift text-purple-600 dark:text-purple-400 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-purple-800 dark:text-purple-200">Seguro</p>
                            </div>
                        </div>

                        <!-- Payment options -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-wallet text-blue-500"></i>
                                Opciones de pago al recibir
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-500">
                                    <i class="fa-solid fa-money-bill-wave text-green-500 text-[10px]"></i> Efectivo
                                </span>
                                <span class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-500">
                                    <i class="fa-solid fa-building-columns text-blue-500 text-[10px]"></i> Transferencia
                                </span>
                                <span class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-500">
                                    <i class="fa-solid fa-mobile-screen text-purple-500 text-[10px]"></i> SINPE Móvil
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Correos de Costa Rica Details -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-cyan-600 to-indigo-700 p-5">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                                <i class="fa-solid fa-box text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Correos de Costa Rica</h3>
                                <p class="text-indigo-200 text-sm">Envíos a todo el país</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Envíos a cualquier punto del territorio nacional a través de Correos de Costa Rica, con seguimiento incluido y foto del comprobante por WhatsApp.
                        </p>

                        <!-- Highlights -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-cyan-50 dark:bg-cyan-900/20 rounded-xl p-3 text-center">
                                <i class="fa-solid fa-calendar-check text-cyan-600 dark:text-cyan-400 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-cyan-800 dark:text-cyan-200">1-3 días</p>
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-3 text-center">
                                <i class="fa-solid fa-shield-halved text-amber-600 dark:text-amber-400 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-amber-800 dark:text-amber-200">Pago previo</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-3 text-center">
                                <i class="fa-solid fa-gift text-purple-600 dark:text-purple-400 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-purple-800 dark:text-purple-200">Seguro</p>
                            </div>
                        </div>

                        <!-- Process -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-cyan-500"></i>
                                Proceso de Envío
                            </h4>
                            <ol class="space-y-2">
                                <li class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex-shrink-0 w-5 h-5 bg-cyan-100 dark:bg-cyan-800 text-cyan-700 dark:text-cyan-200 rounded-full text-xs font-bold flex items-center justify-center mt-0.5">1</span>
                                    Confirmamos tu pago y preparamos tu pedido
                                </li>
                                <li class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex-shrink-0 w-5 h-5 bg-cyan-100 dark:bg-cyan-800 text-cyan-700 dark:text-cyan-200 rounded-full text-xs font-bold flex items-center justify-center mt-0.5">2</span>
                                    Entregamos el paquete en Correos de Costa Rica
                                </li>
                                <li class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex-shrink-0 w-5 h-5 bg-cyan-100 dark:bg-cyan-800 text-cyan-700 dark:text-cyan-200 rounded-full text-xs font-bold flex items-center justify-center mt-0.5">3</span>
                                    Te enviamos foto del comprobante por WhatsApp
                                </li>
                                <li class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex-shrink-0 w-5 h-5 bg-cyan-100 dark:bg-cyan-800 text-cyan-700 dark:text-cyan-200 rounded-full text-xs font-bold flex items-center justify-center mt-0.5">4</span>
                                    Rastreá tu envío con el número de seguimiento
                                </li>
                            </ol>
                        </div>

                        <!-- Payment options -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-wallet text-indigo-500"></i>
                                Opciones de pago
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-500">
                                    <i class="fa-solid fa-mobile-screen text-purple-500 text-[10px]"></i> SINPE Móvil
                                </span>
                                <span class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-500">
                                    <i class="fa-solid fa-building-columns text-blue-500 text-[10px]"></i> Transferencia
                                </span>
                                <span class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-500">
                                    <i class="fa-solid fa-money-bill-wave text-green-600 text-[10px]"></i> Efectivo
                                </span>
                            </div>
                        </div>
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
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg">¿Tienes dudas sobre tu envío?</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Escribinos por WhatsApp, te respondemos rápido</p>
                            </div>
                            <a href="https://wa.me/50686711422" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm whitespace-nowrap">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                +506 8671-1422
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-faq-section :items="[
        ['q' => '¿Cuánto tarda el envío en el GAM?', 'a' => 'En el Gran Área Metropolitana entregamos por mensajería privada en 24 a 48 horas, y en varias zonas de San José y Alajuela podemos hacerlo el mismo día.'],
        ['q' => '¿Cuánto tarda el envío al resto del país?', 'a' => 'Para el resto de Costa Rica usamos Correos de Costa Rica con seguimiento incluido. El paquete llega en 1 a 3 días hábiles después de confirmado el pago.'],
        ['q' => '¿El envío es gratis?', 'a' => 'Sí, el envío es gratis en el GAM y a todo el país con tu cuenta en Invicta Costa Rica.'],
        ['q' => '¿Puedo pagar al recibir el envío?', 'a' => 'Sí, en las entregas por mensajería del GAM podés pagar al recibir en efectivo, SINPE Móvil o transferencia. Para envíos por Correos de Costa Rica el pago es previo.'],
        ['q' => '¿Hacen envíos fuera de Costa Rica?', 'a' => 'No. Actualmente solo enviamos dentro del territorio nacional costarricense.'],
    ]"/>
</x-app-layout>
