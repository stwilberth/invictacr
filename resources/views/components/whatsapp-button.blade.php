@php
    $message = "¡Hola! Vengo desde el sitio web y me gustaría más información.";
    $whatsappLink = "https://wa.me/50686711422?text=" . urlencode($message);
@endphp

<a href="{{ $whatsappLink }}"
   target="_blank"
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 md:w-16 md:h-16 bg-[#25D366] text-white rounded-full shadow-[0_10px_25px_rgba(37,211,102,0.4)] transition-all duration-300 hover:scale-110 active:scale-95 group no-underline"
   aria-label="Contactar por WhatsApp"
   x-data="whatsappState()"
   x-init="init()">
    <div class="absolute inset-0 rounded-full bg-[#25D366] animate-ping opacity-20 group-hover:opacity-40 transition-opacity"></div>
    <i class="fab fa-whatsapp text-2xl md:text-3xl relative z-10"></i>

    <span id="whatsapp-tooltip"
          class="absolute right-full mr-4 px-4 py-2 bg-neutral-900 text-white text-xs font-bold rounded-lg opacity-0 transition-all duration-500 whitespace-nowrap pointer-events-none shadow-xl border border-white/10 uppercase tracking-widest translate-x-2"
          :class="{ 'opacity-100 translate-x-0': showTooltip, 'opacity-0 translate-x-2': !showTooltip }">
        ¿Necesitas ayuda?
    </span>
</a>

@push('scripts')
<script>
    function whatsappState() {
        return {
            showTooltip: false,
            init() {
                setTimeout(() => {
                    this.showTooltip = true;
                    setTimeout(() => { this.showTooltip = false; }, 4000);
                }, 2000);
                setInterval(() => {
                    this.showTooltip = true;
                    setTimeout(() => { this.showTooltip = false; }, 4000);
                }, 10000);
            }
        }
    }
</script>
@endpush