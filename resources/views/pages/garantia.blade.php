<x-app-layout title="Garantía Real - Invicta Costa Rica">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title title="Garantía Real 6 Meses" highlight="Real" subtitle="Tu inversión está protegida. Ofrecemos un respaldo directo y transparente para que solo te preocupes por lucir tu nuevo Invicta." />

            <!-- Main Content Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Left Column: What's Covered -->
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-xl font-black uppercase tracking-tight mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white text-sm">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                                ¿Qué Cubrimos?
                            </h2>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-circle-check text-blue-500 mt-1"></i>
                                    <span class="text-gray-600 dark:text-gray-300">Defectos de fabricación en materiales y mano de obra.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-circle-check text-blue-500 mt-1"></i>
                                    <span class="text-gray-600 dark:text-gray-300 text-sm">Componentes internos: Movimiento del reloj, manecillas, carátula y marcadores.</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Período de Validez -->
                        <div class="p-6 bg-blue-500/10 border border-blue-200 dark:border-blue-800 rounded-xl">
                            <h3 class="font-bold text-blue-700 dark:text-blue-400 mb-2 flex items-center gap-2 text-sm uppercase">
                                <i class="fa-solid fa-clock"></i>
                                Período de Validez
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                Su reloj está respaldado por una garantía limitada de <strong>6 meses</strong> a partir de la fecha de entrega original.
                            </p>
                        </div>
                    </div>

                    <!-- Right Column: What's NOT Covered -->
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-xl font-black uppercase tracking-tight mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white text-sm">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
                                Excepciones
                            </h2>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3 text-gray-500 text-sm leading-tight">
                                    <i class="fa-solid fa-circle-xmark text-red-500/50 mt-1"></i>
                                    <span>Cristal, corona, correa, brazalete y batería.</span>
                                </li>
                                <li class="flex items-start gap-3 text-gray-500 text-sm leading-tight">
                                    <i class="fa-solid fa-circle-xmark text-red-500/50 mt-1"></i>
                                    <span>Daños por uso indebido, accidentes o desgaste normal.</span>
                                </li>
                                <li class="flex items-start gap-3 text-gray-500 text-sm leading-tight">
                                    <i class="fa-solid fa-circle-xmark text-red-500/50 mt-1"></i>
                                    <span>Daños por entrada de agua (humedad) por negligencia.</span>
                                </li>
                                <li class="flex items-start gap-3 text-gray-500 text-sm leading-tight">
                                    <i class="fa-solid fa-circle-xmark text-red-500/50 mt-1"></i>
                                    <span>Reparaciones en centros NO autorizados (incluye cambio de batería).</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Terms Detail -->
                <div class="mt-12 pt-12 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 text-[#00C4FF] mt-1">
                                <i class="fa-solid fa-file-invoice text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm mb-1 uppercase tracking-tight text-gray-900 dark:text-white">Comprobante</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-500 leading-relaxed">Deberá presentar el documento de compra PDF que contiene su número de factura único.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 text-[#00C4FF] mt-1">
                                <i class="fa-solid fa-truck text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm mb-1 uppercase tracking-tight text-gray-900 dark:text-white">Proceso de Envío</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-500 leading-relaxed">El cliente asume los gastos de envío para evaluación. El único medio autorizado es Correos de Costa Rica.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 text-[#00C4FF] mt-1">
                                <i class="fa-solid fa-hand-holding-heart text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm mb-1 uppercase tracking-tight text-gray-900 dark:text-white">Compromiso Real</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-500 leading-relaxed">Si no es posible reparar la pieza, le entregaremos un reloj nuevo igual o de valor equivalente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="bg-amber-500/5 border border-amber-500/20 rounded-xl p-4 flex flex-col md:flex-row gap-4 items-center mb-16">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-2xl"></i>
                <p class="text-xs md:text-sm text-amber-800 dark:text-amber-400 text-center md:text-left leading-relaxed font-medium">
                    Al comprar un reloj usted acepta los términos y condiciones de esta garantía limitada. No incluya cajas de regalo ni correas especiales en caso de envío para evaluación.
                </p>
            </div>

            <!-- Footer Meta -->
            <div class="text-center opacity-50 text-xs tracking-widest font-bold uppercase transition-opacity hover:opacity-100">
                Última actualización: 16 de marzo de 2026
            </div>
        </div>
    </section>
</x-app-layout>
