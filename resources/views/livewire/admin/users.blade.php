<div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Total Usuarios</p>
            <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Administradores</p>
            <p class="text-2xl font-black text-[#00C4FF] mt-1">{{ $totalAdmins }}</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Verificados</p>
            <p class="text-2xl font-black text-green-500 mt-1">{{ $totalVerified }}</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input wire:model.live="search" type="text" placeholder="Buscar por nombre, email o teléfono..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm w-full sm:w-80" />
        <select wire:model.live="filterRole" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
            <option value="all">Todos los roles</option>
            <option value="admin">Administradores</option>
            <option value="user">Usuarios normales</option>
        </select>
        <select wire:model.live="filterVerified" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm">
            <option value="all">Todos</option>
            <option value="yes">Verificados</option>
            <option value="no">No verificados</option>
        </select>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3">Nombre</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Teléfono</th>
                    <th class="text-center px-4 py-3">Rol</th>
                    <th class="text-center px-4 py-3">Verificado</th>
                    <th class="text-left px-4 py-3">Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->phone ?: '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($user->is_admin)
                            <span class="bg-[#00C4FF]/10 text-[#00C4FF] text-xs font-bold px-2.5 py-1 rounded-lg">Admin</span>
                        @else
                            <span class="bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 text-xs font-bold px-2.5 py-1 rounded-lg">Usuario</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($user->email_verified_at)
                            <i class="fa-solid fa-circle-check text-green-500"></i>
                        @else
                            <i class="fa-solid fa-circle-xmark text-gray-300 dark:text-gray-600"></i>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400 dark:text-gray-500">
                        <i class="fa-solid fa-users text-3xl mb-3 block"></i>
                        No se encontraron usuarios.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>
