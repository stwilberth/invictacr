<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <select wire:model="branch" wire:change="loadData" class="bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                <option value="main">main</option>
                <option value="develop">develop</option>
                <option value="master">master</option>
            </select>
        </div>
        <button wire:click="sync" wire:loading.attr="disabled" class="bg-[#00C4FF] text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#00a8d6] transition-colors disabled:opacity-50">
            <span wire:loading.remove>Sincronizar ahora</span>
            <span wire:loading>Sincronizando...</span>
        </button>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-[#00C4FF]">{{ $stats['total'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total commits</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-green-500">{{ number_format($stats['additions'] ?? 0) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Líneas añadidas</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-red-500">{{ number_format($stats['deletions'] ?? 0) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Líneas eliminadas</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-amber-500">{{ $stats['unique_authors'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Autores</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <p class="text-2xl font-black text-purple-500">{{ $stats['deploy_commits'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Deploys</p>
        </div>
    </div>

    {{-- Commits table --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Historial de commits</h2>
        @if(count($commits) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-white/10">
                        <th class="pb-3 font-bold">Fecha</th>
                        <th class="pb-3 font-bold">Autor</th>
                        <th class="pb-3 font-bold">Mensaje</th>
                        <th class="pb-3 font-bold text-right">+/-</th>
                        <th class="pb-3 font-bold text-right">Archivos</th>
                        <th class="pb-3 font-bold">Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commits as $commit)
                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $commit['committed_at'] ? \Carbon\Carbon::parse($commit['committed_at'])->format('d/m/Y H:i') : '' }}
                        </td>
                        <td class="py-3 text-gray-700 dark:text-gray-300">{{ $commit['author_name'] }}</td>
                        <td class="py-3 text-gray-900 dark:text-white max-w-md truncate">{{ $commit['message'] }}</td>
                        <td class="py-3 text-right whitespace-nowrap">
                            <span class="text-green-500">+{{ $commit['additions'] }}</span>
                            <span class="text-red-500">-{{ $commit['deletions'] }}</span>
                        </td>
                        <td class="py-3 text-right text-gray-500">{{ $commit['files_changed'] }}</td>
                        <td class="py-3">
                            @if(str_contains(strtolower($commit['message'] ?? ''), 'deploy'))
                            <span class="text-xs font-black px-2 py-1 rounded bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">DEPLOY</span>
                            @elseif(str_contains(strtolower($commit['message'] ?? ''), 'release'))
                            <span class="text-xs font-black px-2 py-1 rounded bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">RELEASE</span>
                            @elseif(str_contains(strtolower($commit['message'] ?? ''), 'fix') || str_contains(strtolower($commit['message'] ?? ''), 'bug'))
                            <span class="text-xs font-black px-2 py-1 rounded bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">FIX</span>
                            @else
                            <span class="text-xs text-gray-400">---</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-sm">Sin commits sincronizados. Configura GITHUB_TOKEN, GITHUB_OWNER y GITHUB_REPO en .env y sincroniza.</p>
        @endif
    </div>
</div>
