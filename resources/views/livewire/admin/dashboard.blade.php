<div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-[#00C4FF]">{{ $stats['products'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Productos activos</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-green-500">{{ $stats['invoices'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Facturas</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-blue-500">{{ $stats['subscribers'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Suscriptores</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-amber-500">{{ $stats['low_stock'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Stock bajo</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-red-500">{{ $stats['out_of_stock'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Agotados</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-3xl font-black text-purple-500">{{ $stats['upcoming'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Próximamente</p>
        </div>
    </div>

    @if($recentSyncs->count() > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Sincronizaciones recientes</h2>
        <div class="space-y-2">
            @foreach($recentSyncs as $log)
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">{{ $log->type }} - {{ $log->message }}</span>
                <span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>