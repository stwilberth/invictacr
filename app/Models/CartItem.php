<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getLineTotalAttribute(): float
    {
        $product = $this->product;
        if (!$product) return 0;

        $price = $product->descuento > 0
            ? $product->precio_venta * (1 - $product->descuento / 100)
            : $product->precio_venta;

        return $price * $this->quantity;
    }

    public function getUnitPriceAttribute(): float
    {
        $product = $this->product;
        if (!$product) return 0;

        return $product->descuento > 0
            ? $product->precio_venta * (1 - $product->descuento / 100)
            : $product->precio_venta;
    }
}
