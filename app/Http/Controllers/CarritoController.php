<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function show()
    {
        $cart = $this->cartService->getCart()->load('items.product.images');
        return view('pages.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->addItem(
                $request->product_id,
                $request->integer('quantity', 1)
            );

            if ($request->expectsJson()) {
                $cart = $this->cartService->getCart();
                return response()->json([
                    'success' => true,
                    'message' => 'Producto agregado al carrito.',
                    'cart_count' => $cart->item_count,
                ]);
            }

            return back()->with('success', 'Producto agregado al carrito.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $this->cartService->updateQuantity($itemId, $request->integer('quantity'));

            if ($request->expectsJson()) {
                $cart = $this->cartService->getCart();
                return response()->json([
                    'success' => true,
                    'message' => 'Carrito actualizado.',
                    'cart_count' => $cart->item_count,
                    'cart_total' => number_format($cart->total, 0),
                ]);
            }

            return back()->with('success', 'Carrito actualizado.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request, int $itemId)
    {
        $this->cartService->removeItem($itemId);

        if ($request->expectsJson()) {
            $cart = $this->cartService->getCart();
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del carrito.',
                'cart_count' => $cart->item_count,
                'cart_total' => number_format($cart->total, 0),
            ]);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear(Request $request)
    {
        $this->cartService->clear();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrito vaciado.',
                'cart_count' => 0,
            ]);
        }

        return back()->with('success', 'Carrito vaciado.');
    }
}
