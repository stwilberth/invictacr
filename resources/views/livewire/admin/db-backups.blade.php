<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Crea y descarga copias de seguridad de la base de datos.</p>
        </div>
        <button wire:click="createBackup" wire:loading.attr="disabled" wire:target="createBackup"
            class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-5 py-2.5 rounded-xl text-sm transition-all uppercase tracking-wider disabled:opacity-50 flex items-center gap-2">
            <i class="fa-solid fa-database"></i>
            <span wire:loading.remove wire:target="createBackup">Crear Backup</span>
            <span wire:loading wire:target="createBackup">Creando...</span>
        </button>
    </div>

    @if($status)
        <div class="mb-6 px-4 py-3 rounded-xl text-sm font-bold
            {{ str_contains($status, 'Error') ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' }}">
            {{ $status }}
        </div>
    @endif

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        @if(empty($backups))
            <div class="p-12 text-center">
                <i class="fa-solid fa-database text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400 text-sm">No hay backups creados aún.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-black">Archivo</th>
                            <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-black">Fecha</th>
                            <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-black">Tamaño</th>
                            <th class="text-right px-6 py-3 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-black">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-green-50 dark:bg-green-900/20 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-file-code text-green-500 dark:text-green-400 text-xs"></i>
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-white font-mono text-xs">{{ $backup['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-3.5 text-gray-600 dark:text-gray-300">
                                    {{ $this->formatSize($backup['size']) }}
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="downloadBackup('{{ $backup['name'] }}')"
                                            class="px-3 py-1.5 text-xs font-bold rounded-lg bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20 transition-colors uppercase tracking-wider">
                                            <i class="fa-solid fa-download mr-1"></i> Descargar
                                        </button>
                                        <button wire:click="deleteBackup('{{ $backup['name'] }}')"
                                            wire:confirm="¿Eliminar este backup permanentemente?"
                                            class="px-3 py-1.5 text-xs font-bold rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors uppercase tracking-wider">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
