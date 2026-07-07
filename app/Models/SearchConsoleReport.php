<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchConsoleReport extends Model
{
    protected $fillable = [
        'report_date',
        'query',
        'page',
        'country',
        'device',
        'clicks',
        'impressions',
        'ctr',
        'position',
        'raw_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'raw_data' => 'json',
    ];
}
