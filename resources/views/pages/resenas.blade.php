<x-app-layout title="Reseñas de Clientes - Invicta Costa Rica">
    <div class="bg-white dark:bg-[#0a0f1c] py-6 md:py-10">
        <div class="max-w-7xl mx-auto px-4">
            <x-page-title
                title="Lo Que Dicen Nuestros Clientes"
                highlight="Clientes"
                subtitle="Reseñas reales de personas que ya compraron con nosotros y disfrutan de su Invicta original."
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    'd4706b409ea647743ec9dffe96f9503f',
                    '4320502d8b65b23e44ca8b8860a6c4d5',
                    '1b164d924ff877e04eabf3ff350f4863',
                    '06e9614540af48daa4d1ef5e47d17490',
                    '63a7acc4e00b2d5de8e8ebdd57dfd107',
                    '7be4a398961006e5b739b3c5c9347585',
                    '0e2de703b549ffd0a92446bad6708dff',
                    '87c4be1598d31afea67f8db764ef4333',
                    'ac90c6f10848a7b50d7fc9e1100c4c8a',
                    'c7ca6438b0601a62566602b18d0376be',
                    '439fee2f0ae352fa2b31fe4cc7bd6bb7',
                    '655c426e46289c58cacfef0fa95791e2',
                    '7b5b1a983980f50f7ac626c61b457b3b',
                    '71f183de26e242d24f6c9bd9cc1a6e5e',
                    'e297da37609e817be1fabb3321b5d13c',
                    'd4706b409ea647743ec9dffe96f9503f',
                ] as $streamUid)
                <div class="group relative rounded-2xl overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800 aspect-video">
                    <img src="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $streamUid }}/thumbnails/thumbnail.jpg" alt="Reseña de cliente" class="w-full h-full object-cover" loading="lazy" />
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition-all">
                        <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-play text-[#00C4FF] text-2xl ml-1"></i>
                        </div>
                    </div>
                    <button type="button" onclick="openVideoModal('{{ $streamUid }}')" class="absolute inset-0 z-10"></button>
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
