<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $fillable = ['type', 'status', 'message', 'details'];

    protected $casts = [
        'details' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(SyncLogItem::class);
    }
}
