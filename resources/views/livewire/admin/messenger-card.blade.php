@php
    $saldo = round((float) $invoice->total - (float) $invoice->abonos->sum('amount'), 2);
    $paymentLabels = [
        'contra_entrega' => 'Cobro contra entrega',
        'sinpe' => 'SINPE',
        'transferencia' => 'Transferencia',
        'paypal' => 'Pagado en línea',
    ];
    $deliveryStatusLabels = ['pendiente' => 'Pendiente', 'creando' => 'Preparando', 'entregado' => 'Entregado'];
    $firstItem = $invoice->items->first();
    $firstProduct = $firstItem?->product ?: ($firstItem && $firstItem->product_model ? ($productByModelo[$firstItem->product_model] ?? null) : null);
    $dialogPhoto = $firstProduct?->imagen ?? '';
    $dialogAmount = $saldo > 0.009 ? '₡'.number_format($saldo, 0).' por cobrar' : 'Pagado';
    $dialogAmountHtml = $saldo > 0.009
        ? '<span style="color:#dc2626">₡'.number_format($saldo, 0).'</span> por cobrar'
        : '<span style="color:#16a34a">Pagado</span>';
@endphp
<article class="overflow-hidden rounded-2xl bg-white dark:bg-[#0f172a] border border-gray-300 dark:border-gray-600 shadow-sm">
    <div class="flex items-center justify-between gap-2 px-3 py-2 {{ $invoice->shipping_status === 'entregado' ? 'bg-emerald-50 dark:bg-emerald-900/20' : 'bg-cyan-100 dark:bg-cyan-900/30' }}">
        <p class="text-base font-black {{ $invoice->shipping_status === 'entregado' ? 'text-emerald-700 dark:text-emerald-400' : 'text-cyan-800 dark:text-cyan-200' }}">
            {{ $invoice->delivery_date?->format('d/m/Y') ?? 'Fecha por definir' }}
            @if($invoice->delivery_time_start)
                <span class="font-medium">· {{ $invoice->delivery_time_start }}{{ $invoice->delivery_time_end ? ' - '.$invoice->delivery_time_end : '' }}</span>
            @endif
        </p>
        @if($invoice->shipping_status === 'entregado')
            <span class="shrink-0 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 px-2 py-0.5 text-xs font-bold">Entregado</span>
        @else
            <span class="shrink-0 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 px-2 py-0.5 text-xs font-bold">{{ $deliveryStatusLabels[$invoice->shipping_status] ?? 'Pendiente' }}</span>
        @endif
    </div>

    <div class="px-3 py-2 space-y-2">
        <div class="flex items-center justify-between gap-2">
            <p class="font-bold text-base text-gray-900 dark:text-white">{{ $invoice->client_name }}</p>
            @if($invoice->client_phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $invoice->client_phone) }}" class="shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-[#00C4FF]/10 px-2.5 py-1.5 text-sm font-bold text-sky-600 dark:text-[#00C4FF]">
                    <i class="fa-solid fa-phone text-[10px]"></i> {{ $invoice->client_phone }}
                </a>
            @endif
        </div>

        @if($invoice->customer_address)
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($invoice->customer_address) }}" target="_blank" rel="noopener" class="flex items-start gap-2 rounded-lg bg-gray-50 dark:bg-white/5 px-2.5 py-2 text-sm leading-5 text-gray-800 dark:text-gray-200">
                <i class="fa-solid fa-location-dot mt-0.5 text-red-500"></i>
                <span>{{ $invoice->customer_address }}</span>
                <i class="fa-solid fa-arrow-up-right-from-square ml-auto mt-0.5 shrink-0 text-gray-400"></i>
            </a>
        @endif

        <div class="space-y-1.5">
            @foreach($invoice->items as $item)
                @php $product = $item->product ?: ($productByModelo[$item->product_model] ?? null); @endphp
                <div class="flex items-center gap-2">
                    @if($product?->imagen)
                        <img src="{{ $product->imagen }}" alt="{{ $item->product_model ?: $item->product_name }}" class="shrink-0 rounded-lg bg-gray-100 object-contain dark:bg-white/5" style="width:112px;height:112px">
                    @else
                        <div class="grid shrink-0 place-items-center rounded-lg bg-gray-100 text-gray-400 dark:bg-white/5" style="width:112px;height:112px"><i class="fa-regular fa-image"></i></div>
                    @endif
                    <p class="text-base font-bold text-gray-900 dark:text-white">{{ $item->product_model ?: $item->product_name }}@if($item->quantity > 1) <span class="font-medium text-gray-500">× {{ $item->quantity }}</span>@endif</p>
                </div>
            @endforeach
        </div>

        @if($invoice->location || $invoice->notes || $invoice->needs_bracelet_adjustment || $invoice->payment_method)
            <div class="space-y-0.5 text-sm text-gray-700 dark:text-gray-300">
                @if($invoice->payment_method)<p><i class="fa-solid fa-wallet mr-1.5 text-gray-400"></i>{{ $paymentLabels[$invoice->payment_method] ?? $invoice->payment_method }}</p>@endif
                @if($invoice->location)<p><i class="fa-solid fa-map-pin mr-1.5 text-gray-400"></i>{{ $invoice->location }}</p>@endif
                @if($invoice->notes)<p><i class="fa-solid fa-note-sticky mr-1.5 text-gray-400"></i>{{ $invoice->notes }}</p>@endif
                @if($invoice->needs_bracelet_adjustment)<p class="font-bold text-red-600"><i class="fa-solid fa-wrench mr-1.5"></i>Requiere ajuste de brazalete</p>@endif
            </div>
        @endif

        <div class="flex items-center justify-between gap-2 rounded-lg border px-2.5 py-1.5 {{ $saldo > 0.009 ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800/30' : 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50' }}">
            @if($saldo > 0.009)
                <span class="text-xs font-black uppercase tracking-[0.14em] text-amber-700 dark:text-amber-400">Cobrar</span>
                <span class="text-xl font-black text-amber-700 dark:text-amber-400">₡{{ number_format($saldo, 0) }}</span>
            @else
                <span class="text-xs font-black uppercase tracking-[0.14em] text-emerald-700 dark:text-emerald-400">Cobro</span>
                <span class="text-base font-black text-emerald-700 dark:text-emerald-400">Pagado</span>
            @endif
        </div>

        @if($showActions)
            <button data-id="{{ $invoice->id }}" data-photo="{{ $dialogPhoto }}" data-amount-html="{{ $dialogAmountHtml }}" onclick="confirmDelivered(this)" class="w-full px-4 py-3 text-sm bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold">
                <i class="fa-solid fa-check mr-1.5"></i> Marcar entregado
            </button>
        @endif
    </div>
</article>
