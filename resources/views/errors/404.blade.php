<x-app-layout title="Página no encontrada" :hideNav="false">
    <div class="flex flex-col items-center justify-center min-h-[70vh] px-4 text-center">
        <div class="mb-8">
            <img src="{{ asset('logo.webp') }}" alt="Invicta Costa Rica" class="w-24 h-24 mx-auto opacity-20" />
        </div>

        <h1 class="text-8xl font-black text-[#00C4FF] mb-4">404</h1>

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            Reloj no encontrado
        </h2>

        <p class="text-gray-500 dark:text-gray-400 max-w-md mb-8">
            Parece que este modelo no está disponible o la dirección no es correcta.
            ¿Buscas algo específico?
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('products.index') }}"
               class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-8 py-3 rounded-xl text-sm transition-all">
                <i class="fa-solid fa-clock mr-2"></i>Ver catálogo
            </a>

            <a href="https://wa.me/50686711422?text=Hola%2C%20busco%20un%20reloj%20Invicta"
               target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white font-black px-8 py-3 rounded-xl text-sm transition-all">
                <i class="fa-brands fa-whatsapp mr-2"></i>Escríbenos
            </a>
        </div>
    </div>
</x-app-layout>
