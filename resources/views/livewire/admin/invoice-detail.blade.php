<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.invoices') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">&larr; Volver a facturas</a>
            <h2 class="text-xl font-black mt-1">Factura {{ $invoice->invoice_number }}</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.invoices') }}" class="px-4 py-2 text-sm border border-gray-200 dark:border-white/10 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5">Volver</a>
            <a href="{{ route('invoice.pdf', $invoice->id) }}" target="_blank" class="px-4 py-2 text-sm bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold">Descargar PDF</a>
            <button onclick="confirmDelete()" class="px-4 py-2 text-sm bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold">Eliminar</button>
            <script>
                function confirmDelete() {
                    Swal.fire({
                        title: '¿Eliminar factura?',
                        text: 'Se eliminará la factura, sus items y abonos. Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('delete');
                        }
                    });
                }
            </script>
            @if($editing)
                <button wire:click="$set('editing', false)" class="px-4 py-2 text-sm border border-gray-200 dark:border-white/10 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5">Cancelar</button>
                <button wire:click="save" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">Guardar cambios</button>
            @else
                <button wire:click="$set('editing', true)" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">Editar</button>
            @endif
        </div>
    </div>

    @if(session('message'))
        <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
            {{ session('message') }}
        </div>
    @endif

    {{-- MODO VISUALIZACIÓN --}}
    @if(!$editing)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Columna izquierda: info del cliente y productos --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Cliente --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Cliente</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 text-xs">Nombre</span>
                        <p class="font-medium">{{ $invoice->client_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs">Teléfono</span>
                        <p class="font-medium">{{ $invoice->client_phone ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs">Email</span>
                        <p class="font-medium">{{ $invoice->client_email ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs">Cédula</span>
                        <p class="font-medium">{{ $invoice->cedula ?? '-' }}</p>
                    </div>
                    @if($invoice->customer_address)
                    <div class="col-span-2">
                        <span class="text-gray-500 text-xs">Dirección</span>
                        <p class="font-medium">{{ $invoice->customer_address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Productos --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Productos</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-200 dark:border-white/10">
                            <th class="text-left py-2">Producto</th>
                            <th class="text-center py-2">Cant</th>
                            <th class="text-right py-2">Precio</th>
                            <th class="text-right py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="py-2">
                                <div class="font-medium">{{ $item->product_name }}</div>
                                @if($item->product_model)
                                    <div class="text-xs text-gray-500">Modelo: {{ $item->product_model }}</div>
                                @endif
                            </td>
                            <td class="py-2 text-center">{{ $item->quantity }}</td>
                            <td class="py-2 text-right">₡{{ number_format($item->unit_price, 0) }}</td>
                            <td class="py-2 text-right font-medium">₡{{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Abonos --}}
            @if(strtolower($invoice->status) === 'apartado' || $invoice->abonos->count() > 0)
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Abonos</h3>
                @if($invoice->abonos->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-200 dark:border-white/10">
                            <th class="text-left py-2">Fecha</th>
                            <th class="text-right py-2">Monto</th>
                            <th class="text-left py-2">Nota</th>
                            <th class="text-right py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->abonos as $abono)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="py-2 text-xs">{{ $abono->date?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="py-2 text-right font-medium text-green-600">₡{{ number_format($abono->amount, 0) }}</td>
                            <td class="py-2 text-xs text-gray-500">{{ $abono->note ?? '-' }}</td>
                            <td class="py-2 text-right">
                                <button wire:click="deleteAbono({{ $abono->id }})" wire:confirm="¿Eliminar este abono?" class="text-red-500 hover:text-red-700 text-xs">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold">
                            <td class="pt-3 text-xs text-gray-500">Total abonado</td>
                            <td class="pt-3 text-right text-green-600">₡{{ number_format($invoice->abonos->sum('amount'), 0) }}</td>
                            <td colspan="2"></td>
                        </tr>
                        @php $saldo = $invoice->total - $invoice->abonos->sum('amount'); @endphp
                        @if($saldo > 0)
                        <tr class="font-bold">
                            <td class="text-xs text-red-500">Saldo pendiente</td>
                            <td class="text-right text-red-500">₡{{ number_format($saldo, 0) }}</td>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
                @else
                    <p class="text-sm text-gray-500">Sin abonos registrados.</p>
                @endif
                
                <div class="border-t border-gray-200 dark:border-white/10 pt-4 mt-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Agregar abono</h4>
                    <div class="flex gap-2 items-end flex-wrap">
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Monto</label>
                            <input wire:model="newAbonoAmount" type="number" step="0.01" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm w-32" />
                            @error('newAbonoAmount') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Fecha</label>
                            <input wire:model="newAbonoDate" type="datetime-local" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Nota</label>
                            <input wire:model="newAbonoNote" type="text" placeholder="ej: primer abono" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm w-48" />
                        </div>
                        <button wire:click="addAbono" class="px-4 py-2 text-sm bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold">Agregar</button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Columna derecha: montos y estados (solo lectura) --}}
        <div class="space-y-6">
            {{-- Montos --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Montos</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium">₡{{ number_format($invoice->subtotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Descuento</span>
                        <span class="font-medium">₡{{ number_format($invoice->discount, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Envío</span>
                        <span class="font-medium">₡{{ number_format($invoice->shipping, 0) }}</span>
                    </div>
                    @if($invoice->shipping_cost)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Costo envío</span>
                        <span class="font-medium">₡{{ number_format($invoice->shipping_cost, 0) }}</span>
                    </div>
                    @endif
                    <hr class="dark:border-white/10" />
                    <div class="flex justify-between text-lg font-black">
                        <span>Total</span>
                        <span>₡{{ number_format($invoice->total, 0) }}</span>
                    </div>
                    @if($invoice->estimated_utility)
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Utilidad estimada</span>
                        <span class="text-green-600 font-medium">₡{{ number_format($invoice->estimated_utility, 0) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Estados --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Estados</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Factura</span>
                        @php
                            $statusLabels = ['facturado' => 'Facturado', 'apartado' => 'Apartado', 'pending' => 'Pendiente', 'eliminado' => 'Eliminado', 'cancelled' => 'Cancelada'];
                            $statusClasses = ['facturado' => 'bg-green-100 text-green-700', 'apartado' => 'bg-purple-100 text-purple-700', 'pending' => 'bg-amber-100 text-amber-700', 'eliminado' => 'bg-red-100 text-red-700', 'cancelled' => 'bg-gray-100 text-gray-500'];
                        @endphp
                        <div x-data="{
                            current: '{{ strtolower($invoice->status) }}',
                            async change(e) {
                                let newVal = e.target.value;
                                let selectEl = e.target;
                                
                                const res = await window.Swal.fire({
                                    title: '¿Confirmar cambio?',
                                    text: '¿Seguro que deseas cambiar el estado?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#2563eb',
                                    cancelButtonColor: '#64748b',
                                    confirmButtonText: 'Sí, cambiar',
                                    cancelButtonText: 'Cancelar'
                                });
                                
                                if (res.isConfirmed) {
                                    await $wire.updateStatus(newVal);
                                    this.current = newVal;
                                    window.Swal.fire({
                                        title: '¡Actualizado!',
                                        text: 'El estado ha sido cambiado.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    selectEl.value = this.current;
                                }
                            }
                        }">
                            <select @change="change" class="px-2 py-1 pr-6 rounded-lg text-xs font-bold border-0 cursor-pointer focus:ring-0 appearance-none {{ $statusClasses[strtolower($invoice->status)] ?? 'bg-gray-100 text-gray-500' }}">
                                @foreach($statusLabels as $val => $label)
                                    <option value="{{ $val }}" {{ strtolower($invoice->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Envío</span>
                        @php
                            $shippingLabels = ['entregado' => 'Entregado', 'creando' => 'Creando', 'pendiente' => 'Pendiente', 'cancelado' => 'Cancelado'];
                            $shippingClasses = ['entregado' => 'bg-green-100 text-green-700', 'creando' => 'bg-blue-100 text-blue-700', 'pendiente' => 'bg-amber-100 text-amber-700', 'cancelado' => 'bg-red-100 text-red-700'];
                        @endphp
                        <div x-data="{
                            current: '{{ strtolower($invoice->shipping_status) }}',
                            async change(e) {
                                let newVal = e.target.value;
                                let selectEl = e.target;
                                
                                const res = await window.Swal.fire({
                                    title: '¿Confirmar cambio?',
                                    text: '¿Seguro que deseas cambiar el estado de envío?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#2563eb',
                                    cancelButtonColor: '#64748b',
                                    confirmButtonText: 'Sí, cambiar',
                                    cancelButtonText: 'Cancelar'
                                });
                                
                                if (res.isConfirmed) {
                                    await $wire.updateShippingStatus(newVal);
                                    this.current = newVal;
                                    window.Swal.fire({
                                        title: '¡Actualizado!',
                                        text: 'El estado de envío ha cambiado.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    selectEl.value = this.current;
                                }
                            }
                        }">
                            <select @change="change" class="px-2 py-1 pr-6 rounded-lg text-xs font-bold border-0 cursor-pointer focus:ring-0 appearance-none {{ $shippingClasses[strtolower($invoice->shipping_status)] ?? 'bg-gray-100 text-gray-500' }}">
                                @foreach($shippingLabels as $val => $label)
                                    <option value="{{ $val }}" {{ strtolower($invoice->shipping_status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Entrega --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Entrega</h3>
                <div class="space-y-2 text-sm">
                    @if($invoice->delivery_date)
                    <div><span class="text-gray-500 text-xs">Fecha:</span> {{ $invoice->delivery_date->format('d/m/Y') }}</div>
                    @endif
                    @if($invoice->delivery_time_start)
                    <div><span class="text-gray-500 text-xs">Hora:</span> {{ $invoice->delivery_time_start }}{{ $invoice->delivery_time_end ? ' - '.$invoice->delivery_time_end : '' }}</div>
                    @endif
                    @if($invoice->location)
                    <div><span class="text-gray-500 text-xs">Ubicación:</span> {{ $invoice->location }}</div>
                    @endif
                    @if($invoice->notes)
                    <div><span class="text-gray-500 text-xs">Notas:</span> {{ $invoice->notes }}</div>
                    @endif
                    @if($invoice->needs_bracelet_adjustment)
                    <div><span class="text-red-500 text-xs">Requiere ajuste de brazalete</span></div>
                    @endif
                </div>
            </div>

            {{-- Fechas --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Fechas</h3>
                <div class="space-y-1 text-xs text-gray-500">
                    <div class="flex justify-between">
                        <span>Creado</span>
                        <span>{{ $invoice->created_at?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Actualizado</span>
                        <span>{{ $invoice->updated_at?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                    @if($invoice->creation_date)
                    <div class="flex justify-between">
                        <span>Fecha personalizada</span>
                        <span>{{ $invoice->creation_date->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODO EDICIÓN --}}
    @if($editing)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Columna izquierda: info del cliente --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Información del cliente --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Cliente</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Nombre</label>
                        <input wire:model="client_name" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                        @error('client_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Email</label>
                        <input wire:model="client_email" type="email" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Teléfono</label>
                        <input wire:model="client_phone" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Cédula</label>
                        <input wire:model="cedula" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs text-gray-500 block mb-1">Dirección</label>
                        <input wire:model="customer_address" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                </div>
            </div>

            {{-- Productos / Items --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Productos</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-200 dark:border-white/10">
                            <th class="text-left py-2">Producto</th>
                            <th class="text-center py-2">Cant</th>
                            <th class="text-right py-2">Precio</th>
                            <th class="text-right py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="py-2">
                                <div class="font-medium">{{ $item->product_name }}</div>
                                @if($item->product_model)
                                    <div class="text-xs text-gray-500">Modelo: {{ $item->product_model }}</div>
                                @endif
                            </td>
                            <td class="py-2 text-center">{{ $item->quantity }}</td>
                            <td class="py-2 text-right">₡{{ number_format($item->unit_price, 0) }}</td>
                            <td class="py-2 text-right font-medium">₡{{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Abonos --}}
            @if(strtolower($invoice->status) === 'apartado' || $invoice->abonos->count() > 0)
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Abonos</h3>

                @if($invoice->abonos->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-200 dark:border-white/10">
                            <th class="text-left py-2">Fecha</th>
                            <th class="text-right py-2">Monto</th>
                            <th class="text-left py-2">Nota</th>
                            <th class="text-right py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->abonos as $abono)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="py-2 text-xs">{{ $abono->date?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="py-2 text-right font-medium text-green-600">₡{{ number_format($abono->amount, 0) }}</td>
                            <td class="py-2 text-xs text-gray-500">{{ $abono->note ?? '-' }}</td>
                            <td class="py-2 text-right">
                                <button wire:click="deleteAbono({{ $abono->id }})" wire:confirm="¿Eliminar este abono?" class="text-red-500 hover:text-red-700 text-xs">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold">
                            <td class="pt-3 text-xs text-gray-500">Total abonado</td>
                            <td class="pt-3 text-right text-green-600">₡{{ number_format($invoice->abonos->sum('amount'), 0) }}</td>
                            <td colspan="2"></td>
                        </tr>
                        @php $saldo = $invoice->total - $invoice->abonos->sum('amount'); @endphp
                        @if($saldo > 0)
                        <tr class="font-bold">
                            <td class="text-xs text-red-500">Saldo pendiente</td>
                            <td class="text-right text-red-500">₡{{ number_format($saldo, 0) }}</td>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
                @else
                    <p class="text-sm text-gray-500">Sin abonos registrados.</p>
                @endif

                <div class="border-t border-gray-200 dark:border-white/10 pt-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Agregar abono</h4>
                    <div class="flex gap-2 items-end flex-wrap">
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Monto</label>
                            <input wire:model="newAbonoAmount" type="number" step="0.01" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm w-32" />
                            @error('newAbonoAmount') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Fecha</label>
                            <input wire:model="newAbonoDate" type="datetime-local" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Nota</label>
                            <input wire:model="newAbonoNote" type="text" placeholder="ej: primer abono" class="bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm w-48" />
                        </div>
                        <button wire:click="addAbono" class="px-4 py-2 text-sm bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold">Agregar</button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Columna derecha: montos y estados --}}
        <div class="space-y-6">
            {{-- Montos --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Montos</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Subtotal</label>
                        <input wire:model="subtotal" type="number" step="0.01" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Descuento</label>
                        <input wire:model="discount" type="number" step="0.01" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Envío (cobrado)</label>
                        <input wire:model="shipping" type="number" step="0.01" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Costo de envío</label>
                        <input wire:model="shipping_cost" type="number" step="0.01" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Total</label>
                        <input wire:model="total" type="number" step="0.01" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm font-bold" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Utilidad estimada</label>
                        <input wire:model="estimated_utility" type="number" step="0.01" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                </div>
            </div>

            {{-- Estados --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Estados</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Estado de factura</label>
                        <select wire:model="status" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm">
                            <option value="facturado">Facturado</option>
                            <option value="apartado">Apartado</option>
                            <option value="eliminado">Eliminado</option>
                            <option value="pending">Pendiente</option>
                            <option value="cancelled">Cancelada</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Estado de envío</label>
                        <select wire:model="shipping_status" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm">
                            <option value="pendiente">Pendiente</option>
                            <option value="creando">Creando</option>
                            <option value="entregado">Entregado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Envío / Entrega --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Entrega</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Fecha de entrega</label>
                        <input wire:model="delivery_date" type="date" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Hora inicio</label>
                            <input wire:model="delivery_time_start" type="text" placeholder="08:00" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Hora fin</label>
                            <input wire:model="delivery_time_end" type="text" placeholder="12:00" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Ubicación / Link</label>
                        <input wire:model="location" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Notas</label>
                        <textarea wire:model="notes" rows="3" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input wire:model="needs_bracelet_adjustment" type="checkbox" id="bracelet" class="rounded" />
                        <label for="bracelet" class="text-sm text-gray-600 dark:text-gray-400">Requiere ajuste de brazalete</label>
                    </div>
                </div>
            </div>

            {{-- Fechas de creación --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Fechas</h3>
                <div class="space-y-2 text-xs text-gray-500">
                    <div class="flex justify-between">
                        <span>Creado</span>
                        <span>{{ $invoice->created_at?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Actualizado</span>
                        <span>{{ $invoice->updated_at?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Fecha de creación (personalizada)</label>
                        <input wire:model="creation_date" type="date" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm mt-1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
