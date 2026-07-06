<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchLog extends Model
{
    protected $fillable = [
        'query',
        'parsed_filters',
        'used_ai',
        'ai_response',
        'ai_raw_response',
        'user_id',
        'ip_address',
        'real_ip',
        'user_agent',
        'device_type',
        'results_count',
    ];

    protected $casts = [
        'parsed_filters' => 'array',
        'used_ai' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
