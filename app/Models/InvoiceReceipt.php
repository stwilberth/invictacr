<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceReceipt extends Model
{
    protected $fillable = [
        'invoice_id', 'path', 'original_name', 'mime', 'size',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * URL pública del comprobante (bucket R2 vía CDN).
     */
    public function getUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }

        // En BD se guarda "/storage/..." pero la llave R2/CDN es sin ese prefijo
        $key = preg_replace('#^/?storage/#', '', $this->path);

        return 'https://cdn.invictacostarica.com/' . $key;
    }

    public function getIsImageAttribute(): bool
    {
        return $this->mime ? str_starts_with($this->mime, 'image/') : (bool) preg_match('/\.(jpe?g|png|gif|webp|bmp)$/i', $this->path ?? '');
    }
}
