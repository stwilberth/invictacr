<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalFactor extends Model
{
    protected $fillable = [
        'event_date',
        'category',
        'title',
        'description',
        'source',
        'impact_level',
        'active',
        'metadata',
    ];

    protected $casts = [
        'event_date' => 'date',
        'active' => 'boolean',
        'metadata' => 'json',
    ];
}
