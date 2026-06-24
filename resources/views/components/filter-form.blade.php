@props([
    'formId' => 'filter-form',
    'showClose' => false,
    'gender' => null,
    'filters' => [],
])

@if($showClose)
<button type="button" onclick="closeFilters()" class="md:hidden absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl" aria-label="Cerrar filtros">
    <i class="fa-solid fa-xmark"></i>
</button>
@endif

<h3 class="font-black text-sm uppercase tracking-wider text-gray-900 dark:text-white mb-4">
    <i class="fa-solid fa-sliders text-[#00C4FF] mr-2"></i>Filtros
</h3>
<form method="GET" action="{{ url()->current() }}" id="{{ $formId }}">
    @if(request('sort'))
        <input type="hidden" name="sort" value="{{ request('sort') }}" />
    @endif

    {{-- Gender filter --}}
    @if(!$gender)
    <div class="mb-4">
        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Género</h4>
        <div class="space-y-1">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="gender" value="" {{ !request('gender') ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach(['hombre', 'mujer', 'unisex'] as $g)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="gender" value="{{ $g }}" {{ (request('gender') ?: $gender) === $g ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>{{ ucfirst($g) }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Color filter --}}
    @if($filters['colors']->count() > 0)
    <div class="mb-4">
        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Color</h4>
        <div class="space-y-1 max-h-40 overflow-y-auto">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="color" value="" {{ !request('color') ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['colors'] as $color)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="color" value="{{ $color }}" {{ request('color') === $color ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>{{ ucfirst($color) }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Brazalete filter --}}
    @if($filters['brazaletes']->count() > 0)
    <div class="mb-4">
        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Brazalete</h4>
        <div class="space-y-1">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="brazalete" value="" {{ !request('brazalete') ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['brazaletes'] as $brazalete)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="brazalete" value="{{ $brazalete }}" {{ request('brazalete') === $brazalete ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>{{ ucfirst($brazalete) }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Colección filter --}}
    @if(($filters['colecciones'] ?? collect())->count() > 0)
    <div class="mb-4">
        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Colección</h4>
        <div class="space-y-1 max-h-40 overflow-y-auto">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="coleccion" value="" {{ !request('coleccion') ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>Todas</span>
            </label>
            @foreach($filters['colecciones'] as $coleccion)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="coleccion" value="{{ $coleccion }}" {{ request('coleccion') === $coleccion ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>{{ $coleccion }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tipo de movimiento filter --}}
    @if(($filters['movimientos'] ?? collect())->count() > 0)
    <div class="mb-4">
        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Movimiento</h4>
        <div class="space-y-1">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="tipo_movimiento" value="" {{ !request('tipo_movimiento') ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['movimientos'] as $mov)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="tipo_movimiento" value="{{ $mov }}" {{ request('tipo_movimiento') === $mov ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>{{ $mov === 'cuarzo' ? 'Batería' : ucfirst($mov) }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Size filter --}}
    @if(($filters['sizes'] ?? collect())->count() > 0)
    <div class="mb-4">
        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tamaño</h4>
        <div class="space-y-1">
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="size" value="" {{ !request('size') ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>Todos</span>
            </label>
            @foreach($filters['sizes'] as $size)
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="radio" name="size" value="{{ $size }}" {{ request('size') === $size ? 'checked' : '' }} onchange="document.getElementById('{{ $formId }}').submit()" class="text-[#00C4FF]">
                <span>{{ $size }}MM</span>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Price range filter --}}
    <div class="mb-4">
        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Precio</h4>
        <div class="flex items-center gap-2">
            <input type="number" name="precio_min" placeholder="Desde" value="{{ request('precio_min') }}" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-gray-600 rounded-lg text-xs px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50" onchange="document.getElementById('{{ $formId }}').submit()" />
            <span class="text-gray-400 text-xs">-</span>
            <input type="number" name="precio_max" placeholder="Hasta" value="{{ request('precio_max') }}" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-gray-600 rounded-lg text-xs px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#00C4FF]/50" onchange="document.getElementById('{{ $formId }}').submit()" />
        </div>
    </div>

    <button type="submit" class="w-full bg-[#00C4FF] hover:bg-[#00b3e6] text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all duration-300 active:scale-95 shadow-md">
        <i class="fa-solid fa-filter mr-1"></i> Filtrar
    </button>
</form>
