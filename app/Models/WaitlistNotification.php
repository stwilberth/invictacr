<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitlistNotification extends Model
{
    protected $fillable = [
        'waitlist_entry_id',
        'modelo',
        'titulo',
        'mensaje',
        'leida_at',
    ];

    protected $casts = [
        'leida_at' => 'datetime',
    ];

    public function entry()
    {
        return $this->belongsTo(WaitlistEntry::class, 'waitlist_entry_id');
    }

    public function scopeNoLeidas($query)
    {
        return $query->whereNull('leida_at');
    }
}
