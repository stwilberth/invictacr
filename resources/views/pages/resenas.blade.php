<x-app-layout title="Reseñas de Clientes - Invicta Costa Rica">
    <div class="bg-white dark:bg-[#0a0f1c] py-6 md:py-10">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title
                title="Lo Que Dicen Nuestros Clientes"
                highlight="Clientes"
                subtitle="Reseñas reales de personas que ya compraron con nosotros y disfrutan de su Invicta original."
            />

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                @foreach($videos as $video)
                <div class="group relative rounded-2xl overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800 aspect-video">
                    <img src="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video->stream_uid }}/thumbnails/thumbnail.jpg" alt="Reseña de cliente" class="w-full h-full object-cover" loading="lazy" />
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition-all">
                        <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-play text-[#00C4FF] text-2xl ml-1"></i>
                        </div>
                    </div>
                    <button type="button" onclick="openVideoModal('{{ $video->stream_uid }}')" class="absolute inset-0 z-10"></button>
                </div>
                @endforeach
            </div>

            <div class="flex flex-wrap justify-center gap-4 mt-14 mb-2">
                <a
                    href="/relojes"
                    class="flex-1 min-w-[200px] max-w-[280px] flex items-center justify-center gap-3 px-6 py-4 bg-[#002D62] hover:bg-[#001d44] text-white rounded-2xl font-black uppercase tracking-tight transition-all shadow-lg active:scale-95 group"
                >
                    <i class="fa-solid fa-clock text-xl group-hover:scale-110 transition-transform"></i>
                    Catálogo
                </a>
                <a
                    href="https://wa.me/50686711422"
                    class="flex-1 min-w-[200px] max-w-[280px] flex items-center justify-center gap-3 px-6 py-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-black uppercase tracking-tight transition-all shadow-lg active:scale-95 group"
                >
                    <i class="fa-brands fa-whatsapp text-xl group-hover:scale-110 transition-transform"></i>
                    Contáctanos
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
