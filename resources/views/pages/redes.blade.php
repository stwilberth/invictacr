<x-app-layout title="Redes Sociales" description="Seguí a Invicta Costa Rica en Instagram y Facebook para ver nuevos modelos, ofertas y sorteos de relojes Invicta originales.">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title title="Redes Sociales" highlight="Redes" subtitle="Seguí a Invicta Costa Rica en nuestras redes para ofertas exclusivas, lanzamientos y contenido diario." />

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Instagram -->
                <a href="https://www.instagram.com/invictacr_/" target="_blank" rel="noopener noreferrer" class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 sm:p-8 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full flex items-center justify-center bg-gradient-to-br from-[#E1306C] via-[#F56040] to-[#FCAF45] text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fab fa-instagram text-2xl sm:text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-lg mb-1">Instagram</h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">@invictacr_</p>
                </a>

                <!-- TikTok -->
                <a href="https://www.tiktok.com/@invictacr" target="_blank" rel="noopener noreferrer" class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 sm:p-8 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full flex items-center justify-center bg-black text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fab fa-tiktok text-2xl sm:text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-lg mb-1">TikTok</h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">@invictacr</p>
                </a>

                <!-- Facebook -->
                <a href="https://www.facebook.com/invictacr" target="_blank" rel="noopener noreferrer" class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 sm:p-8 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full flex items-center justify-center bg-[#1877F2] text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fab fa-facebook-f text-2xl sm:text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-lg mb-1">Facebook</h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">invictacr</p>
                </a>

                <!-- YouTube -->
                <a href="https://www.youtube.com/@invicta_cr" target="_blank" rel="noopener noreferrer" class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 sm:p-8 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full flex items-center justify-center bg-[#FF0000] text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fab fa-youtube text-2xl sm:text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-lg mb-1">YouTube</h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">@invicta_cr</p>
                </a>
            </div>

            <!-- WhatsApp CTA -->
            <div class="mt-16 text-center">
                <div class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-[2px] shadow-lg shadow-green-500/20">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl px-8 py-6">
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div class="shrink-0">
                                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                    <i class="fab fa-whatsapp text-green-600 dark:text-green-400 text-2xl"></i>
                                </div>
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg">¿Compraste o tenés dudas?</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Escribinos por WhatsApp, te respondemos al instante</p>
                            </div>
                            <a href="https://wa.me/50686711422" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm whitespace-nowrap">
                                <i class="fab fa-whatsapp text-lg"></i>
                                +506 8671-1422
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
