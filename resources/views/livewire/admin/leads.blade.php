<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-black">Leads <span class="text-sm font-bold text-gray-400">· escribieron y no han comprado</span></h2>
        <div class="flex gap-2 text-xs font-bold">
            <button wire:click="switchTab('retomar')" class="px-3 py-1.5 rounded-lg {{ $tab === 'retomar' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-gray-100 dark:bg-white/5 text-gray-500' }}">
                Por retomar ({{ $counts['retomar'] }})
            </button>
            <button wire:click="switchTab('compraron')" class="px-3 py-1.5 rounded-lg {{ $tab === 'compraron' ? 'bg-green-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-500' }}">
                Compraron ({{ $counts['compraron'] }})
            </button>
            <button wire:click="switchTab('descartados')" class="px-3 py-1.5 rounded-lg {{ $tab === 'descartados' ? 'bg-gray-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-500' }}">
                Descartados ({{ $counts['descartados'] }})
            </button>
            <button wire:click="switchTab('auto')" class="px-3 py-1.5 rounded-lg {{ $tab === 'auto' ? 'bg-amber-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-500' }}">
                Clic WA sin compra ({{ $counts['auto'] }})
            </button>
        </div>
    </div>

    {{-- Registro rápido --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl p-4 mb-4 border border-gray-100 dark:border-white/5">
        <p class="text-xs font-black uppercase tracking-wider text-gray-500 mb-3">Registrar al que te escribió</p>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
            <input wire:model="phone" placeholder="Teléfono *" class="col-span-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10" />
            <input wire:model="name" placeholder="Nombre" class="col-span-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10" />
            <input wire:model="modelo" placeholder="Modelo interés" class="col-span-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10" />
            <input wire:model="ref" placeholder="Ref (opcional)" class="col-span-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10" />
            <input wire:model="nota" placeholder="Nota" class="col-span-1 md:col-span-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10" />
            <button wire:click="save" class="px-4 py-2 rounded-xl text-sm font-black uppercase bg-[#00C4FF] text-[#0a0f1c] hover:bg-[#00a3d6]">+ Agregar</button>
        </div>
        @if($notice)
        <p class="text-xs font-bold mt-2 {{ str_contains($notice, 'ya ') ? 'text-amber-500' : 'text-green-500' }}">{{ $notice }}</p>
        @endif
    </div>

    @if($tab === 'auto')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-100 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-white/5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">Visitante</th>
                    <th class="px-4 py-3">Clic WA</th>
                    <th class="px-4 py-3">Visitas</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $v)
                <tr class="border-b border-gray-50 dark:border-white/5">
                    <td class="px-4 py-2.5">
                        <a href="{{ route('admin.visitors.detail', $v->id) }}" class="font-bold hover:text-[#00C4FF]">{{ $v->name ?: 'Anónimo' }}</a>
                        <div class="text-xs text-gray-400">{{ $v->phone ?: $v->device_type }} · ref {{ $v->ref_code }}</div>
                    </td>
                    <td class="px-4 py-2.5 text-xs">{{ $v->whatsapp_clicked_at?->format('d/m H:i') }}</td>
                    <td class="px-4 py-2.5 text-xs">{{ $v->visits_count }}</td>
                    <td class="px-4 py-2.5 text-right">
                        <button wire:click="registerCandidate({{ $v->id }})" class="px-3 py-1.5 text-xs font-black uppercase rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500/20">Registrar lead</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">Sin candidatos: todos los que dieron clic ya tienen factura o lead.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-100 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-white/5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">Lead</th>
                    <th class="px-4 py-3">Interés</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr class="border-b border-gray-50 dark:border-white/5 {{ $lead->needs_follow_up ? 'bg-red-50/60 dark:bg-red-900/10' : '' }}">
                    <td class="px-4 py-2.5">
                        <span class="font-bold">{{ $lead->name ?: 'Sin nombre' }}</span>
                        @if($lead->needs_follow_up)
                        <span class="ml-1 text-[10px] font-black uppercase px-1.5 py-0.5 rounded bg-red-500 text-white">retomar</span>
                        @endif
                        <div class="text-xs text-gray-400">{{ $lead->phone }} · {{ $lead->created_at->format('d/m H:i') }}{{ $lead->source === 'auto_click' ? ' · auto' : '' }}</div>
                        @if($lead->nota)<div class="text-xs text-gray-500 italic">{{ $lead->nota }}</div>@endif
                    </td>
                    <td class="px-4 py-2.5 text-xs">
                        {{ $lead->modelo_interes ?: '—' }}
                        @if($lead->visitor)
                        <div><a href="{{ route('admin.visitors.detail', $lead->visitor->id) }}" class="text-[#00C4FF] hover:underline">ver visitas ({{ $lead->visitor->visits_count }})</a></div>
                        @endif
                    </td>
                    <td class="px-4 py-2.5">
                        @if($lead->estado === 'comprado' && $lead->invoice)
                        <a href="{{ route('admin.invoices.detail', $lead->invoice->id) }}" class="text-xs font-black text-green-500 hover:underline">✓ {{ $lead->invoice->invoice_number }}</a>
                        @else
                        <span class="text-xs font-bold text-gray-500">{{ ucfirst($lead->estado) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-right whitespace-nowrap">
                        @if($tab === 'retomar')
                        <button wire:click="markContacted({{ $lead->id }})" class="px-2.5 py-1 text-[11px] font-black uppercase rounded-lg bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20">Contactado</button>
                        <button wire:click="markDiscarded({{ $lead->id }})" class="px-2.5 py-1 text-[11px] font-black uppercase rounded-lg bg-gray-100 dark:bg-white/5 text-gray-500">Descartar</button>
                        @elseif($tab === 'descartados')
                        <button wire:click="reactivate({{ $lead->id }})" class="px-2.5 py-1 text-[11px] font-black uppercase rounded-lg bg-[#00C4FF]/10 text-[#00C4FF] hover:bg-[#00C4FF]/20">Reactivar</button>
                        @endif
                        <button wire:click="delete({{ $lead->id }})" onclick="return confirm('¿Borrar lead?')" class="px-2.5 py-1 text-[11px] font-black uppercase rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500">✕</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">Nada por aquí.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
