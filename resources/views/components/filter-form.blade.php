@props([
    'formId' => 'filter-form',
    'showClose' => false,
    'gender' => null,
    'filters' => [],
])

@php
    $isMobile = str_ends_with($formId, '-mobile');
    $activeSection = null;
    if (request('gender') || $gender) $activeSection = "'gender'";
    elseif (request('color')) $activeSection = "'color'";
    elseif (request('coleccion')) $activeSection = "'coleccion'";
    elseif (request('brazalete')) $activeSection = "'brazalete'";
    elseif (request('tipo_movimiento')) $activeSection = "'movimiento'";
    elseif (request('size')) $activeSection = "'size'";
    elseif (request('precio_min') || request('precio_max')) $activeSection = "'precio'";
    $defaultSection = $activeSection ?? ($isMobile ? "'gender'" : 'null');
@endphp

@if($showClose)
<button type="button" onclick="closeFilters()" class="md:hidden absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl" aria-label="Cerrar filtros">
    <i class="fa-solid fa-xmark"></i>
</button>
@endif

<h3 class="font-black text-sm uppercase tracking-wider text-gray-900 dark:text-white mb-4">
    <i class="fa-solid fa-sliders text-[#00C4FF] mr-2"></i>Filtros
</h3>
<div id="{{ $formId }}" x-data="{ openSection: {{ $defaultSection }} }">

    {{-- Gender --}}
    @php $currentGender = request('gender') ?: $gender; @endphp
    <div class="mb-2">
        <button type="button" @click="openSection = openSection === 'gender' ? null : 'gender'" class="w-full flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider py-2">
            <span>Género</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': openSection === 'gender' }"></i>
        </button>
        <div x-show="openSection === 'gender'" x-cloak class="space-y-1 pb-2">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="gender_{{ $formId }}" value="" {{ !$currentGender ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('gender', '')" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach(['hombre', 'mujer', 'unisex'] as $g)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="gender_{{ $formId }}" value="{{ $g }}" {{ $currentGender === $g ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('gender', '{{ $g }}')" class="text-[#00C4FF]">
                <span>{{ ucfirst($g) }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Color --}}
    @if(isset($filters['colors']) && $filters['colors']->count() > 0)
    <div class="mb-2">
        <button type="button" @click="openSection = openSection === 'color' ? null : 'color'" class="w-full flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider py-2">
            <span>Color</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': openSection === 'color' }"></i>
        </button>
        <div x-show="openSection === 'color'" x-cloak class="space-y-1 max-h-40 overflow-y-auto pb-2">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="color_{{ $formId }}" value="" {{ !request('color') ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('color', '')" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['colors'] as $color)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="color_{{ $formId }}" value="{{ $color }}" {{ strtolower(request('color')) === strtolower($color) ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('color', '{{ $color }}')" class="text-[#00C4FF]">
                <span>{{ ucfirst($color) }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Colección --}}
    @if(isset($filters['colecciones']) && $filters['colecciones']->count() > 0)
    <div class="mb-2">
        <button type="button" @click="openSection = openSection === 'coleccion' ? null : 'coleccion'" class="w-full flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider py-2">
            <span>Colección</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': openSection === 'coleccion' }"></i>
        </button>
        <div x-show="openSection === 'coleccion'" x-cloak class="space-y-1 max-h-40 overflow-y-auto pb-2">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="coleccion_{{ $formId }}" value="" {{ !request('coleccion') ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('coleccion', '')" class="text-[#00C4FF]">
                <span>Todas</span>
            </label>
            @foreach($filters['colecciones'] as $col)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="coleccion_{{ $formId }}" value="{{ $col }}" {{ strtolower(request('coleccion')) === strtolower($col) ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('coleccion', '{{ $col }}')" class="text-[#00C4FF]">
                <span>{{ ucfirst($col) }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Brazalete --}}
    @if(isset($filters['brazaletes']) && $filters['brazaletes']->count() > 0)
    <div class="mb-2">
        <button type="button" @click="openSection = openSection === 'brazalete' ? null : 'brazalete'" class="w-full flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider py-2">
            <span>Brazalete</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': openSection === 'brazalete' }"></i>
        </button>
        <div x-show="openSection === 'brazalete'" x-cloak class="space-y-1 max-h-40 overflow-y-auto pb-2">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="brazalete_{{ $formId }}" value="" {{ !request('brazalete') ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('brazalete', '')" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['brazaletes'] as $brazalete)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="brazalete_{{ $formId }}" value="{{ $brazalete }}" {{ strtolower(request('brazalete')) === strtolower($brazalete) ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('brazalete', '{{ $brazalete }}')" class="text-[#00C4FF]">
                <span>{{ $brazalete }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Movimiento --}}
    @if(isset($filters['movimientos']) && $filters['movimientos']->count() > 0)
    <div class="mb-2">
        <button type="button" @click="openSection = openSection === 'movimiento' ? null : 'movimiento'" class="w-full flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider py-2">
            <span>Movimiento</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': openSection === 'movimiento' }"></i>
        </button>
        <div x-show="openSection === 'movimiento'" x-cloak class="space-y-1 pb-2">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="tipo_movimiento_{{ $formId }}" value="" {{ !request('tipo_movimiento') ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('tipo_movimiento', '')" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['movimientos'] as $mov)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="tipo_movimiento_{{ $formId }}" value="{{ $mov }}" {{ strtolower(request('tipo_movimiento')) === strtolower($mov) ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('tipo_movimiento', '{{ $mov }}')" class="text-[#00C4FF]">
                <span>{{ $mov === 'cuarzo' ? 'Batería' : ucfirst($mov) }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tamaño --}}
    @if(isset($filters['sizes']) && $filters['sizes']->count() > 0)
    <div class="mb-2">
        <button type="button" @click="openSection = openSection === 'size' ? null : 'size'" class="w-full flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider py-2">
            <span>Tamaño</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': openSection === 'size' }"></i>
        </button>
        <div x-show="openSection === 'size'" x-cloak class="space-y-1 pb-2">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="size_{{ $formId }}" value="" {{ !request('size') ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('size', '')" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['sizes'] as $size)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="size_{{ $formId }}" value="{{ $size }}" {{ strtolower(request('size')) === strtolower($size) ? 'checked' : '' }} onchange="window.CatalogManager && window.CatalogManager.setFilter('size', '{{ $size }}')" class="text-[#00C4FF]">
                <span>{{ $size }}MM</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Precio --}}
    <div class="mb-2">
        <button type="button" @click="openSection = openSection === 'precio' ? null : 'precio'" class="w-full flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider py-2">
            <span>Precio</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': openSection === 'precio' }"></i>
        </button>
        <div x-show="openSection === 'precio'" x-cloak class="pb-2">
            <div class="flex items-center gap-2">
                <input type="number" id="precio_min_{{ $formId }}" placeholder="Desde" value="{{ request('precio_min') }}" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-gray-600 rounded-lg text-xs px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50" onchange="window.CatalogManager && window.CatalogManager.setFilter('precio_min', this.value)" />
                <span class="text-gray-400 text-xs">-</span>
                <input type="number" id="precio_max_{{ $formId }}" placeholder="Hasta" value="{{ request('precio_max') }}" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-gray-600 rounded-lg text-xs px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50" onchange="window.CatalogManager && window.CatalogManager.setFilter('precio_max', this.value)" />
            </div>
        </div>
    </div>
</div>
