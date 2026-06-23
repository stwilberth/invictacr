<div>
    <div class="flex gap-2 mb-6">
        <input wire:model.live="search" type="text" placeholder="Buscar factura o cliente..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm w-80" />
        <select wire:model.live="filterStatus" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm">
            <option value="">Todos los estados</option>
            <option value="pending">Pendiente</option>
            <option value="paid">Pagada</option>
            <option value="cancelled">Cancelada</option>
        </select>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3">Factura</th>
                    <th class="text-left px-4 py-3">Cliente</th>
                    <th class="text-right px-4 py-3">Total</th>
                    <th class="text-center px-4 py-3">Estado</th>
                    <th class="text-right px-4 py-3">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $invoice->client_name }}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">₡{{ number_format($invoice->total, 0) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            @if($invoice->status === 'paid') bg-green-100 text-green-700
                            @elseif($invoice->status === 'pending') bg-amber-100 text-amber-700
                            @else bg-gray-100 text-gray-500 @endif">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ $invoice->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $invoices->links() }}</div>
</div>