<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use App\Models\WhatsappChatImport;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Leads extends Component
{
    public string $tab = 'retomar';

    // Form rápido
    public string $name = '';
    public string $phone = '';
    public string $modelo = '';
    public string $nota = '';
    public string $ref = '';
    public string $ad = '';
    public string $notice = '';

    // Búsqueda de clics por hora local de Costa Rica
    public string $clickDate = '';
    public string $clickHour = '';
    public ?int $selectedCandidateId = null;
    public string $candidateName = '';
    public string $candidatePhone = '';

    // Edición inline del nombre
    public ?int $editingId = null;
    public string $editName = '';

    protected function rules(): array
    {
        return [
            'phone' => 'required|string|max:30',
            'name' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:100',
            'nota' => 'nullable|string|max:1000',
            'ref' => 'nullable|string|max:20',
            'ad' => 'nullable|string|max:100',
        ];
    }

    public function mount(): void
    {
        $localNow = now()->setTimezone('America/Costa_Rica');
        $this->clickDate = $localNow->format('Y-m-d');
        $this->clickHour = $localNow->format('H');
        $this->syncPurchases();
    }

    public function updatedClickDate(): void
    {
        $this->selectedCandidateId = null;
        $this->reset(['candidateName', 'candidatePhone']);
    }

    public function updatedClickHour(): void
    {
        $this->selectedCandidateId = null;
        $this->reset(['candidateName', 'candidatePhone']);
    }

    public function switchTab(string $tab): void
    {
        $this->tab = in_array($tab, ['retomar', 'compraron', 'descartados', 'auto'], true) ? $tab : 'retomar';
    }

    /**
     * Marca como comprados los leads (nuevo/contactado) que ya
     * tienen factura por visitor_id o teléfono.
     */
    protected function syncPurchases(): void
    {
        $leads = Lead::porRetomar()->get(['id', 'phone_norm', 'visitor_id']);

        if ($leads->isEmpty()) {
            return;
        }

        $visitorIds = $leads->pluck('visitor_id')->filter()->unique()->values();
        $byVisitor = $visitorIds->isNotEmpty()
            ? Invoice::where('status', '!=', 'cancelled')
                ->whereIn('visitor_id', $visitorIds)
                ->pluck('id', 'visitor_id')
            : collect();

        $norms = $leads->pluck('phone_norm')->filter()->unique()->values();
        $invoiceByPhone = [];
        if ($norms->isNotEmpty()) {
            $invoices = Invoice::where('status', '!=', 'cancelled')
                ->whereNotNull('client_phone')
                ->get(['id', 'client_phone']);
            foreach ($invoices as $inv) {
                $n = Lead::normalizePhone($inv->client_phone);
                if ($n && !isset($invoiceByPhone[$n])) {
                    $invoiceByPhone[$n] = $inv->id;
                }
            }
        }

        foreach ($leads as $lead) {
            $invoiceId = null;
            if ($lead->visitor_id && isset($byVisitor[$lead->visitor_id])) {
                $invoiceId = $byVisitor[$lead->visitor_id];
            } elseif ($lead->phone_norm && isset($invoiceByPhone[$lead->phone_norm])) {
                $invoiceId = $invoiceByPhone[$lead->phone_norm];
            }

            if ($invoiceId) {
                Lead::where('id', $lead->id)->update([
                    'estado' => Lead::ESTADO_COMPRADO,
                    'invoice_id' => $invoiceId,
                ]);
            }
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->notice = '';

        $norm = Lead::normalizePhone($this->phone);
        if (!$norm) {
            $this->notice = 'Teléfono inválido.';
            return;
        }

        if (Lead::where('phone_norm', $norm)->exists()) {
            $this->notice = 'Ese número ya está registrado como lead.';
            return;
        }

        $visitorId = null;
        $ref = trim($this->ref);
        if ($ref !== '') {
            $visitor = Visitor::findByRefCode($ref);
            if (!$visitor) {
                $this->notice = 'Código ref no encontrado. Revisalo o déjalo vacío.';
                return;
            }
            $visitorId = $visitor->id;
        }

        $lead = Lead::create([
            'name' => trim($this->name) ?: null,
            'phone' => trim($this->phone),
            'modelo_interes' => trim($this->modelo) !== '' ? trim($this->modelo) : null,
            'nota' => trim($this->nota) !== '' ? trim($this->nota) : null,
            'estado' => Lead::ESTADO_NUEVO,
            'source' => Lead::SOURCE_MANUAL,
            'visitor_id' => $visitorId,
            'fb_ad_id' => preg_replace('/\D+/', '', $this->ad) ?: null,
        ]);

        // ¿Ya compró? (te escribió después de comprar, por ejemplo)
        if ($purchase = Lead::findPurchase($lead->phone_norm, $lead->visitor_id)) {
            $lead->update(['estado' => Lead::ESTADO_COMPRADO, 'invoice_id' => $purchase->id]);
            $this->notice = 'Registrado, pero ese número ya tiene factura: marcado como comprado.';
        } else {
            $this->notice = 'Lead registrado.';
        }

        $this->reset(['name', 'phone', 'modelo', 'nota', 'ref', 'ad']);
    }

    public function startEditName(int $id): void
    {
        $lead = Lead::find($id);
        if (!$lead) {
            return;
        }

        $this->editingId = $id;
        $this->editName = $lead->name ?? '';
    }

    public function saveName(): void
    {
        if (!$this->editingId) {
            return;
        }

        Lead::where('id', $this->editingId)->update([
            'name' => trim($this->editName) !== '' ? trim($this->editName) : null,
        ]);

        $this->reset('editingId', 'editName');
    }

    public function markContacted(int $id): void
    {
        Lead::where('id', $id)->update([
            'estado' => Lead::ESTADO_CONTACTADO,
            'contacted_at' => now(),
        ]);
    }

    public function markDiscarded(int $id): void
    {
        Lead::where('id', $id)->update(['estado' => Lead::ESTADO_DESCARTADO]);
    }

    public function reactivate(int $id): void
    {
        Lead::where('id', $id)->update(['estado' => Lead::ESTADO_NUEVO]);
    }

    public function delete(int $id): void
    {
        Lead::where('id', $id)->delete();
    }

    public function registerCandidate(int $visitorId): void
    {
        $visitor = Visitor::find($visitorId);
        if (!$visitor) {
            return;
        }

        if (Lead::where('visitor_id', $visitorId)->exists()) {
            return;
        }

        $this->selectedCandidateId = $visitorId;
        $this->candidateName = $visitor->name ?? '';
        $this->candidatePhone = $visitor->phone ?? '';
        $this->notice = '';
    }

    public function saveCandidate(): void
    {
        $this->validate([
            'candidateName' => ['nullable', 'string', 'max:255'],
            'candidatePhone' => ['required', 'string', 'max:30'],
            'clickDate' => ['required', 'date_format:Y-m-d'],
            'clickHour' => ['required', 'regex:/^(0[0-9]|1[0-9]|2[0-3])$/'],
        ]);

        $visitor = Visitor::find($this->selectedCandidateId);
        if (!$visitor) {
            $this->notice = 'No se encontró el visitante. Actualiza la lista e intenta de nuevo.';
            return;
        }

        $norm = Lead::normalizePhone($this->candidatePhone);
        if (!$norm) {
            $this->notice = 'Teléfono inválido.';
            return;
        }

        if ($purchase = Lead::findPurchase($norm, $visitor->id)) {
            $this->notice = 'Ese contacto ya tiene una compra (factura ' . $purchase->invoice_number . '); no se registró como pendiente.';
            $this->reset(['selectedCandidateId', 'candidateName', 'candidatePhone']);
            return;
        }

        if (Lead::where('phone_norm', $norm)->exists()) {
            $this->notice = 'Ese número ya está registrado como lead.';
            return;
        }

        $click = VisitorEvent::query()
            ->where('visitor_id', $visitor->id)
            ->where('type', 'whatsapp_click')
            ->where('created_at', '>=', $this->selectedHourRange()[0])
            ->where('created_at', '<', $this->selectedHourRange()[1])
            ->latest('created_at')
            ->first();

        if (!$click) {
            $this->notice = 'El clic ya no coincide con el rango seleccionado. Actualiza la lista.';
            return;
        }

        $name = trim($this->candidateName) ?: null;
        $phone = trim($this->candidatePhone);
        $visitor->update(['name' => $name, 'phone' => $phone]);

        Lead::create([
            'name' => $name,
            'phone' => $phone,
            'modelo_interes' => null,
            'nota' => 'Clic WhatsApp el ' . $click->created_at->timezone('America/Costa_Rica')->format('d/m/Y H:i'),
            'estado' => Lead::ESTADO_NUEVO,
            'source' => Lead::SOURCE_AUTO,
            'visitor_id' => $visitor->id,
        ]);

        $this->notice = 'Contacto guardado y agregado a leads por retomar.';
        $this->reset(['selectedCandidateId', 'candidateName', 'candidatePhone']);
    }

    /** Rango de una hora, interpretado en la zona horaria de Costa Rica. */
    protected function selectedHourRange(): array
    {
        $start = \Illuminate\Support\Carbon::createFromFormat(
            'Y-m-d H',
            $this->clickDate . ' ' . $this->clickHour,
            'America/Costa_Rica'
        )->startOfHour()->setTimezone(config('app.timezone'));

        return [$start, $start->copy()->addHour()];
    }

    public function render()
    {
        $counts = [
            'retomar' => Lead::porRetomar()->count(),
            'compraron' => Lead::where('estado', Lead::ESTADO_COMPRADO)->count(),
            'descartados' => Lead::where('estado', Lead::ESTADO_DESCARTADO)->count(),
        ];

        $leads = Lead::with(['visitor', 'invoice'])
            ->when($this->tab === 'retomar', fn ($q) => $q->porRetomar())
            ->when($this->tab === 'compraron', fn ($q) => $q->where('estado', Lead::ESTADO_COMPRADO))
            ->when($this->tab === 'descartados', fn ($q) => $q->where('estado', Lead::ESTADO_DESCARTADO))
            ->when($this->tab === 'auto', fn ($q) => $q->whereRaw('1 = 0'))
            ->latest()
            ->get();

        $adNames = \App\Models\FacebookAdReport::whereIn('ad_id', $leads->pluck('fb_ad_id')->filter()->unique()->values())
            ->get(['ad_id', 'campaign_name', 'ad_name', 'spend', 'report_date'])
            ->groupBy('ad_id')
            ->map(function ($rows) {
                $first = $rows->first();
                return (object) [
                    'name' => $first->ad_name ?: $first->campaign_name,
                    'spend' => $rows->sum('spend'),
                ];
            });

        // Anuncios con actividad para el dropdown del form
        $ads = \App\Models\FacebookAdReport::query()
            ->selectRaw("ad_id, COALESCE(NULLIF(ad_name, ''), campaign_name) AS nombre, SUM(spend) AS spend")
            ->whereNotNull('ad_id')
            ->where('ad_id', '!=', '')
            ->groupBy('ad_id', 'nombre')
            ->orderByDesc('spend')
            ->limit(50)
            ->get();

        $whatsappChats = WhatsappChatImport::query()
            ->select(['id', 'source_name', 'contact_name', 'phone', 'message_count', 'first_message_at', 'last_message_at', 'visitor_id', 'lead_id', 'created_at'])
            ->selectRaw('SUBSTRING(transcript, 1, 1200) AS transcript_preview')
            ->with(['visitor', 'lead'])
            ->latest()
            ->limit(10)
            ->get();
        $chatMatches = [];
        foreach ($whatsappChats as $chat) {
            $timestamps = $chat->message_timestamps ?? [];
            if (!$timestamps) {
                $chatMatches[$chat->id] = collect();
                continue;
            }

            $min = Carbon::parse(min($timestamps))->subHours(2);
            $max = Carbon::parse(max($timestamps))->addHours(2);
            $events = VisitorEvent::with('visitor')
                ->whereBetween('created_at', [$min, $max])
                ->whereIn('type', ['page_view', 'product_view', 'search', 'whatsapp_click', 'add_to_cart', 'cta_click'])
                ->whereHas('visitor')
                ->get();

            $chatMatches[$chat->id] = $events
                ->groupBy('visitor_id')
                ->map(function ($visitorEvents) use ($timestamps) {
                    $visitor = $visitorEvents->first()->visitor;
                    $closest = $visitorEvents->sortBy(fn (VisitorEvent $event) => collect($timestamps)
                        ->min(fn ($timestamp) => abs(Carbon::parse($timestamp)->diffInSeconds($event->created_at, false)))
                    )->first();
                    $delta = collect($timestamps)->min(fn ($timestamp) => abs(Carbon::parse($timestamp)->diffInSeconds($closest->created_at, false)));

                    return (object) [
                        'visitor' => $visitor,
                        'event' => $closest,
                        'seconds' => $delta,
                        'clicks' => $visitorEvents->where('type', 'whatsapp_click')->count(),
                        'purchased' => (bool) Lead::findPurchase(Lead::normalizePhone($visitor->phone), $visitor->id),
                    ];
                })
                ->sortBy(fn ($match) => [$match->purchased ? 1 : 0, $match->event->type === 'whatsapp_click' ? 0 : 1, $match->seconds])
                ->take(5)
                ->values();
        }

        // Clics de WhatsApp de la hora seleccionada, sin compra y sin lead previo
        $candidates = collect();
        if ($this->tab === 'auto') {
            try {
                [$from, $until] = $this->selectedHourRange();
            } catch (\Throwable) {
                [$from, $until] = [now()->subHour(), now()];
            }
            $leadVisitorIds = Lead::whereNotNull('visitor_id')->pluck('visitor_id')->all();
            $leadPhones = Lead::pluck('phone_norm')->filter()->all();

            $eventIds = VisitorEvent::query()
                ->selectRaw('MAX(id) as id')
                ->where('type', 'whatsapp_click')
                ->where('created_at', '>=', $from)
                ->where('created_at', '<', $until)
                ->groupBy('visitor_id')
                ->pluck('id');

            $candidates = VisitorEvent::with('visitor')
                ->whereIn('id', $eventIds)
                ->latest('created_at')
                ->take(100)
                ->get()
                ->filter(function (VisitorEvent $event) use ($leadVisitorIds, $leadPhones) {
                    $v = $event->visitor;
                    if (!$v || in_array($v->id, $leadVisitorIds, true)) {
                        return false;
                    }
                    if (Lead::findPurchase(Lead::normalizePhone($v->phone), $v->id)) {
                        return false;
                    }
                    $norm = Lead::normalizePhone($v->phone);
                    return !($norm && in_array($norm, $leadPhones, true));
                })
                ->values();
            $counts['auto'] = $candidates->count();
        } else {
            $counts['auto'] = null;
        }

        return view('livewire.admin.leads', compact('leads', 'candidates', 'counts', 'adNames', 'ads', 'whatsappChats', 'chatMatches'))
            ->layout('components.admin-layout', ['title' => 'Leads']);
    }
}
