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
                <button wire:click="clearAll" wire:confirm="¿Eliminar TODOS los productos próximos? Se hará un backup antes." class="bg-red-500 hover:bg-red-600 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all">Limpiar todo</button>
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
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                            <th class="text-left px-4 py-3">Modelo</th>
                            <th class="text-left px-4 py-3">Producto</th>
                            <th class="text-left px-4 py-3">Colección</th>
                            <th class="text-left px-4 py-3">Imagen</th>
                            <th class="text-center px-4 py-3">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="px-4 py-3">
                                <a href="{{ route('products.show', ['gender' => $product->genero ?? 'unisex', 'slug' => $product->slug]) }}" target="_blank" class="font-bold text-[#00C4FF] hover:text-[#00B0E6] hover:underline">
                                    {{ $product->modelo }}
                                    <i class="fa-solid fa-up-right-from-square text-[10px] ml-0.5"></i>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[180px] truncate">{{ $product->title ?? 'Sin título' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->coleccion ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($product->imagen)
                                <span class="text-green-500 text-xs"><i class="fa-solid fa-check-circle"></i></span>
                                @else
                                <span class="text-red-400 text-xs"><i class="fa-solid fa-times-circle"></i></span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="activate({{ $product->id }})" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all">Activar</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay productos próximos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
        $wire.processNext();
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
