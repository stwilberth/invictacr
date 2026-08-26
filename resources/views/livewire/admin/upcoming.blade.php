<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-3">Importar modelos</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Pegue los modelos (separados por espacio, coma o salto de línea). Se procesan uno por uno y verá el progreso en vivo.</p>
            <textarea wire:model="modelosInput" rows="5" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#00C4FF] dark:text-white placeholder-gray-400" placeholder="Ej:&#10;48604 47386&#10;49821,49703" @if($importing) disabled @endif></textarea>
            <div class="flex gap-2 mt-3">
                <button wire:click="startImport" wire:loading.attr="disabled" class="bg-[#00C4FF] hover:bg-[#00B0E6] text-white font-bold px-5 py-2 rounded-xl text-xs transition-all disabled:opacity-50" @if($importing) disabled @endif>
                    @if($importing)
                    <span><i class="fa-solid fa-spinner fa-spin"></i> Importando...</span>
                    @else
                    <span>Importar</span>
                    @endif
                </button>
                <button wire:click="clearAll" wire:confirm="¿Eliminar TODOS los productos próximos? Se hará un backup antes." class="bg-red-500 hover:bg-red-600 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all disabled:opacity-50" @if($importing) disabled @endif>Limpiar todo</button>
            </div>

            @if($importing || count($importLog) > 0)
            <div class="mt-4 bg-gray-50 dark:bg-[#0a0f1c] rounded-xl border border-gray-200 dark:border-white/5 p-3 max-h-80 overflow-y-auto" id="import-log">
                @foreach($importLog as $entry)
                @php
                    $typeClass = match($entry['type']) {
                        'error' => 'text-red-600 dark:text-red-400',
                        'skipped' => 'text-gray-500 dark:text-gray-400',
                        'created' => 'text-green-600 dark:text-green-400',
                        'created_basic' => 'text-yellow-600 dark:text-yellow-400',
                        'image_ok' => 'text-blue-600 dark:text-blue-400',
                        'image_fail' => 'text-orange-500',
                        'done' => 'text-[#00C4FF] font-bold',
                        default => 'text-gray-600 dark:text-gray-300',
                    };
                    $icon = match($entry['type']) {
                        'info' => 'fa-circle-info',
                        'scraping' => 'fa-search',
                        'scraped' => 'fa-check-circle',
                        'image_ok', 'image_fail' => 'fa-image',
                        'created' => 'fa-plus-circle',
                        'created_basic' => 'fa-exclamation-triangle',
                        'skipped' => 'fa-forward',
                        'error' => 'fa-times-circle',
                        'done' => 'fa-flag-checkered',
                        default => 'fa-circle-info',
                    };
                @endphp
                <div class="flex items-start gap-2 py-1 text-xs {{ $typeClass }}">
                    <span class="whitespace-nowrap">
                        <i class="fa-solid {{ $icon }}"></i>
                    </span>
                    @if($entry['modelo'])
                    <span class="font-bold whitespace-nowrap">{{ $entry['modelo'] }}</span>
                    @endif
                    <span>{{ $entry['message'] }}</span>
                </div>
                @endforeach
                @if($importing)
                <div class="text-xs text-gray-400 mt-2">
                    Procesados {{ $processedModelos }} de {{ $totalModelos }}
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1 mt-1">
                        <div class="bg-[#00C4FF] h-1 rounded-full transition-all" style="width: {{ $totalModelos > 0 ? ($processedModelos / $totalModelos) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex items-center gap-3 mb-3">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Próximos</h2>
                <input wire:model.live="search" type="text" placeholder="Buscar..." class="flex-1 bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm" />
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @forelse($products as $product)
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/5 overflow-hidden flex flex-col">
                    <a href="{{ route('products.show', ['slug' => $product->slug]) }}" target="_blank" class="block relative aspect-square bg-white dark:bg-gray-800">
                        @if($product->imagen)
                            <img src="{{ $product->imagen }}" alt="{{ $product->title }}" class="w-full h-full object-contain p-2" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="hidden w-full h-full items-center justify-center text-gray-300 dark:text-gray-600">
                                <i class="fa-solid fa-image text-2xl"></i>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                <i class="fa-solid fa-image text-2xl"></i>
                            </div>
                        @endif
                    </a>
                    <div class="p-2 flex-1 flex flex-col">
                        <a href="{{ route('products.show', ['slug' => $product->slug]) }}" target="_blank" class="font-bold text-xs text-[#00C4FF] hover:text-[#00B0E6] hover:underline truncate">
                            {{ $product->modelo }}
                        </a>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{ $product->title ?? 'Sin título' }}</p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-auto">{{ $product->coleccion ?? '-' }}</p>
                    </div>
                    <div class="px-2 pb-2">
                        <button wire:click="activate({{ $product->id }})" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-1.5 rounded-lg text-[10px] transition-all">Activar</button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                    No hay productos próximos.
                </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </div>
</div>

@script
<script>
    let processing = false;

    document.addEventListener('import-started', () => {
        processing = true;
        processLoop();
    });

    document.addEventListener('model-processed', () => {
        if (processing) {
            processLoop();
        }
    });

    function processLoop() {
        if (!$wire.importing) {
            processing = false;
            return;
        }
        $wire.processNext().catch(() => {
            processing = false;
            $wire.failImport('Error de comunicación con el servidor. Importación detenida.');
        });
        scrollLog();
    }

    function scrollLog() {
        const log = document.getElementById('import-log');
        if (log) {
            setTimeout(() => { log.scrollTop = log.scrollHeight; }, 50);
        }
    }
</script>
@endscript
