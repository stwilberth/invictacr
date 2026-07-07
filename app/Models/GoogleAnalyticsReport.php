<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAnalyticsReport extends Model
{
    protected $fillable = [
        'report_date',
        'users',
        'sessions',
        'pageviews',
        'bounce_rate',
        'avg_session_duration',
        'new_users',
        'top_pages',
        'traffic_sources',
        'device_breakdown',
        'raw_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'bounce_rate' => 'decimal:2',
        'avg_session_duration' => 'decimal:2',
        'raw_data' => 'json',
    ];
}
