<div>
    <div class="flex gap-2 mb-6">
        <button wire:click="$set('activeTab', 'generator')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'generator' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Generar Anuncio</button>
        <button wire:click="$set('activeTab', 'utm')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'utm' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">UTM Generator</button>
    </div>

    @if($activeTab === 'generator')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Producto</label>
                <select wire:model="selectedProductId" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
                    <option value="">Seleccionar producto...</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->modelo }} - {{ $p->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button wire:click="generateAd" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-6 py-3 rounded-xl text-sm transition-all uppercase tracking-wider">Generar Anuncio</button>

        @if($generatedContent)
        <div class="mt-6 p-4 bg-gray-50 dark:bg-white/5 rounded-xl">
            <h3 class="font-black text-gray-900 dark:text-white mb-3">Anuncio generado:</h3>
            <div class="space-y-2">
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $generatedContent['title'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $generatedContent['description'] }}</p>
                @if($generatedContent['image'])
                <img src="{{ $generatedContent['image'] }}" class="w-40 h-40 object-contain rounded-xl" />
                @endif
            </div>
        </div>
        @endif
    </div>
    @elseif($activeTab === 'utm')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <p class="text-gray-500 dark:text-gray-400 text-sm">Generador de enlaces UTM (próximamente).</p>
    </div>
    @endif
</div>