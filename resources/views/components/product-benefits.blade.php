@props(['apartadoMinimo' => 0, 'showApartado' => true, 'apartadoWhatsapp' => null])
@php
    $withApartado = $showApartado && (float) $apartadoMinimo > 0;
@endphp
<div class="w-full grid grid-cols-2 gap-2 md:gap-3">
    {{-- Trust signals estilo mockup --}}
    <div class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-transparent dark:bg-transparent rounded-xl min-w-0">
        <span class="block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-truck-fast md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Envío gratis </span>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">a todo Costa Rica</span>
        </span>
    </div>
    <div class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-transparent dark:bg-transparent rounded-xl min-w-0">
        <span class="block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-shield-halved md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">100% Original </span>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">Garantía 6 meses</span>
        </span>
    </div>
    <div class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-transparent dark:bg-transparent rounded-xl min-w-0">
        <span class="block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-box md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Apartado desde</span>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">₡{{ number_format($apartadoMinimo, 0) }}</span>
        </span>
    </div>
    <div class="flex items-center gap-3 px-3 py-2.5 md:px-4 md:py-3 bg-transparent dark:bg-transparent rounded-xl min-w-0">
        <span class="block flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-hand-holding-dollar md:text-xl"></i>
        </span>
        <span class="leading-tight min-w-0">
            <span class="text-xs md:block md:text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Pago al recibir* </span>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">solo en el GAM</span>
        </span>
    </div>
</div>
