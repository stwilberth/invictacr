<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.invoices') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">&larr; Volver a facturas</a>
            <h2 class="text-xl font-black mt-1">Crear Factura</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.invoices') }}" class="px-4 py-2 text-sm border border-gray-200 dark:border-white/10 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5">Cancelar</a>
            <button wire:click="save" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">Guardar Factura</button>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Columna izquierda: info del cliente y productos --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Información del cliente --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Cliente</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <label class="text-xs text-gray-500 block mb-1">Nombre *</label>
                        <input wire:model.live.debounce.300ms="client_name" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="Nombre del cliente" @focus="open = true" @keydown.escape="open = false" />
                        @error('client_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if(strlen($clientSearch) >= 2 && count($clientResults) > 0)
                        <div x-show="open" class="absolute top-full left-0 right-0 mt-1 z-50 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                            @foreach($clientResults as $client)
                            <button type="button" wire:click="selectClient({{ $client->id }})" @click="open = false" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-white/5 border-b border-gray-100 dark:border-white/5 last:border-0">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $client->name }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($client->phone) {{ $client->phone }} @endif
                                    @if($client->email) · {{ $client->email }} @endif
                                </div>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Email</label>
                        <input wire:model="client_email" type="email" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="correo@ejemplo.com" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Teléfono</label>
                        <input wire:model="client_phone" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="8888-8888" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Cédula</label>
                        <input wire:model="cedula" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="Número de cédula" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs text-gray-500 block mb-1">Dirección</label>
                        <input wire:model="customer_address" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="Dirección de entrega" />
                    </div>
                </div>
            </div>

            {{-- Agregar producto --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Agregar Producto</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    <div class="md:col-span-2">
                        <label class="text-xs text-gray-500 block mb-1">Nombre *</label>
                        <input wire:model="newItemName" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="Nombre del producto" />
                    </div>
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <label class="text-xs text-gray-500 block mb-1">Modelo</label>
                        <input wire:model.live.debounce.300ms="newItemModel" type="text" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="Modelo" @focus="open = true" @keydown.escape="open = false" />
                        @if(strlen($productSearch) >= 1 && count($productResults) > 0)
                        <div x-show="open" class="absolute top-full left-0 right-0 mt-1 z-50 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-white/10 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                            @foreach($productResults as $product)
                            <button type="button" wire:click="selectProduct({{ $product->id }})" @click="open = false" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-white/5 border-b border-gray-100 dark:border-white/5 last:border-0">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $product->title }}</div>
                                <div class="text-xs text-gray-500">
                                    Modelo: {{ $product->modelo }} · ₡{{ number_format($product->precio_venta, 0) }}
                                </div>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Cantidad</label>
                        <input wire:model="newItemQuantity" type="number" min="1" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Precio (₡) *</label>
                        <input wire:model="newItemPrice" type="number" step="0.01" min="0" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="0" />
                    </div>
                    <div class="md:col-span-2">
                        <button wire:click="addItem" class="w-full px-4 py-2 text-sm bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold">Agregar producto</button>
                    </div>
                </div>
            </div>

            {{-- Productos agregados --}}
            @if(count($items) > 0)
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Productos en la factura</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-200 dark:border-white/10">
                            <th class="text-left py-2">Producto</th>
                            <th class="text-center py-2">Cant</th>
                            <th class="text-right py-2">Precio</th>
                            <th class="text-right py-2">Subtotal</th>
                            <th class="text-right py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="py-2">
                                <div class="font-medium">{{ $item['product_name'] }}</div>
                                @if($item['product_model'])
                                    <div class="text-xs text-gray-500">Modelo: {{ $item['product_model'] }}</div>
                                @endif
                            </td>
                            <td class="py-2 text-center">{{ $item['quantity'] }}</td>
                            <td class="py-2 text-right">₡{{ number_format($item['unit_price'], 0) }}</td>
                            <td class="py-2 text-right font-medium">₡{{ number_format($item['subtotal'], 0) }}</td>
                            <td class="py-2 text-right">
                                <button wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 text-xs">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        <input wire:model="subtotal" type="number" step="0.01" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900" readonly />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Descuento (₡)</label>
                        <input wire:model.live="discount" type="number" step="0.01" min="0" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Envío cobrado (₡)</label>
                        <input wire:model.live="shipping" type="number" step="0.01" min="0" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Costo de envío (₡)</label>
                        <input wire:model="shipping_cost" type="number" step="0.01" min="0" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <hr class="dark:border-white/10" />
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Total</label>
                        <div class="text-lg font-black text-blue-600 dark:text-blue-400">₡{{ number_format($total, 0) }}</div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Utilidad estimada (₡)</label>
                        <input wire:model="estimated_utility" type="number" step="0.01" min="0" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
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
                            <option value="pending">Pendiente</option>
                            <option value="facturado">Facturado</option>
                            <option value="apartado">Apartado</option>
                            <option value="eliminado">Eliminado</option>
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

            {{-- Notas --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Notas</h3>
                <textarea wire:model="notes" rows="3" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" placeholder="Notas adicionales..."></textarea>
            </div>

            {{-- Fecha --}}
            <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Fecha</h3>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Fecha de creación (personalizada)</label>
                    <input wire:model="creation_date" type="date" class="w-full bg-white dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-3 py-2 text-sm" />
                </div>
            </div>
        </div>
    </div>

    {{-- Botón guardar inferior --}}
    <div class="mt-6 flex justify-end">
        <button wire:click="save" class="px-6 py-3 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold">Guardar Factura</button>
    </div>
</div>
