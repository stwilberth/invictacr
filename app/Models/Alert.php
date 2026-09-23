<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'level',
        'context',
        'resolved_at',
        'notified_at',
    ];

    protected $casts = [
        'context' => 'json',
        'resolved_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function isActive(): bool
    {
        return $this->resolved_at === null;
    }

    public function resolve(): void
    {
        $this->update(['resolved_at' => now()]);
    }
}