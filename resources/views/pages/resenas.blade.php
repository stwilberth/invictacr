<x-app-layout title="Reseñas de Clientes - Invicta Costa Rica">
    <div class="bg-white dark:bg-[#0a0f1c] py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title
                title="Lo Que Dicen Nuestros Clientes"
                highlight="Clientes"
                subtitle="Reseñas reales de personas que ya compraron con nosotros y disfrutan de su Invicta original."
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(['1182052076', '1192763867', '1175093984', '1175094082', '1175094337', '1175094102', '1175094166', '1175094314', '1175094251', '1175094129', '1175093934', '1175094037', '1175094366', '1175094283', '1182052049', '1182052076'] as $vimeoId)
                <div class="group relative rounded-2xl overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800 aspect-video">
                    <img src="https://vumbnail.com/{{ $vimeoId }}.jpg" alt="Reseña de cliente" class="w-full h-full object-cover" loading="lazy" />
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition-all">
                        <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-play text-[#00C4FF] text-2xl ml-1"></i>
                        </div>
                    </div>
                    <a href="https://vimeo.com/{{ $vimeoId }}" target="_blank" rel="noopener" class="absolute inset-0 z-10"></a>
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
