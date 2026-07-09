<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    protected $fillable = [
        'invoice_id', 'amount', 'date', 'note'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
