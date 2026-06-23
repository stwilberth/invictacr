<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingTask extends Model
{
    protected $fillable = ['title', 'description', 'status', 'type', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];
}
