<x-app-layout title="Reseñas de Clientes" description="Reseñas y videos reales de clientes que ya compraron su reloj Invicta con nosotros. Relojes 100% originales, envío gratis y garantía de 6 meses.">
    <div class="bg-white dark:bg-[#0a0f1c] py-6 md:py-10">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title
                title="Lo Que Dicen Nuestros Clientes"
                highlight="Clientes"
                subtitle="Reseñas reales de personas que ya compraron con nosotros y disfrutan de su Invicta original."
            />

            @if($videos->isEmpty())
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-video-slash text-4xl mb-4 opacity-50"></i>
                <p class="font-bold">Pronto publicaremos las reseñas de nuestros clientes.</p>
                <a href="/relojes" class="inline-block mt-4 text-[#00C4FF] font-bold hover:underline">Ver catálogo</a>
            </div>
            @else
            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-4">
                @foreach($videos as $video)
                <div class="group relative rounded-xl overflow-hidden shadow bg-gray-900 aspect-[9/16] w-full mx-auto">
                    <img src="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video->stream_uid }}/thumbnails/thumbnail.jpg" alt="Reseña de {{ $video->nombre ?? 'cliente' }}" class="w-full h-full object-cover" loading="lazy" />
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition-all pointer-events-none">
                        <div class="w-9 h-9 md:w-11 md:h-11 rounded-full bg-white/90 flex items-center justify-center shadow group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-play text-[#00C4FF] text-sm md:text-base ml-0.5"></i>
                        </div>
                    </div>
                    @if($video->nombre)
                    <span class="absolute bottom-1.5 left-1.5 right-1.5 truncate text-[10px] font-bold text-white/90 bg-black/50 rounded-md px-1.5 py-0.5 pointer-events-none">{{ $video->nombre }}</span>
                    @endif
                    <button type="button" onclick="openVideoModal('{{ $video->stream_uid }}')" aria-label="Ver reseña" class="absolute inset-0 z-10 cursor-pointer"></button>
                </div>
                @endforeach
            </div>
            @endif

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
