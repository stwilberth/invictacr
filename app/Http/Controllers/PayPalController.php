<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    protected function getAccessToken(): string
    {
        $response = Http::withBasicAuth(
            config('paypal.client_id'),
            config('paypal.client_secret')
        )->asForm()->post(config('paypal.base_url') . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            Log::error('PayPal token error', ['response' => $response->body()]);
            throw new \Exception('Error al conectar con PayPal.');
        }

        return $response->json('access_token');
    }

    public function create()
    {
        $cart = $this->cartService->getCart()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Tu carrito está vacío.');
        }

        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('checkout')->with('error', 'Datos de checkout no encontrados.');
        }

        $subtotal = 0;
        foreach ($cart->items as $item) {
            $product = $item->product;
            $price = $product->descuento > 0
                ? $product->precio_venta * (1 - $product->descuento / 100)
                : $product->precio_venta;
            $subtotal += $price * $item->quantity;
        }

        $exchangeRate = $this->getExchangeRate();
        $totalUSD = round(($subtotal / $exchangeRate), 2);

        try {
            $accessToken = $this->getAccessToken();

            $items = [];
            foreach ($cart->items as $item) {
                $product = $item->product;
                $price = $product->descuento > 0
                    ? $product->precio_venta * (1 - $product->descuento / 100)
                    : $product->precio_venta;
                $priceUSD = round(($price / $exchangeRate), 2);

                $items[] = [
                    'name' => 'Invicta ' . $product->modelo,
                    'description' => $product->title,
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($priceUSD, 2, '.', ''),
                    ],
                    'quantity' => $item->quantity,
                    'category' => 'PHYSICAL_GOODS',
                ];
            }

            $response = Http::withToken($accessToken)
                ->post(config('paypal.base_url') . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => 'INV-' . now()->timestamp,
                            'description' => 'Compra en Invicta Costa Rica',
                            'custom_id' => auth()->id() ?? 'guest',
                            'amount' => [
                                'currency_code' => 'USD',
                                'value' => number_format($totalUSD, 2, '.', ''),
                                'breakdown' => [
                                    'item_total' => [
                                        'currency_code' => 'USD',
                                        'value' => number_format($totalUSD, 2, '.', ''),
                                    ],
                                ],
                            ],
                            'items' => $items,
                        ],
                    ],
                    'application_context' => [
                        'brand_name' => config('paypal.brand_name'),
                        'landing_page' => 'BILLING',
                        'user_action' => 'PAY_NOW',
                        'return_url' => route('paypal.execute'),
                        'cancel_url' => route('paypal.cancel'),
                        'locale' => 'es-CR',
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('PayPal create order error', ['response' => $response->body()]);
                return redirect()->route('checkout')->with('error', 'Error al crear la orden en PayPal. Intenta de nuevo.');
            }

            $orderData = $response->json();
            $approvalUrl = collect($orderData['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

            if (!$approvalUrl) {
                return redirect()->route('checkout')->with('error', 'Error al obtener enlace de pago de PayPal.');
            }

            session(['paypal_order_id' => $orderData['id']]);
            session(['paypal_total_usd' => $totalUSD]);
            session(['paypal_exchange_rate' => $exchangeRate]);

            return redirect($approvalUrl);

        } catch (\Exception $e) {
            Log::error('PayPal create error', ['message' => $e->getMessage()]);
            return redirect()->route('checkout')->with('error', 'Error al procesar el pago con PayPal. Intenta de nuevo.');
        }
    }

    public function execute(Request $request)
    {
        $paypalOrderId = session('paypal_order_id');
        $checkoutData = session('checkout_data');

        if (!$paypalOrderId || !$checkoutData) {
            return redirect()->route('cart.show')->with('error', 'Sesión de pago expirada.');
        }

        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->withBody('{}', 'application/json')
                ->post(config('paypal.base_url') . "/v2/checkout/orders/{$paypalOrderId}/capture");

            if (!$response->successful()) {
                Log::error('PayPal capture error', ['response' => $response->body()]);
                return redirect()->route('checkout')->with('error', 'Error al capturar el pago.');
            }

            $captureData = $response->json();
            $transactionId = $captureData['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

            $cart = $this->cartService->getCart()->load('items.product');

            $invoice = $this->createPayPalInvoice(
                $checkoutData,
                $cart,
                $transactionId
            );

            $this->cartService->clear();

            session()->forget(['checkout_data', 'paypal_order_id', 'paypal_total_usd', 'paypal_exchange_rate']);

            return redirect()->route('order.confirmation', $invoice->id)
                ->with('payment_method', 'paypal');

        } catch (\Exception $e) {
            Log::error('PayPal execute error', ['message' => $e->getMessage()]);
            return redirect()->route('cart.show')->with('error', 'Error al procesar el pago.');
        }
    }

    public function cancel()
    {
        session()->forget(['checkout_data', 'paypal_order_id', 'paypal_total_usd', 'paypal_exchange_rate']);
        return redirect()->route('checkout')->with('error', 'Pago cancelado. Puedes elegir otro método de pago.');
    }

    public function webhook(Request $request)
    {
        Log::info('PayPal webhook received', ['headers' => $request->headers->all(), 'body' => $request->all()]);
        return response()->json(['status' => 'ok']);
    }

    protected function getExchangeRate(): float
    {
        try {
            $response = Http::timeout(5)->get('https://api.exchangerate-api.com/v4/latest/USD');
            if ($response->successful()) {
                return $response->json('rates.CRC', 520);
            }
        } catch (\Exception $e) {
            Log::warning('Exchange rate API failed, using default');
        }
        return 520;
    }

    protected function createPayPalInvoice(array $data, $cart, ?string $transactionId): Invoice
    {
        return DB::transaction(function () use ($data, $cart, $transactionId) {
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
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'address' => "{$data['address']}, {$data['canton']}, {$data['province']}",
                ]
            );

            $invoiceNumber = Invoice::generateUniqueNumber();

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $client->id,
                'client_name' => $data['name'],
                'client_email' => $data['email'],
                'client_phone' => $data['phone'],
                'customer_address' => "{$data['address']}, {$data['canton']}, {$data['province']}",
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'facturado',
                'payment_method' => 'paypal',
                'paypal_transaction_id' => $transactionId,
                'source' => 'web',
                'notes' => $data['notes'] ?? null,
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
