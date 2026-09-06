@props(['apartadoMinimo' => 0, 'showApartado' => true])
@php
    $withApartado = $showApartado && (float) $apartadoMinimo > 0;
@endphp
<div class="w-full grid {{ $withApartado ? 'grid-cols-2' : 'grid-cols-1' }} gap-1.5">
    <div class="flex items-center gap-2 px-2.5 py-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-500/15">
            <i class="fa-solid fa-truck-fast text-emerald-600 dark:text-emerald-400 text-xs"></i>
        </span>
        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 dark:text-gray-200 leading-tight">Envío gratis</span>
    </div>
    <div class="flex items-center gap-2 px-2.5 py-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-500/15">
            <i class="fa-solid fa-shield-halved text-emerald-600 dark:text-emerald-400 text-xs"></i>
        </span>
        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 dark:text-gray-200 leading-tight">100% Original &mdash; Garantía 6 meses</span>
    </div>
    @if($withApartado)
    <div class="flex items-center gap-2 px-2.5 py-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-500/15">
            <i class="fa-solid fa-hand-holding-dollar text-emerald-600 dark:text-emerald-400 text-xs"></i>
        </span>
        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 dark:text-gray-200 leading-tight">Apartado desde <span class="font-black text-gray-900 dark:text-white">₡{{ number_format($apartadoMinimo, 0) }}</span></span>
    </div>
    @endif
</div>
