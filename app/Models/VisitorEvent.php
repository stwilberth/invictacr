<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorEvent extends Model
{
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'visitor_id',
        'session_id',
        'type',
        'url',
        'page_title',
        'product_id',
        'duration_seconds',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'duration_seconds' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getDurationHumanAttribute(): ?string
    {
        if ($this->duration_seconds === null) {
            return null;
        }

        $seconds = $this->duration_seconds;

        if ($seconds < 60) {
            return $seconds . 's';
        }

        $minutes = intdiv($seconds, 60);
        $remaining = $seconds % 60;

        if ($minutes < 60) {
            return $minutes . 'm ' . $remaining . 's';
        }

        $hours = intdiv($minutes, 60);
        $minutes = $minutes % 60;

        return $hours . 'h ' . $minutes . 'm';
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'page_view' => 'Página vista',
            'product_view' => 'Reloj visto',
            'search' => 'Búsqueda',
            'whatsapp_click' => 'Click WhatsApp',
            'add_to_cart' => 'Agregó al carrito',
            'cta_click' => 'Click CTA',
            default => $this->type,
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'page_view' => 'fa-file-lines text-gray-400',
            'product_view' => 'fa-clock text-[#00C4FF]',
            'search' => 'fa-magnifying-glass text-amber-500',
            'whatsapp_click' => 'fa-brands fa-whatsapp text-green-500',
            'add_to_cart' => 'fa-cart-shopping text-purple-500',
            'cta_click' => 'fa-bullseye text-[#00C4FF]',
            default => 'fa-circle text-gray-400',
        };
    }
}
