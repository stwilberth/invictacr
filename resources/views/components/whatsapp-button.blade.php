@php
    $message = "¡Hola! Vengo desde el sitio web y me gustaría más información.";
    $whatsappLink = "https://wa.me/50686711422?text=" . urlencode($message);
@endphp

<a href="{{ $whatsappLink }}"
   target="_blank"
   rel="noopener noreferrer"
   class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50 flex items-center gap-3 bg-[#25D366] hover:bg-[#1fb857] rounded-full px-5 md:px-6 py-3 md:py-3.5 shadow-[0_10px_25px_rgba(0,0,0,0.35)] transition-all duration-300 hover:scale-105 active:scale-95 no-underline animate-bounce-slow"
   aria-label="Contactar por WhatsApp">
    <i class="fab fa-whatsapp text-2xl md:text-3xl text-white"></i>
    <span class="text-sm md:text-base font-black uppercase tracking-wide text-white">Contactar</span>
</a>
