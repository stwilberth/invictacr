<div>
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4">
            <div class="min-w-0">
                <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm">Sincronizar con VariedadesCR</h2>
                @if($this->lastSuccess)
                <p class="text-xs text-gray-400 mt-1 break-words">Última sincronización exitosa: {{ $this->lastSuccess->created_at->format('d/m/Y H:i') }} — {{ $this->lastSuccess->message }}</p>
                @else
                <p class="text-xs text-gray-400 mt-1">Nunca se ha sincronizado</p>
                @endif
            </div>
            <button
                wire:click="triggerSync"
                wire:loading.attr="disabled"
                class="w-full sm:w-auto justify-center bg-[#00C4FF] hover:bg-[#00b0e6] disabled:opacity-50 disabled:cursor-not-allowed text-[#0a0f1c] font-black px-6 py-3 rounded-xl text-sm transition-all flex items-center gap-2 shrink-0"
            >
                <i wire:loading.remove wire:target="triggerSync" class="fa-solid fa-rotate"></i>
                <i wire:loading wire:target="triggerSync" class="fa-solid fa-spinner fa-spin"></i>
                <span wire:loading.remove wire:target="triggerSync">Sincronizar ahora</span>
                <span wire:loading wire:target="triggerSync">Sincronizando...</span>
            </button>
        </div>

        @if($lastResult)
        <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm font-bold break-words">
            <i class="fa-solid fa-circle-check"></i> {{ $lastResult }}
        </div>
        @endif

        @if($lastError)
        <div class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-bold break-words">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $lastError }}
        </div>
        @endif
    </div>
</div>
