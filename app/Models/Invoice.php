<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'client_id', 'client_name', 'client_email',
        'client_phone', 'customer_address', 'subtotal', 'discount',
        'shipping', 'shipping_cost', 'total', 'status', 'shipping_status',
        'payment_method', 'paypal_transaction_id', 'source',
        'notes', 'issued_at', 'delivery_date', 'delivery_time_start',
        'delivery_time_end', 'location', 'needs_bracelet_adjustment',
        'creation_date', 'estimated_utility', 'cedula', 'created_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'estimated_utility' => 'decimal:2',
        'issued_at' => 'datetime',
        'delivery_date' => 'date',
        'creation_date' => 'date',
        'needs_bracelet_adjustment' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class);
    }

    /**
     * Generate a unique invoice number with collision protection.
     */
    public static function generateUniqueNumber(): string
    {
        $attempts = 0;

        do {
            // Empieza en 1000 para que se vea profesional, más la cantidad actual
            $count = static::count() + 1000 + $attempts;
            $invoiceNumber = 'INV-' . $count;
            $exists = static::where('invoice_number', $invoiceNumber)->exists();
            $attempts++;
        } while ($exists && $attempts < 100);

        return $invoiceNumber;
    }
}
