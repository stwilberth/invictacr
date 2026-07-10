<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLogItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['sync_log_id', 'type', 'modelo', 'product_id', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function syncLog()
    {
        $this->belongsTo(SyncLog::class);
    }

    public function product()
    {
        $this->belongsTo(Product::class);
    }
}
