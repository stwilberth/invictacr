<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between gap-3 mb-3">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.18em] text-[#00C4FF]">Operación</p>
            <h2 class="text-xl font-black text-gray-900 dark:text-white">Mensajero</h2>
        </div>
        <a href="{{ route('admin.messenger') }}" class="w-11 h-11 shrink-0 grid place-items-center rounded-xl bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 text-gray-500 hover:text-[#00C4FF]" aria-label="Actualizar entregas">
            <i class="fa-solid fa-rotate-right"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 gap-2 mb-3">
        <button wire:click="setTab('pendientes')" class="px-4 py-3 rounded-xl text-sm font-bold transition-colors {{ $tab === 'pendientes' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">
            Pendientes ({{ $invoices->count() }})
        </button>
        <button wire:click="setTab('entregados')" class="px-4 py-3 rounded-xl text-sm font-bold transition-colors {{ $tab === 'entregados' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10' }}">
            Entregados ({{ $delivered->count() }})
        </button>
    </div>

    @if($tab === 'pendientes')
        <div class="space-y-3">
            @forelse($invoices as $invoice)
                @include('livewire.admin.messenger-card', ['invoice' => $invoice, 'productByModelo' => $productByModelo, 'showActions' => true])
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center dark:border-white/10 dark:bg-[#0f172a]">
                    <i class="fa-solid fa-check-circle text-3xl text-emerald-500"></i>
                    <p class="mt-3 font-black text-gray-900 dark:text-white">No hay entregas pendientes</p>
                    <p class="mt-1 text-sm text-gray-500">Las nuevas entregas aparecerán aquí automáticamente.</p>
                </div>
            @endforelse
        </div>
    @else
        <div class="space-y-3">
            @forelse($delivered as $invoice)
                @include('livewire.admin.messenger-card', ['invoice' => $invoice, 'productByModelo' => $productByModelo, 'showActions' => false])
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center dark:border-white/10 dark:bg-[#0f172a]">
                    <i class="fa-solid fa-truck text-3xl text-gray-300 dark:text-gray-600"></i>
                    <p class="mt-3 font-black text-gray-900 dark:text-white">Aún no hay entregas hoy</p>
                    <p class="mt-1 text-sm text-gray-500">Aquí verás las entregas marcadas durante el día.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>

<script>
    function confirmDelivered(btn) {
        const popup = {
            title: btn.dataset.amountHtml,
            text: '¿Marcar como entregada?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        };
        if (btn.dataset.photo) {
            popup.imageUrl = btn.dataset.photo;
            popup.imageWidth = 180;
            delete popup.icon;
        }
        window.Swal.fire(popup).then((result) => {
            if (result.isConfirmed) {
                @this.call('markDelivered', parseInt(btn.dataset.id, 10));
            }
        });
    }
</script>
