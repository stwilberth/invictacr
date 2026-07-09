<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'client_id', 'client_name', 'client_email',
        'client_phone', 'customer_address', 'subtotal', 'discount',
        'shipping', 'shipping_cost', 'total', 'status', 'shipping_status',
        'notes', 'issued_at', 'delivery_date', 'delivery_time_start',
        'delivery_time_end', 'location', 'needs_bracelet_adjustment',
        'creation_date', 'estimated_utility', 'cedula',
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
}
