<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    protected $fillable = [
        'invoice_id', 'amount', 'date', 'note', 'comprobante_path'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime',
    ];

    /**
     * URL pública del comprobante (bucket R2 vía CDN).
     */
    public function getComprobanteUrlAttribute(): ?string
    {
        if (!$this->comprobante_path) {
            return null;
        }

        if (str_starts_with($this->comprobante_path, 'http')) {
            return $this->comprobante_path;
        }

        // En BD se guarda "/storage/..." pero la llave R2/CDN es sin ese prefijo
        $key = preg_replace('#^/?storage/#', '', $this->comprobante_path);

        return 'https://cdn.invictacostarica.com/' . $key;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
