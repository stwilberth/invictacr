<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    public const ESTADO_NUEVO = 'nuevo';
    public const ESTADO_CONTACTADO = 'contactado';
    public const ESTADO_COMPRADO = 'comprado';
    public const ESTADO_DESCARTADO = 'descartado';

    public const SOURCE_MANUAL = 'manual';
    public const SOURCE_AUTO = 'auto_click';

    protected $fillable = [
        'name',
        'phone',
        'phone_norm',
        'modelo_interes',
        'nota',
        'estado',
        'source',
        'visitor_id',
        'invoice_id',
        'contacted_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Lead $lead) {
            $lead->phone_norm = static::normalizePhone($lead->phone);
        });
    }

    /**
     * Normaliza un teléfono CR: solo dígitos, últimos 8
     * (cubre +506, espacios, guiones).
     */
    public static function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '' || $digits === null) {
            return null;
        }

        return substr($digits, -8);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Busca la factura de compra: por visitor_id o por teléfono
     * (sufijo, para cubrir +506/prefijos). Ignora canceladas.
     */
    public static function findPurchase(?string $phoneNorm, ?int $visitorId): ?Invoice
    {
        if (!$phoneNorm && !$visitorId) {
            return null;
        }

        return Invoice::where('status', '!=', 'cancelled')
            ->where(function ($q) use ($phoneNorm, $visitorId) {
                if ($visitorId) {
                    $q->orWhere('visitor_id', $visitorId);
                }
                if ($phoneNorm) {
                    $q->orWhere('client_phone', 'like', '%' . $phoneNorm);
                }
            })
            ->latest()
            ->first();
    }

    public function purchase(): ?Invoice
    {
        if ($this->invoice_id && $this->relationLoaded('invoice')) {
            return $this->invoice;
        }

        if ($this->invoice_id) {
            return $this->invoice;
        }

        return static::findPurchase($this->phone_norm, $this->visitor_id);
    }

    /**
     * ¿Lleva días sin seguimiento y sin compra? Nuevo >1 día,
     * contactado >3 días.
     */
    public function getNeedsFollowUpAttribute(): bool
    {
        if (in_array($this->estado, [self::ESTADO_COMPRADO, self::ESTADO_DESCARTADO], true)) {
            return false;
        }

        $ref = $this->estado === self::ESTADO_CONTACTADO
            ? ($this->contacted_at ?? $this->updated_at)
            : $this->created_at;

        if (!$ref) {
            return true;
        }

        $days = $this->estado === self::ESTADO_CONTACTADO ? 3 : 1;

        return $ref->diffInDays(now()) >= $days;
    }

    public function scopePorRetomar($query)
    {
        return $query->whereIn('estado', [self::ESTADO_NUEVO, self::ESTADO_CONTACTADO]);
    }
}
