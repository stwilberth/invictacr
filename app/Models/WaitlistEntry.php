<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitlistEntry extends Model
{
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_NOTIFICADO = 'notificado';
    public const ESTADO_CONTACTADO = 'contactado';
    public const ESTADO_DESCARTADO = 'descartado';

    protected $fillable = [
        'nombre',
        'telefono',
        'modelo',
        'nota',
        'estado',
        'notified_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
    ];

    public function notifications()
    {
        return $this->hasMany(WaitlistNotification::class, 'waitlist_entry_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public static function normalizeModelo(?string $modelo): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9]/', '', trim((string) $modelo));
        return mb_strtoupper($clean ?? '');
    }

    public function setModeloAttribute($value): void
    {
        $this->attributes['modelo'] = self::normalizeModelo($value);
    }
}
