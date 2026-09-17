{{-- Comprobantes de pago de la factura (contado: múltiples, ej. efectivo + transferencia) --}}
@if(strtolower($invoice->status) !== 'apartado')
<div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 overflow-x-auto">
    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Comprobantes de pago</h3>

    @if($invoice->receipts->count() > 0)
    <div class="flex flex-wrap gap-3 mb-4">
        @foreach($invoice->receipts as $receipt)
        <div class="relative group w-24">
            @if($receipt->is_image)
            <a href="{{ $receipt->url }}" target="_blank" rel="noopener" title="{{ $receipt->original_name ?? 'Ver comprobante' }}">
                <img src="{{ $receipt->url }}" alt="Comprobante" class="w-24 h-24 object-cover rounded-xl border border-gray-200 dark:border-white/10 hover:opacity-80 transition-opacity" loading="lazy" />
            </a>
            @else
            <a href="{{ $receipt->url }}" target="_blank" rel="noopener" title="{{ $receipt->original_name ?? 'Ver comprobante' }}" class="w-24 h-24 flex flex-col items-center justify-center gap-1 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-gray-500 hover:opacity-80 transition-opacity">
                <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                <span class="text-[9px] font-bold uppercase px-1 truncate w-full text-center">{{ pathinfo($receipt->original_name ?? '', PATHINFO_EXTENSION) ?: 'PDF' }}</span>
            </a>
            @endif
            <button wire:click="deleteReceipt({{ $receipt->id }})" wire:confirm="¿Eliminar este comprobante?" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-600 text-white text-xs items-center justify-center hidden group-hover:flex hover:bg-red-700" title="Eliminar">×</button>
            @if($receipt->original_name)
            <p class="text-[10px] text-gray-400 truncate mt-1" title="{{ $receipt->original_name }}">{{ $receipt->original_name }}</p>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <p class="text-sm text-gray-500 mb-4">Sin comprobantes registrados.</p>
    @endif

    <div class="border-t border-gray-200 dark:border-white/10 pt-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Agregar comprobantes</h4>
        <form action="{{ route('admin.invoices.receipts.store', $invoice->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-end flex-wrap">
            @csrf
            <div>
                <label class="text-xs text-gray-500 block mb-1">Imágenes o PDF (varios a la vez)</label>
                <input type="file" name="receipts[]" multiple accept="image/*,.pdf" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm max-w-64 text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                @error('receipts') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                @error('receipts.*') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">Subir</button>
        </form>
    </div>
</div>
@endif
