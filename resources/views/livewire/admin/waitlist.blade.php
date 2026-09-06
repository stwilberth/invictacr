<div>
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Lista de Espera</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $pendientesCount }} persona(s) esperando reloj · @if($this->unreadCount > 0)<span class="font-bold text-amber-600 dark:text-amber-400">{{ $this->unreadCount }} notificación(es) sin leer</span>@else Sin notificaciones pendientes @endif</p>
        </div>
        @if($this->unreadCount > 0)
        <button wire:click="marcarTodasLeidas" class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 hover:-translate-y-0.5 transition-transform shadow-sm">Marcar todas leídas</button>
        @endif
    </div>

    {{-- Notificaciones por disponibilidad --}}
    @if(count($this->notifications) > 0)
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 mb-6">
        <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm mb-4">Notificaciones de disponibilidad</h2>
        <div class="space-y-2 max-h-72 overflow-y-auto">
            @foreach($this->notifications as $notif)
            <div class="flex items-start gap-3 p-3 rounded-xl {{ $notif->leida_at ? 'bg-gray-50 dark:bg-white/5' : 'bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30' }}">
                <i class="fa-solid fa-bell mt-1 {{ $notif->leida_at ? 'text-gray-300 dark:text-gray-600' : 'text-amber-500' }}"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $notif->titulo }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $notif->mensaje }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at?->format('d/m/Y H:i') }}</p>
                </div>
                @if(!$notif->leida_at)
                <button wire:click="marcarLeida({{ $notif->id }})" class="shrink-0 text-[10px] font-extrabold uppercase px-2 py-1 rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 hover:underline">Marcar leída</button>
                @else
                <span class="shrink-0 text-[10px] font-bold uppercase px-2 py-1 rounded-lg bg-gray-100 text-gray-400 dark:bg-white/5">Leída</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Formulario agregar contacto --}}
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 h-fit">
            <h2 class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-sm mb-1">Agregar contacto</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Asigne un reloj por número de modelo, sin importar su estado o stock.</p>
            <form wire:submit="agregar" class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-1">Nombre *</label>
                    <input type="text" wire:model="nombre" placeholder="Ej: Juan Pérez" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#00C4FF]" />
                    @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-1">Teléfono / WhatsApp</label>
                    <input type="text" wire:model="telefono" placeholder="Ej: 8888-8888" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#00C4FF]" />
                    @error('telefono') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-1">N.º de modelo *</label>
                    <input type="text" wire:model.live.debounce.300ms="modelo" placeholder="Ej: 37217 — escriba para buscar" @focus="open = true" @keydown.escape="open = false" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm font-mono text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#00C4FF]" />
                    @error('modelo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    @if(strlen(trim($modeloSearch)) >= 1 && count($modeloResults) > 0)
                    <div x-show="open" class="absolute top-full left-0 right-0 mt-1 z-50 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 rounded-xl shadow-xl max-h-56 overflow-y-auto">
                        @foreach($modeloResults as $product)
                        <button type="button" wire:click="selectModelo({{ $product->id }})" @click="open = false" class="w-full text-left px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-white/5 border-b border-gray-100 dark:border-white/5 last:border-0">
                            <div class="font-mono font-bold text-sm text-[#00C4FF]">{{ $product->modelo }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $product->title }}</div>
                            <div class="text-[10px] {{ (int) $product->stock > 0 ? 'text-emerald-500' : 'text-red-500' }} font-bold">{{ (int) $product->stock }} uds · {{ $product->disponibilidad ?? '—' }}</div>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-1">Nota</label>
                    <textarea wire:model="nota" rows="2" placeholder="Opcional" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#00C4FF]"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-extrabold uppercase bg-[#00C4FF] text-white hover:bg-[#00a8d6] hover:-translate-y-0.5 transition-all shadow-sm">Agregar a la espera</button>
            </form>
        </div>

        {{-- Lista de personas en espera --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
            <div class="flex flex-wrap gap-2 mb-4">
                <input type="text" wire:model.live="search" placeholder="Buscar nombre, teléfono o modelo..." class="flex-1 min-w-[200px] px-3 py-2 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#00C4FF]" />
                <select wire:model.live="filtroEstado" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm text-gray-900 dark:text-white">
                    <option value="todos">Todos</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="notificado">Notificados</option>
                    <option value="contactado">Contactados</option>
                    <option value="descartado">Descartados</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[10px] uppercase tracking-wider text-gray-400 border-b border-gray-100 dark:border-white/5">
                            <th class="py-2 pr-2">Contacto</th>
                            <th class="py-2 pr-2">Modelo</th>
                            <th class="py-2 pr-2">Stock actual</th>
                            <th class="py-2 pr-2">Estado</th>
                            <th class="py-2 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                        @php $prod = $this->stockDe($entry->modelo); @endphp
                        <tr class="border-b border-gray-50 dark:border-white/5 last:border-0">
                            <td class="py-3 pr-2">
                                <p class="font-bold text-gray-900 dark:text-white">{{ $entry->nombre }}</p>
                                <p class="text-xs text-gray-500">{{ $entry->telefono ?? '—' }}</p>
                                @if($entry->nota)<p class="text-xs text-gray-400 italic">{{ $entry->nota }}</p>@endif
                                <p class="text-[10px] text-gray-400">{{ $entry->created_at?->format('d/m/Y') }}</p>
                            </td>
                            <td class="py-3 pr-2 font-mono font-bold text-[#00C4FF]">{{ $entry->modelo }}</td>
                            <td class="py-3 pr-2 text-xs">
                                @if($prod)
                                    <span class="font-bold {{ (int) $prod->stock > 0 ? 'text-emerald-500' : 'text-red-500' }}">{{ (int) $prod->stock }} uds</span>
                                    <span class="text-gray-400">· {{ $prod->disponibilidad ?? '—' }}</span>
                                @else
                                    <span class="text-gray-400">Sin registro</span>
                                @endif
                            </td>
                            <td class="py-3 pr-2">
                                @if($entry->estado === 'pendiente')
                                <span class="text-[10px] font-extrabold uppercase px-2 py-1 rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">En espera</span>
                                @elseif($entry->estado === 'notificado')
                                <span class="text-[10px] font-extrabold uppercase px-2 py-1 rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Disponible · avisar</span>
                                @elseif($entry->estado === 'contactado')
                                <span class="text-[10px] font-extrabold uppercase px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Contactado</span>
                                @else
                                <span class="text-[10px] font-extrabold uppercase px-2 py-1 rounded-lg bg-gray-100 text-gray-500 dark:bg-white/5">Descartado</span>
                                @endif
                            </td>
                            <td class="py-3 text-right whitespace-nowrap">
                                @if(in_array($entry->estado, ['pendiente', 'notificado']))
                                <button wire:click="marcarContactado({{ $entry->id }})" title="Marcar contactado" class="p-2 rounded-lg text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20"><i class="fa-solid fa-phone"></i></button>
                                <button wire:click="descartar({{ $entry->id }})" title="Descartar" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5"><i class="fa-solid fa-ban"></i></button>
                                @elseif($entry->estado === 'descartado')
                                <button wire:click="reactivar({{ $entry->id }})" title="Reactivar" class="p-2 rounded-lg text-[#00C4FF] hover:bg-[#00C4FF]/10"><i class="fa-solid fa-rotate-left"></i></button>
                                @else
                                <button wire:click="reactivar({{ $entry->id }})" title="Volver a espera" class="p-2 rounded-lg text-[#00C4FF] hover:bg-[#00C4FF]/10"><i class="fa-solid fa-rotate-left"></i></button>
                                @endif
                                <button wire:click="eliminar({{ $entry->id }})" wire:confirm="¿Eliminar este registro?" title="Eliminar" class="p-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-8 text-center text-sm text-gray-500">Nadie en lista de espera. Agregue el primer contacto.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $entries->links() }}</div>
        </div>
    </div>
</div>
