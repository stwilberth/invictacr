<x-app-layout title="Reseñas de Clientes">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-4 text-center">Reseñas de Clientes</h1>
        <p class="text-center text-gray-500 dark:text-gray-400 mb-10">Mira lo que dicen nuestros clientes sobre sus relojes Invicta</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(['1182052076', '1192763867', '1175093984', '1175094082', '1175094337', '1175094102', '1175094166', '1175094314', '1175094251', '1175094129'] as $vimeoId)
            <div class="group relative rounded-2xl overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800 aspect-video">
                <img src="https://vumbnail.com/{{ $vimeoId }}.jpg" alt="Reseña de cliente" class="w-full h-full object-cover" loading="lazy" />
                <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition-all">
                    <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-play text-gray-900 text-2xl ml-1"></i>
                    </div>
                </div>
                <a href="https://vimeo.com/{{ $vimeoId }}" target="_blank" rel="noopener" class="absolute inset-0 z-10"></a>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>