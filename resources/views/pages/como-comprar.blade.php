<x-app-layout title="Cómo Comprar - Invicta Costa Rica">
    <section class="bg-white dark:bg-[#0a0f1c] py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title title="Guía de Compra Rápida" highlight="Rápida" subtitle="Tu próximo Invicta original está a solo tres pasos de distancia. Diseñamos un proceso simple, seguro y transparente." />

            <!-- Steps Grid -->
            <div class="space-y-12">
                <!-- Paso 1: Shop -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 flex flex-col md:flex-row gap-8 items-center md:items-start group hover:border-emerald-400/30 transition-all">
                    <div class="shrink-0 w-16 h-16 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cart-shopping text-3xl"></i>
                    </div>
                    <div>
                        <div class="text-emerald-500 font-black uppercase tracking-widest text-xs mb-2">Paso 01</div>
                        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Elegí tu modelo y envíanos un WhatsApp</h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                            Navegá por nuestro catálogo y seleccioná el reloj Invicta que más te guste. Hacé clic en el botón de <strong class="text-blue-600 dark:text-white">"Comprar por WhatsApp"</strong> que encontrarás en cada producto. Esto abrirá un chat directo con nosotros para iniciar tu pedido.
                        </p>
                        <div class="bg-emerald-500/10 dark:bg-emerald-500/5 p-4 rounded-xl border border-emerald-500/20 dark:border-emerald-500/10 text-sm text-emerald-700 dark:text-emerald-400/80">
                            <i class="fa-solid fa-circle-info mr-2"></i> Solo necesitaremos tu <strong>Nombre Completo</strong> y <strong>Dirección de Entrega</strong> para iniciar el pedido. El proceso es rápido y sin complicaciones.
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Choose -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 flex flex-col md:flex-row gap-8 items-center md:items-start group hover:border-blue-400/30 transition-all">
                    <div class="shrink-0 w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-hand-pointer text-3xl"></i>
                    </div>
                    <div>
                        <div class="text-blue-400 font-black uppercase tracking-widest text-xs mb-2">Paso 02</div>
                        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Coordinamos tu Envío</h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                            Somos los más rápidos de Costa Rica. Una vez confirmado tu pedido, preparamos tu paquete de inmediato y coordinamos la entrega según tu ubicación.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-200 dark:border-white/10">
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                                    <i class="fa-solid fa-map-location-dot text-blue-400 mr-2"></i> GAM
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-500">Entrega personal el mismo día o al día siguiente con nuestro mensajero privado. Sin costo adicional.</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-200 dark:border-white/10">
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                                    <i class="fa-solid fa-box text-blue-400 mr-2"></i> Resto del País
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-500">Envío express mediante Correos de Costa Rica con seguimiento incluido. Llega en 24-48 horas hábiles.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 3: Enjoy -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 flex flex-col md:flex-row gap-8 items-center md:items-start group hover:border-purple-400/30 transition-all">
                    <div class="shrink-0 w-16 h-16 bg-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-face-smile text-3xl"></i>
                    </div>
                    <div>
                        <div class="text-purple-400 font-black uppercase tracking-widest text-xs mb-2">Paso 03</div>
                        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Pago Seguro y Garantía Real</h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                            Tu tranquilidad es lo más importante. Ofrecemos métodos de pago flexibles y seguros respaldados por nuestra garantía real de 6 meses.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3 bg-purple-500/10 dark:bg-purple-500/5 p-4 rounded-xl border border-purple-500/20 dark:border-purple-500/10">
                                <i class="fa-solid fa-handshake text-purple-400 mt-1"></i>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">Paga al Recibir (Solo GAM)</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">Recibí tu reloj, verificalo y pagá en el momento por SINPE Móvil o Efectivo. Sin riesgos.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-200 dark:border-white/10">
                                <i class="fa-solid fa-credit-card text-purple-400 mt-1"></i>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">Envíos Nacionales</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">Aceptamos SINPE Móvil, Transferencia Bancaria o efectivo contra entrega. Te enviaremos el comprobante por WhatsApp.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Badges Section -->
            <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-4 group">
                    <i class="fa-solid fa-certificate text-2xl text-[#00C4FF] mb-3 block group-hover:scale-110 transition-transform"></i>
                    <h5 class="text-[10px] md:text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-300">100% Original</h5>
                </div>
                <div class="p-4 group">
                    <i class="fa-solid fa-shield-halved text-2xl text-[#00C4FF] mb-3 block group-hover:scale-110 transition-transform"></i>
                    <h5 class="text-[10px] md:text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-300">Garantía Real</h5>
                </div>
                <div class="p-4 group">
                    <i class="fa-solid fa-plane-up text-2xl text-[#00C4FF] mb-3 block group-hover:scale-110 transition-transform"></i>
                    <h5 class="text-[10px] md:text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-300">Importado USA</h5>
                </div>
                <div class="p-4 group">
                    <i class="fa-solid fa-star text-2xl text-[#00C4FF] mb-3 block group-hover:scale-110 transition-transform"></i>
                    <h5 class="text-[10px] md:text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-300">+500 Clientes</h5>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-24 text-center bg-gradient-to-r from-blue-600 to-blue-800 p-12 rounded-[3rem] shadow-2xl">
                <h2 class="text-3xl md:text-4xl font-black uppercase italic text-white mb-4">¿Listo para estrenar?</h2>
                <p class="text-white/80 mb-8 max-w-lg mx-auto leading-relaxed">Explora nuestra colección y encuentra el Invicta que mejor se adapta a tu estilo. ¡Envío gratis a todo Costa Rica!</p>
                <a href="/relojes" class="inline-flex items-center gap-2 bg-white text-blue-600 px-8 py-4 rounded-full font-black uppercase tracking-tighter hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                    Ver Catálogo Completo
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
