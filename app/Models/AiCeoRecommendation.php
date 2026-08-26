<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiCeoRecommendation extends Model
{
    protected $fillable = [
        'batch_key',
        'category',
        'area',
        'priority',
        'title',
        'rationale',
        'action',
        'status',
        'raw_data',
        'generated_at',
        'resolved_at',
    ];

    protected $casts = [
        'raw_data' => 'json',
        'generated_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function scopeLatestBatch($query)
    {
        $latestKey = static::query()->max('batch_key');

        return $query->where('batch_key', $latestKey);
    }
}
