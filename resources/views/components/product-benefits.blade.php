@props(['apartadoMinimo' => 0, 'showApartado' => true, 'apartadoWhatsapp' => null])
@php
    $withApartado = $showApartado && (float) $apartadoMinimo > 0;
@endphp
<div x-data="{ abierto: false }" class="w-full grid grid-cols-2 gap-2 md:gap-3">
    {{-- Trust signals estilo mockup --}}
    <div class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-[#F1F5FA] dark:bg-gray-800 rounded-xl min-w-0">
        <span class="hidden md:block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-truck-fast md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Envío gratis </span>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 hidden md:block">a todo Costa Rica</span>
        </span>
    </div>
    <div class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-[#F1F5FA] dark:bg-gray-800 rounded-xl min-w-0">
        <span class="hidden md:block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-shield-halved md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">100% Original </span>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 hidden md:block">Garantía 6 meses</span>
        </span>
    </div>
    <div @click="if (window.innerWidth < 768) abierto = !abierto" class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-[#F1F5FA] dark:bg-gray-800 rounded-xl min-w-0 cursor-pointer md:cursor-default select-none">
        <span class="hidden md:block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-box md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0 flex-1">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Apartado desde ₡{{ number_format($apartadoMinimo, 0) }}</span>
        </span>
        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 md:hidden transition-transform" :class="{ 'rotate-180': abierto }"></i>
    </div>
    <div x-show="abierto" x-transition class="col-span-2 md:hidden bg-[#0EB45D]/10 border border-[#0EB45D]/20 rounded-xl p-3" style="display: none;">
        <p class="text-xs font-extrabold text-[#14325E] dark:text-gray-100">¿Cómo funciona el apartado?</p>
        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 leading-snug">Apartá hoy con el <b>20% (₡{{ number_format($apartadoMinimo, 0) }})</b> y cancelá el resto en cuotas. Coordinamos todo por WhatsApp.</p>
        @if($apartadoWhatsapp)
        <a href="{{ $apartadoWhatsapp }}" target="_blank" rel="noopener noreferrer" class="mt-2 flex items-center justify-center gap-2 bg-[#0EB45D] hover:bg-[#0aa550] text-white text-xs font-bold rounded-lg py-2.5 no-underline">
            <i class="fa-brands fa-whatsapp text-sm"></i> Apartar por WhatsApp
        </a>
        @endif
    </div>
</div>
    <div class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-[#F1F5FA] dark:bg-gray-800 rounded-xl min-w-0">
        <span class="hidden md:block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-hand-holding-dollar md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Pago al recibir* </span>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 hidden md:block">solo en el GAM</span>
        </span>
    </div>
</div>
