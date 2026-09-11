@props(['apartadoMinimo' => 0, 'showApartado' => true])
@php
    $withApartado = $showApartado && (float) $apartadoMinimo > 0;
@endphp
<div class="w-full grid {{ $withApartado ? 'grid-cols-3' : 'grid-cols-3' }} gap-3">
    {{-- Trust signals estilo mockup --}}
    <div class="flex items-center gap-3 px-4 py-3 bg-[#F1F5FA] dark:bg-gray-800 rounded-xl">
        <span class="flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-truck-fast text-xl"></i>
        </span>
        <span class="leading-tight">
            <span class="block text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Envío gratis</span>
            <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">a todo Costa Rica</span>
        </span>
    </div>
    <div class="flex items-center gap-3 px-4 py-3 bg-[#F1F5FA] dark:bg-gray-800 rounded-xl">
        <span class="flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-shield-halved text-xl"></i>
        </span>
        <span class="leading-tight">
            <span class="block text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">100% Original</span>
            <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Garantía 6 meses</span>
        </span>
    </div>
    <div class="flex items-center gap-3 px-4 py-3 bg-[#F1F5FA] dark:bg-gray-800 rounded-xl">
        <span class="flex-shrink-0 text-[#14325E] dark:text-gray-200">
            <i class="fa-solid fa-box text-xl"></i>
        </span>
        <span class="leading-tight">
            <span class="block text-[13px] font-extrabold text-[#14325E] dark:text-gray-100">Apartado desde</span>
            <span class="block text-[13px] font-extrabold text-[#14325E] dark:text-white">₡{{ number_format($apartadoMinimo, 0) }}</span>
        </span>
    </div>
</div>
