<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function show()
    {
        $cart = $this->cartService->getCart()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Tu carrito está vacío.');
        }

        foreach ($cart->items as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                return redirect()->route('cart.show')->with('error', 'Uno de los productos en tu carrito ya no está disponible.');
            }
        }

        return view('pages.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'province' => 'required|string|max:100',
            'canton' => 'required|string|max:100',
            'payment_method' => 'required|in:paypal,sinpe,transferencia,contra_entrega',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = $this->cartService->getCart()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Tu carrito está vacío.');
        }

        foreach ($cart->items as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                $name = $item->product?->title ?? 'un producto';
                return redirect()->route('cart.show')->with('error', "Stock insuficiente para {$name}.");
            }
        }

        if ($request->payment_method === 'contra_entrega') {
            $address = strtolower($request->address . ' ' . $request->canton . ' ' . $request->province);
            $gamProvinces = ['san jos', 'cartago', 'heredia', 'alajuela'];
            $isGam = false;
            foreach ($gamProvinces as $p) {
                if (str_contains($address, $p)) {
                    $isGam = true;
                    break;
                }
            }
            if (!$isGam) {
                return back()->withInput()->with('error', 'El pago contra entrega solo está disponible en la Gran Área Metropolitana (GAM).');
            }
        }

        if ($request->payment_method === 'paypal') {
            $request->session()->put('checkout_data', $request->only([
                'name', 'email', 'phone', 'address', 'province', 'canton', 'notes', 'payment_method',
            ]));
            return redirect()->route('paypal.create');
        }

        $invoice = $this->createInvoice($request, $cart, $request->payment_method);

        $this->cartService->clear();

        return redirect()->route('order.confirmation', $invoice->id)
            ->with('payment_method', $request->payment_method);
    }

    public function confirmation(Invoice $invoice)
    {
        $invoice->load('items.product');
        $paymentMethod = session('payment_method') ?? $invoice->payment_method;

        return view('pages.order-confirmation', compact('invoice', 'paymentMethod'));
    }

    protected function createInvoice(Request $request, $cart, string $paymentMethod, ?string $paypalTransactionId = null): Invoice
    {
        return DB::transaction(function () use ($request, $cart, $paymentMethod, $paypalTransactionId) {
            $subtotal = 0;
            $discount = 0;

            foreach ($cart->items as $item) {
                $product = $item->product;
                $lineSubtotal = $product->precio_venta * $item->quantity;
                $lineDiscount = $product->descuento > 0
                    ? $lineSubtotal * ($product->descuento / 100)
                    : 0;
                $subtotal += $lineSubtotal;
                $discount += $lineDiscount;
            }

            $total = $subtotal - $discount;

            $client = Client::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => "{$request->address}, {$request->canton}, {$request->province}",
                ]
            );

            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad(
                Invoice::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $status = $paymentMethod === 'paypal' ? 'completed' : 'pending';

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $client->id,
                'client_name' => $request->name,
                'client_email' => $request->email,
                'client_phone' => $request->phone,
                'customer_address' => "{$request->address}, {$request->canton}, {$request->province}",
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => $status,
                'payment_method' => $paymentMethod,
                'paypal_transaction_id' => $paypalTransactionId,
                'source' => 'web',
                'notes' => $request->notes,
                'issued_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $product = $item->product;
                $lineSubtotal = $product->precio_venta * $item->quantity;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'product_model' => $product->modelo,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->precio_venta,
                    'subtotal' => $lineSubtotal,
                ]);

                $newStock = $product->stock - $item->quantity;
                $product->update([
                    'stock' => $newStock,
                    'disponibilidad' => $newStock <= 0 ? 'agotado' : 'disponible',
                ]);
            }

            return $invoice;
        });
    }
}
