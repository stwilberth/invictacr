<div>
    {{-- Period selector --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Conversión por modelo</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Clics en cada CTA por reloj para medir interés y ventas.</p>
        </div>
        <div class="flex items-center gap-3">
            <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periodo</label>
            <select wire:model.live="days" class="bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300">
                <option value="7">7 días</option>
                <option value="14">14 días</option>
                <option value="30">30 días</option>
            </select>
        </div>
    </div>

    {{-- Totals cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-[#00C4FF]">{{ number_format($totals['vistas']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Vistas de reloj</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-green-500">{{ number_format($totals['cta_click']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Clics en CTA</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-purple-500">{{ number_format($totals['add_to_cart']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Compras carrito</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-emerald-500">{{ number_format($totals['whatsapp']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Clics WhatsApp</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-2xl font-black text-amber-500">{{ number_format($totals['carrito_tasa'], 1) }}%</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tasa carrito</p>
        </div>
    </div>

    {{-- CTA breakdown chart --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <h2 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">
            <i class="fa-solid fa-chart-pie text-[#00C4FF] mr-2"></i> Clics por tipo de botón
        </h2>
        <div class="grid grid-cols-3 gap-3 text-center mb-4">
            <div class="rounded-xl bg-[#00C4FF]/10 border border-[#00C4FF]/20 p-3">
                <p class="text-xl font-black text-[#00C4FF]">{{ number_format($byCta['comprar-ahora'] ?? 0) }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Comprar ahora</p>
            </div>
            <div class="rounded-xl bg-green-500/10 border border-green-500/20 p-3">
                <p class="text-xl font-black text-green-500">{{ number_format($byCta['comprar-whatsapp'] ?? 0) }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Comprar por WhatsApp</p>
            </div>
            <div class="rounded-xl bg-amber-500/10 border border-amber-500/20 p-3">
                <p class="text-xl font-black text-amber-500">{{ number_format($byCta['ver-disponibilidad'] ?? 0) }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Ver disponibilidad</p>
            </div>
        </div>
        <div class="relative" style="height: 220px;">
            <canvas id="ctaChart"></canvas>
        </div>
    </div>

    {{-- Search --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Buscar por modelo o nombre..."
            class="w-full sm:w-64 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm"
        />
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span class="font-bold text-gray-900 dark:text-white">{{ $products->total() }}</span> modelos con actividad
        </p>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Modelo</th>
                        <th class="px-4 py-3 text-center">Vistas</th>
                        <th class="px-4 py-3 text-center">Comprar ahora</th>
                        <th class="px-4 py-3 text-center">WhatsApp</th>
                        <th class="px-4 py-3 text-center">Ver dispon.</th>
                        <th class="px-4 py-3 text-center">Carrito</th>
                        <th class="px-4 py-3 text-center">Tasa carrito</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($row->imagen)
                                        <img src="{{ $row->imagen }}" class="w-full h-full object-contain" alt="" loading="lazy">
                                    @else
                                        <i class="fa-solid fa-clock text-[#00C4FF]"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('products.show', $row->modelo) }}" target="_blank" class="font-bold text-[#00C4FF] hover:underline text-xs">{{ $row->modelo }}</a>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 max-w-[200px] truncate">{{ $row->title }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">{{ number_format($row->product_view) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-[#00C4FF] font-bold">{{ number_format($row->{"cta_comprar-ahora"}) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-green-500 font-bold">{{ number_format($row->{"cta_comprar-whatsapp"}) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-amber-500 font-bold">{{ number_format($row->{"cta_ver-disponibilidad"}) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-purple-500 font-bold">{{ number_format($row->add_to_cart) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-gray-700 dark:text-gray-300 font-bold">{{ number_format($row->tasa, 1) }}%</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
                            No hay eventos registrados en este periodo. Los clics en los botones de compra aparecerán aquí.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:init', function () {
            const ctx = document.getElementById('ctaChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Comprar ahora', 'Comprar por WhatsApp', 'Ver disponibilidad'],
                    datasets: [{
                        label: 'Clics',
                        data: [
                            {{ $byCta['comprar-ahora'] ?? 0 }},
                            {{ $byCta['comprar-whatsapp'] ?? 0 }},
                            {{ $byCta['ver-disponibilidad'] ?? 0 }},
                        ],
                        backgroundColor: ['rgba(0, 196, 255, 0.7)', 'rgba(37, 211, 102, 0.7)', 'rgba(245, 158, 11, 0.7)'],
                        borderColor: ['rgba(0, 196, 255, 1)', 'rgba(37, 211, 102, 1)', 'rgba(245, 158, 11, 1)'],
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: 'rgba(255,255,255,0.05)' }
                        },
                        x: {
                            ticks: { color: '#9ca3af', font: { size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</div>
