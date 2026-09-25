<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Visitor;
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
    public string $notice = '';

    protected function rules(): array
    {
        return [
            'phone' => 'required|string|max:30',
            'name' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:100',
            'nota' => 'nullable|string|max:1000',
            'ref' => 'nullable|string|max:20',
        ];
    }

    public function mount(): void
    {
        $this->syncPurchases();
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
        ]);

        // ¿Ya compró? (te escribió después de comprar, por ejemplo)
        if ($purchase = Lead::findPurchase($lead->phone_norm, $lead->visitor_id)) {
            $lead->update(['estado' => Lead::ESTADO_COMPRADO, 'invoice_id' => $purchase->id]);
            $this->notice = 'Registrado, pero ese número ya tiene factura: marcado como comprado.';
        } else {
            $this->notice = 'Lead registrado.';
        }

        $this->reset(['name', 'phone', 'modelo', 'nota', 'ref']);
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

        $norm = Lead::normalizePhone($visitor->phone);
        if ($norm && Lead::where('phone_norm', $norm)->exists()) {
            return;
        }

        Lead::create([
            'name' => $visitor->name,
            'phone' => $visitor->phone ?? ('visitante ' . $visitor->ref_code),
            'modelo_interes' => null,
            'nota' => 'Clic WhatsApp el ' . ($visitor->whatsapp_clicked_at?->format('d/m H:i') ?? ''),
            'estado' => Lead::ESTADO_NUEVO,
            'source' => Lead::SOURCE_AUTO,
            'visitor_id' => $visitor->id,
        ]);

        $this->syncPurchases();
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

        // Candidatos auto: clic WhatsApp últimos 7 días, sin factura y sin lead
        $candidates = collect();
        if ($this->tab === 'auto') {
            $since = now()->subDays(7);
            $leadVisitorIds = Lead::whereNotNull('visitor_id')->pluck('visitor_id')->all();
            $leadPhones = Lead::pluck('phone_norm')->filter()->all();

            $candidates = Visitor::where('whatsapp_clicked_at', '>=', $since)
                ->whereNotIn('id', $leadVisitorIds ?: [0])
                ->latest('whatsapp_clicked_at')
                ->take(100)
                ->get()
                ->filter(function (Visitor $v) use ($leadPhones) {
                    if (Lead::findPurchase(Lead::normalizePhone($v->phone), $v->id)) {
                        return false;
                    }
                    $norm = Lead::normalizePhone($v->phone);
                    return !($norm && in_array($norm, $leadPhones, true));
                })
                ->values();
            $counts['auto'] = $candidates->count();
        } else {
            $counts['auto'] = Visitor::where('whatsapp_clicked_at', '>=', now()->subDays(7))
                ->whereNotIn('id', Lead::whereNotNull('visitor_id')->pluck('visitor_id')->all() ?: [0])
                ->count();
        }

        return view('livewire.admin.leads', compact('leads', 'candidates', 'counts'))
            ->layout('components.admin-layout', ['title' => 'Leads']);
    }
}
