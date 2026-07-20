<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiTimelineInsight extends Model
{
    protected $fillable = [
        'period_key',
        'period_label',
        'conclusion',
        'advice',
        'is_final',
        'raw_data',
        'generated_at',
    ];

    protected $casts = [
        'is_final' => 'boolean',
        'raw_data' => 'json',
        'generated_at' => 'datetime',
    ];
}
