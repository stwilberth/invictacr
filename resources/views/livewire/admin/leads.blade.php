<div>
    @if(session('message'))
    <div class="mb-4 rounded-xl bg-green-50 dark:bg-green-900/20 px-4 py-3 text-sm font-bold text-green-700 dark:text-green-300">{{ session('message') }}</div>
    @endif
    @if($errors->has('visitor_id') || $errors->has('phone'))
    <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm font-bold text-red-600">{{ $errors->first('visitor_id') ?: $errors->first('phone') }}</div>
    @endif

    {{-- Importación clásica: file inputs no van por Livewire en este sitio. --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl p-4 mb-4 border border-gray-100 dark:border-white/5">
        <p class="text-sm font-black mb-1">Importar conversación de WhatsApp</p>
                <p class="text-xs text-gray-500 mb-3">Sube el ZIP exportado. Se crea un lead automáticamente con el nombre/teléfono que se pueda extraer, y luego se comparan las horas del chat con la actividad de navegación.</p>
        <form method="POST" action="{{ route('admin.leads.whatsapp-chats.store') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
            @csrf
            <input type="file" name="chat_zip" accept=".zip,application/zip" required class="max-w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-gray-100 dark:file:bg-white/10 file:px-3 file:py-2" />
            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-black uppercase bg-[#00C4FF] text-[#0a0f1c]">Subir ZIP</button>
        </form>
        @error('chat_zip') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
    </div>

    @if($whatsappChats->isNotEmpty())
    <div class="space-y-3 mb-5">
        <h3 class="text-sm font-black uppercase tracking-wider text-gray-500">Chats importados · coincidencias de navegación</h3>
        @foreach($whatsappChats as $chat)
        <section class="bg-white dark:bg-[#0f172a] rounded-2xl p-4 border border-gray-100 dark:border-white/5">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-3">
                <div>
                    <p class="font-black">{{ $chat->contact_name ?: $chat->source_name }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $chat->phone ?: 'Teléfono por asignar' }} · {{ $chat->message_count }} mensajes fechados
                        @if($chat->first_message_at)
                        · {{ $chat->first_message_at->timezone('America/Costa_Rica')->format('d/m/Y H:i') }} – {{ $chat->last_message_at?->timezone('America/Costa_Rica')->format('d/m/Y H:i') }}
                        @endif
                    </p>
                    @if($chat->lead)
                    <p class="mt-1 text-xs font-bold text-green-600">Lead creado: {{ $chat->lead->name ?: ($chat->lead->phone ?: 'contacto sin datos') }} · {{ $chat->lead->estado }}@if($chat->visitor) · Visitante vinculado @endif</p>
                    @endif
                </div>
                <details class="text-xs">
                    <summary class="cursor-pointer text-[#00A3D6] font-bold">Ver vista previa</summary>
                    <pre class="mt-2 max-h-56 max-w-3xl overflow-auto whitespace-pre-wrap rounded-lg bg-gray-50 dark:bg-black/20 p-3">{{ $chat->transcript_preview }}</pre>
                </details>
            </div>

            @if(!$chat->visitor_id)
            @if(($chatMatches[$chat->id] ?? collect())->isEmpty())
            <p class="text-xs text-amber-600">No hay actividad web registrada cerca de las horas de este chat. Puedes intentar con otro chat o revisar las fechas de exportación.</p>
            @else
            <p class="text-xs text-gray-500 mb-2">Posibles visitantes por cercanía temporal. Un clic de WhatsApp cercano aparece primero; confirma manualmente antes de vincular.</p>
            <div class="space-y-2">
                @foreach($chatMatches[$chat->id] as $match)
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                        <div class="text-xs">
                            <a href="{{ route('admin.visitors.detail', $match->visitor->id) }}" class="font-black hover:text-[#00C4FF]">{{ $match->visitor->name ?: 'Anónimo' }}</a>
                            <span class="text-gray-500"> · ref {{ $match->visitor->ref_code }} · {{ $match->visitor->device_type ?: 'dispositivo desconocido' }}</span>
                            <div class="text-gray-500">{{ $match->event->type_label }} {{ $match->event->created_at->timezone('America/Costa_Rica')->format('d/m/Y H:i') }} · {{ round($match->seconds / 60) }} min de diferencia · {{ $match->clicks }} clic(s) WA en el rango</div>
                            @if($match->purchased)<span class="font-bold text-green-600">Ya tiene compra</span>@endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.leads.whatsapp-chats.link', $chat) }}" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <input type="hidden" name="visitor_id" value="{{ $match->visitor->id }}" />
                        <input name="contact_name" value="{{ $chat->contact_name ?: $match->visitor->name }}" placeholder="Nombre del contacto" class="min-w-36 flex-1 px-3 py-2 rounded-lg text-xs bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10" />
                        <input name="phone" value="{{ $chat->phone ?: $match->visitor->phone }}" placeholder="Teléfono WhatsApp (opcional)" class="min-w-36 flex-1 px-3 py-2 rounded-lg text-xs bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10" />
                        <button type="submit" class="px-3 py-2 rounded-lg text-xs font-black uppercase bg-green-500 text-white">Vincular</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
            @endif
        </section>
        @endforeach
    </div>
    @endif

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
                Clic WA sin compra
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
            <select wire:model="ad" class="col-span-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                <option value="">— Anuncio —</option>
                @foreach($ads as $a)
                <option value="{{ $a->ad_id }}">{{ \Illuminate\Support\Str::limit($a->nombre, 40) }} ({{ number_format($a->spend, 0) }}$)</option>
                @endforeach
            </select>
            <input wire:model="nota" placeholder="Nota" class="col-span-1 md:col-span-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10" />
            <button wire:click="save" class="px-4 py-2 rounded-xl text-sm font-black uppercase bg-[#00C4FF] text-[#0a0f1c] hover:bg-[#00a3d6]">+ Agregar</button>
        </div>
        @if($notice)
        <p class="text-xs font-bold mt-2 {{ str_contains($notice, 'ya ') ? 'text-amber-500' : 'text-green-500' }}">{{ $notice }}</p>
        @endif
    </div>

    @if($tab === 'auto')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl p-4 mb-4 border border-gray-100 dark:border-white/5">
        <p class="text-xs text-gray-500 mb-2">Busca clics registrados durante esa hora, según la hora de Costa Rica.</p>
        <div class="flex flex-wrap items-end gap-3">
            <label class="text-xs font-bold text-gray-500">Fecha
                <input type="date" wire:model.live="clickDate" class="block mt-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10" />
            </label>
            <label class="text-xs font-bold text-gray-500">Hora
                <select wire:model.live="clickHour" class="block mt-1 px-3 py-2 rounded-xl text-sm bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                    @for($hour = 0; $hour < 24; $hour++)
                    <option value="{{ sprintf('%02d', $hour) }}">{{ sprintf('%02d:00–%02d:00', $hour, ($hour + 1) % 24) }}</option>
                    @endfor
                </select>
            </label>
            <span class="text-xs text-gray-500">{{ $counts['auto'] }} clic{{ $counts['auto'] === 1 ? '' : 's' }} pendiente{{ $counts['auto'] === 1 ? '' : 's' }}</span>
        </div>
        @if($notice)
        <p class="text-xs font-bold mt-3 {{ str_contains($notice, 'ya ') || str_contains($notice, 'no ') ? 'text-amber-500' : 'text-green-500' }}">{{ $notice }}</p>
        @endif
        @if($selectedCandidateId)
        <div class="mt-4 p-3 rounded-xl bg-gray-50 dark:bg-white/5">
            <p class="text-xs font-black uppercase tracking-wider text-gray-500 mb-2">Asignar datos del contacto</p>
            <div class="flex flex-wrap gap-2">
                <input wire:model="candidateName" placeholder="Nombre" class="flex-1 min-w-40 px-3 py-2 rounded-xl text-sm bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10" />
                <input wire:model="candidatePhone" placeholder="Teléfono / WhatsApp *" class="flex-1 min-w-40 px-3 py-2 rounded-xl text-sm bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10" />
                <button wire:click="saveCandidate" class="px-4 py-2 rounded-xl text-sm font-black uppercase bg-green-500 text-white">Guardar lead</button>
                <button wire:click="$set('selectedCandidateId', null)" class="px-4 py-2 rounded-xl text-sm font-bold bg-gray-200 dark:bg-white/10">Cancelar</button>
            </div>
            @error('candidatePhone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        @endif
    </div>
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
                @forelse($candidates as $event)
                @php($v = $event->visitor)
                <tr class="border-b border-gray-50 dark:border-white/5">
                    <td class="px-4 py-2.5">
                        <a href="{{ route('admin.visitors.detail', $v->id) }}" class="font-bold hover:text-[#00C4FF]">{{ $v->name ?: 'Anónimo' }}</a>
                        <div class="text-xs text-gray-400">{{ $v->phone ?: $v->device_type }} · ref {{ $v->ref_code }}</div>
                    </td>
                    <td class="px-4 py-2.5 text-xs">{{ $event->created_at?->timezone('America/Costa_Rica')->format('d/m H:i') }}</td>
                    <td class="px-4 py-2.5 text-xs">{{ $v->visits_count }}</td>
                    <td class="px-4 py-2.5 text-right">
                        <button wire:click="registerCandidate({{ $v->id }})" class="px-3 py-1.5 text-xs font-black uppercase rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500/20">Asignar nombre y teléfono</button>
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
                        @if($editingId === $lead->id)
                        <div class="flex items-center gap-1">
                            <input wire:model="editName" wire:key="edit-{{ $lead->id }}" autofocus wire:keydown.enter="saveName" wire:keydown.escape="$set('editingId', null)" placeholder="Nombre" class="px-2 py-1 rounded-lg text-sm w-40 bg-gray-50 dark:bg-white/5 border border-[#00C4FF]" />
                            <button wire:click="saveName" class="px-2 py-1 text-[11px] font-black uppercase rounded-lg bg-green-500 text-white">✓</button>
                        </div>
                        @else
                        <span class="font-bold">{{ $lead->name ?: 'Sin nombre' }}</span>
                        <button wire:click="startEditName({{ $lead->id }})" title="Editar nombre" class="ml-1 text-gray-400 hover:text-[#00C4FF] text-xs">✎</button>
                        @endif
                        @if($lead->needs_follow_up)
                        <span class="ml-1 text-[10px] font-black uppercase px-1.5 py-0.5 rounded bg-red-500 text-white">retomar</span>
                        @endif
                        <div class="text-xs text-gray-400">{{ $lead->phone }} · {{ $lead->created_at->format('d/m H:i') }}{{ $lead->source === 'auto_click' ? ' · auto' : '' }}</div>
                        @if($lead->nota)<div class="text-xs text-gray-500 italic">{{ $lead->nota }}</div>@endif
                        @if($lead->fb_ad_id && isset($adNames[$lead->fb_ad_id]))
                        <div class="text-[11px] mt-0.5 font-bold text-purple-500">📣 {{ $adNames[$lead->fb_ad_id]->name }} · $'{{ number_format($adNames[$lead->fb_ad_id]->spend, 2) }} invertido</div>
                        @elseif($lead->fb_ad_id)
                        <div class="text-[11px] mt-0.5 font-bold text-purple-500">📣 anuncio {{ $lead->fb_ad_id }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-xs">
                        {{ $lead->modelo_interes ?: '—' }}
                        @if($lead->visitor)
                        <div><a href="{{ route('admin.visitors.detail', $lead->visitor->id) }}" class="text-[#00C4FF] hover:underline">ver visitas ({{ $lead->visitor->visits_count }})</a></div>
                        @if($lead->visitor->utm_source || $lead->visitor->referrer)
                        <div class="text-[11px] text-gray-400 mt-0.5">
                            🌐 {{ $lead->visitor->utm_source ?: (str_contains($lead->visitor->referrer ?? '', 'google') ? 'google' : ($lead->visitor->referrer ?: 'directo')) }}
                            @if($lead->visitor->utm_medium) / {{ $lead->visitor->utm_medium }}@endif
                            @if($lead->visitor->utm_campaign) / {{ $lead->visitor->utm_campaign }}@endif
                        </div>
                        @endif
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
