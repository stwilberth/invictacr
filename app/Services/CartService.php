<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected ?Cart $cart = null;

    public function getCart(): Cart
    {
        if ($this->cart) return $this->cart;

        $sessionId = Session::getId();

        if (auth()->check()) {
            $this->cart = Cart::where('user_id', auth()->id())->first();
            if ($this->cart && $this->cart->session_id !== $sessionId) {
                $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
                if ($sessionCart) {
                    $this->mergeCarts($sessionCart, $this->cart);
                    $sessionCart->delete();
                }
            }
            if (!$this->cart) {
                $this->cart = Cart::firstOrCreate(
                    ['user_id' => auth()->id()],
                    ['session_id' => $sessionId]
                );
            }
        } else {
            $this->cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['session_id' => $sessionId]
            );
        }

        return $this->cart;
    }

    public function addItem(int $productId, int $quantity = 1): CartItem
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);

        if ($product->stock <= 0) {
            throw new \Exception('Este producto no está disponible.');
        }

        $existingItem = $cart->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            $newQty = $existingItem->quantity + $quantity;
            if ($newQty > $product->stock) {
                throw new \Exception("Solo hay {$product->stock} unidades disponibles.");
            }
            $existingItem->update(['quantity' => $newQty]);
            return $existingItem;
        }

        if ($quantity > $product->stock) {
            throw new \Exception("Solo hay {$product->stock} unidades disponibles.");
        }

        return $cart->items()->create([
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($cartItemId);

        if ($quantity <= 0) {
            $item->delete();
            return;
        }

        if ($quantity > $item->product->stock) {
            throw new \Exception("Solo hay {$item->product->stock} unidades disponibles.");
        }

        $item->update(['quantity' => $quantity]);
    }

    public function removeItem(int $cartItemId): void
    {
        $cart = $this->getCart();
        $cart->items()->where('id', $cartItemId)->delete();
    }

    public function clear(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }

    public function getItemCount(): int
    {
        return $this->getCart()->item_count;
    }

    public function mergeCarts(Cart $from, Cart $to): void
    {
        foreach ($from->items as $item) {
            $existing = $to->items()->where('product_id', $item->product_id)->first();
            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $item->quantity]);
            } else {
                $item->update(['cart_id' => $to->id]);
            }
        }
    }
}
