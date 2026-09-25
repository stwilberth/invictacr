{{-- Banner 10% recurrente (reutilizable: arriba bajo el navbar y abajo sobre el footer) --}}
@php
    $refCode = strtoupper(substr((string) ($visitorUuid ?? ''), -6));
    $waLink = 'https://wa.me/50686711422?text=' . urlencode('¡Hola! Vi la promo del 10% de descuento y quiero más información' . ($refCode ? ". Mi código: {$refCode}" : ''));
@endphp
<section class="bg-[#0a0f1c] border-y-2 border-[#00C4FF]">
    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="max-w-7xl mx-auto px-4 py-2.5 flex items-center justify-center gap-2 sm:gap-3 text-center hover:opacity-90 transition-opacity">
        <i class="fa-solid fa-tag text-[#00C4FF] text-sm sm:text-base"></i>
        <p class="text-white text-[11px] sm:text-sm font-bold uppercase tracking-wide">
            <span class="text-[#00C4FF]">10% de descuento</span> en tu compra
            <span class="hidden sm:inline text-gray-400 font-semibold normal-case">· Solo por hoy · Reclamar por WhatsApp →</span>
        </p>
    </a>
</section>
