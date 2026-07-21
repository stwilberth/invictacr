<x-app-layout title="Carrito de Compras - Invicta Costa Rica">
    <section class="bg-white dark:bg-[#0a0f1c] pt-8 pb-16 md:pt-12 md:pb-24">
        <div class="max-w-7xl mx-auto px-4" x-data="cartPage()" x-init="init()">
            <x-page-title title="Mi Carrito" highlight="Carrito" subtitle="Revisa los productos que seleccionaste antes de finalizar tu compra." />

            @if(session('success'))
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-sm font-bold text-emerald-700 dark:text-emerald-400 text-center">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold text-red-700 dark:text-red-400 text-center">
                {{ session('error') }}
            </div>
            @endif

            @if($cart->items->isEmpty())
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-cart-shopping text-3xl text-gray-300 dark:text-gray-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tu carrito está vacío</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">Agregá relojes increíbles a tu carrito desde nuestro catálogo.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-eye"></i> Ver Catálogo
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Items --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart->items as $item)
                    @if($item->product)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-5 md:p-6"
                         x-data="{ qty: {{ $item->quantity }} }">
                        @php
                            $model = preg_replace('/^invicta-/i', '', $item->product->modelo ?? '');
                            $thumbUrl = null;
                            if ($item->product->imagen && str_starts_with($item->product->imagen, '/storage/relojes/')) {
                                $thumbModelo = pathinfo(basename($item->product->imagen), PATHINFO_FILENAME);
                                if (file_exists(public_path("storage/relojes/thumbs/{$thumbModelo}.webp"))) {
                                    $thumbUrl = "/storage/relojes/thumbs/{$thumbModelo}.webp";
                                }
                            } elseif (file_exists(public_path("storage/relojes/thumbs/{$model}.webp"))) {
                                $thumbUrl = "/storage/relojes/thumbs/{$model}.webp";
                            }
                        @endphp
                        <div class="flex gap-4 md:gap-5">
                            <div class="w-20 h-20 md:w-24 md:h-24 flex-shrink-0 bg-gray-50 dark:bg-white/5 rounded-xl overflow-hidden flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                @if($thumbUrl)
                                    <img src="{{ $thumbUrl }}" alt="{{ $item->product->title }}" class="w-full h-full object-contain p-1" />
                                @else
                                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500">{{ $model }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-[#00C4FF] transition-colors line-clamp-2">
                                        Invicta {{ $item->product->coleccion ? $item->product->coleccion . ' ' : '' }}{{ $item->product->modelo }}
                                    </a>
                                    <button type="button" @click="removeItem({{ $item->id }})" class="text-gray-300 hover:text-red-500 dark:text-gray-600 dark:hover:text-red-400 transition-colors p-1 shrink-0">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                @if($item->product->color)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item->product->color }}</p>
                                @endif
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-0 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                        <button type="button" @click="updateQty({{ $item->id }}, qty - 1)"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors text-xs">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <span class="w-10 h-8 flex items-center justify-center text-sm font-bold text-gray-900 dark:text-white border-x border-gray-200 dark:border-gray-600" x-text="qty"></span>
                                        <button type="button" @click="updateQty({{ $item->id }}, qty + 1)"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors text-xs"
                                                @if($item->product->stock <= $item->quantity) disabled @endif>
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    <span class="text-base font-black text-red-600 dark:text-red-400">₡{{ number_format($item->line_total, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    <div class="flex justify-end pt-2">
                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('¿Vaciar el carrito?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 transition-colors">
                                <i class="fa-solid fa-trash-can mr-1"></i> Vaciar carrito
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Resumen --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8 sticky top-20">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Resumen</h2>
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>Productos (<span x-text="totalItems"></span>)</span>
                                <span class="font-bold text-gray-700 dark:text-gray-200">₡{{ number_format($cart->subtotal, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>Envío</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">Gratis</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-bold text-gray-900 dark:text-white">Total</span>
                                <span class="text-xl font-black text-red-600 dark:text-red-400">₡{{ number_format($cart->total, 0) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('checkout') }}" class="block w-full text-center py-3.5 bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-bold text-sm uppercase tracking-tight transition-all hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-lock mr-2"></i> Proceder al Checkout
                        </a>
                        <a href="{{ route('products.index') }}" class="block w-full text-center py-3 mt-3 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-[#00C4FF] hover:border-[#00C4FF] rounded-xl font-bold text-xs uppercase tracking-tight transition-all">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Seguir Comprando
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

@push('scripts')
<script>
function cartPage() {
    return {
        totalItems: {{ $cart->item_count }},
        init() {},
        async updateQty(itemId, newQty) {
            if (newQty < 0) return;
            try {
                const res = await fetch(`/carrito/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quantity: newQty }),
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al actualizar');
                }
            } catch (e) {
                console.error(e);
            }
        },
        async removeItem(itemId) {
            if (!confirm('¿Eliminar este producto del carrito?')) return;
            try {
                const res = await fetch(`/carrito/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                }
            } catch (e) {
                console.error(e);
            }
        },
    };
}
</script>
@endpush

</x-app-layout>
