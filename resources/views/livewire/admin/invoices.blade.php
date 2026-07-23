<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-black">Facturas</h2>
        <a href="{{ route('admin.invoices.create') }}" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">+ Crear Factura</a>
    </div>

    {{-- Totals --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Facturas</div>
            <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totals->count }}</div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Total Vendido</div>
            <div class="text-2xl font-black text-green-600 dark:text-green-400 mt-1">₡{{ number_format($totals->totalAmount, 0) }}</div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Descuentos</div>
            <div class="text-2xl font-black text-red-500 mt-1">-₡{{ number_format($totals->totalDiscount, 0) }}</div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Envío</div>
            <div class="text-2xl font-black text-blue-500 mt-1">₡{{ number_format($totals->totalShipping, 0) }}</div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Utilidad</div>
            <div class="text-2xl font-black text-[#00C4FF] mt-1">₡{{ number_format($totals->totalUtility, 0) }}</div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Promedio</div>
            <div class="text-2xl font-black text-gray-500 mt-1">₡{{ number_format($totals->average, 0) }}</div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4 mb-6 space-y-3">
        <div class="flex gap-2 flex-wrap items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar factura, cliente o teléfono..." class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm min-w-[250px] flex-1" />
            <button wire:click="resetFilters" class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 border border-gray-200 dark:border-white/10 rounded-xl">Limpiar filtros</button>
        </div>
        <div class="flex gap-2 flex-wrap">
            <select wire:model.live="filterStatus" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-xs">
                <option value="">Todos los estados</option>
                <option value="facturado">Facturado</option>
                <option value="apartado">Apartado</option>
                <option value="eliminado">Eliminado</option>
                <option value="pending">Pendiente</option>
                <option value="cancelled">Cancelada</option>
            </select>
            <select wire:model.live="filterShipping" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-xs">
                <option value="">Todos los envíos</option>
                <option value="entregado">Entregado</option>
                <option value="creando">Creando</option>
                <option value="pendiente">Pendiente</option>
                <option value="cancelado">Cancelado</option>
            </select>
            <select wire:model.live="filterAbonos" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-xs">
                <option value="">Todos (abonos)</option>
                <option value="con_abonos">Con abonos</option>
                <option value="sin_abonos">Sin abonos</option>
            </select>
            <input wire:model.live="dateFrom" type="date" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-xs" />
            <span class="text-xs text-gray-400 self-center">a</span>
            <input wire:model.live="dateTo" type="date" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-xs" />
            <input wire:model.live.debounce.500ms="totalMin" type="number" placeholder="Total min" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-xs w-24" />
            <span class="text-xs text-gray-400 self-center">a</span>
            <input wire:model.live.debounce.500ms="totalMax" type="number" placeholder="Total max" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-xs w-24" />
        </div>
    </div>

    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/5 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3">Factura</th>
                    <th class="text-left px-4 py-3">Cliente</th>
                    <th class="text-right px-4 py-3">Total</th>
                    <th class="text-right px-4 py-3">Utilidad</th>
                    <th class="text-center px-4 py-3">Estado</th>
                    <th class="text-center px-4 py-3">Envío</th>
                    <th class="text-right px-4 py-3">Abonos</th>
                    <th class="text-right px-4 py-3">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="px-4 py-3 font-bold">
                        <a href="{{ route('admin.invoices.detail', $invoice->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-gray-900 dark:text-white">{{ $invoice->client_name }}</div>
                        @if($invoice->client_phone)
                            <div class="text-xs text-gray-500">{{ $invoice->client_phone }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">₡{{ number_format($invoice->total, 0) }}</td>
                    <td class="px-4 py-3 text-right font-bold {{ $invoice->estimated_utility > 0 ? 'text-[#00C4FF]' : 'text-gray-400' }}">₡{{ number_format($invoice->estimated_utility ?? 0, 0) }}</td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $statusClasses = [
                                'facturado' => 'bg-green-100 text-green-700',
                                'apartado' => 'bg-purple-100 text-purple-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                'eliminado' => 'bg-red-100 text-red-700',
                                'cancelled' => 'bg-gray-100 text-gray-500',
                            ];
                            $statusLabels = [
                                'facturado' => 'Facturado',
                                'apartado' => 'Apartado',
                                'pending' => 'Pendiente',
                                'eliminado' => 'Eliminado',
                                'cancelled' => 'Cancelada',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $statusClasses[strtolower($invoice->status)] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ $statusLabels[strtolower($invoice->status)] ?? ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $shippingClasses = [
                                'entregado' => 'bg-green-100 text-green-700',
                                'creando' => 'bg-blue-100 text-blue-700',
                                'pendiente' => 'bg-amber-100 text-amber-700',
                                'cancelado' => 'bg-red-100 text-red-700',
                            ];
                            $shippingLabels = [
                                'entregado' => 'Entregado',
                                'creando' => 'Creando',
                                'pendiente' => 'Pendiente',
                                'cancelado' => 'Cancelado',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $shippingClasses[$invoice->shipping_status] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ $shippingLabels[$invoice->shipping_status] ?? ucfirst($invoice->shipping_status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400 text-xs">
                        @php
                            $totalAbonos = $invoice->abonos->sum('amount');
                            $saldo = $invoice->total - $totalAbonos;
                        @endphp
                        @if($totalAbonos > 0)
                            <div>₡{{ number_format($totalAbonos, 0) }}</div>
                            @if($saldo > 0)
                                <div class="text-red-500 font-bold">Saldo: ₡{{ number_format($saldo, 0) }}</div>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400 text-xs">{{ $invoice->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">No se encontraron facturas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $invoices->links() }}</div>
</div>
