<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\WaitlistEntry;
use App\Models\WaitlistNotification;
use Livewire\Component;
use Livewire\WithPagination;

class Waitlist extends Component
{
    use WithPagination;

    public string $nombre = '';
    public string $telefono = '';
    public string $modelo = '';
    public string $nota = '';

    public string $search = '';
    public string $filtroEstado = 'todos';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado(): void
    {
        $this->resetPage();
    }

    public function agregar(): void
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'modelo' => 'required|string|max:50',
            'nota' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre del contacto es obligatorio.',
            'modelo.required' => 'El número de modelo es obligatorio.',
        ]);

        // Se permite cualquier modelo, sin validar estado/stock del reloj.
        WaitlistEntry::create([
            'nombre' => trim($this->nombre),
            'telefono' => trim($this->telefono) ?: null,
            'modelo' => $this->modelo,
            'nota' => trim($this->nota) ?: null,
            'estado' => WaitlistEntry::ESTADO_PENDIENTE,
        ]);

        $this->reset(['nombre', 'telefono', 'modelo', 'nota']);

        session()->flash('message', 'Contacto agregado a la lista de espera.');
    }

    public function marcarContactado(int $id): void
    {
        $entry = WaitlistEntry::findOrFail($id);
        $entry->update(['estado' => WaitlistEntry::ESTADO_CONTACTADO]);
    }

    public function reactivar(int $id): void
    {
        $entry = WaitlistEntry::findOrFail($id);
        $entry->update(['estado' => WaitlistEntry::ESTADO_PENDIENTE, 'notified_at' => null]);
    }

    public function descartar(int $id): void
    {
        $entry = WaitlistEntry::findOrFail($id);
        $entry->update(['estado' => WaitlistEntry::ESTADO_DESCARTADO]);
    }

    public function eliminar(int $id): void
    {
        WaitlistEntry::findOrFail($id)->delete();
        session()->flash('message', 'Registro eliminado de la lista de espera.');
    }

    public function marcarLeida(int $id): void
    {
        $notif = WaitlistNotification::findOrFail($id);
        if (!$notif->leida_at) {
            $notif->update(['leida_at' => now()]);
        }
    }

    public function marcarTodasLeidas(): void
    {
        WaitlistNotification::whereNull('leida_at')->update(['leida_at' => now()]);
    }

    public function getNotificationsProperty()
    {
        return WaitlistNotification::with('entry')
            ->latest()
            ->take(20)
            ->get();
    }

    public function getUnreadCountProperty(): int
    {
        return WaitlistNotification::whereNull('leida_at')->count();
    }

    public function stockDe(string $modelo): ?Product
    {
        return Product::where('modelo', WaitlistEntry::normalizeModelo($modelo))->first();
    }

    public function render()
    {
        $query = WaitlistEntry::query();

        if ($this->filtroEstado !== 'todos') {
            $query->where('estado', $this->filtroEstado);
        }

        if (trim($this->search) !== '') {
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('nombre', 'like', "%{$s}%")
                    ->orWhere('telefono', 'like', "%{$s}%")
                    ->orWhere('modelo', 'like', "%{$s}%");
            });
        }

        $entries = $query->latest()->paginate(20);

        $pendientesCount = WaitlistEntry::where('estado', WaitlistEntry::ESTADO_PENDIENTE)->count();

        return view('livewire.admin.waitlist', [
            'entries' => $entries,
            'pendientesCount' => $pendientesCount,
        ])->layout('components.admin-layout', ['title' => 'Lista de Espera']);
    }
}
