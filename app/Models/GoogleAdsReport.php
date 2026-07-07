<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAdsReport extends Model
{
    protected $fillable = [
        'report_date',
        'campaign_name',
        'campaign_id',
        'impressions',
        'clicks',
        'cost',
        'conversions',
        'conversion_value',
        'ctr',
        'average_cpc',
        'raw_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'raw_data' => 'json',
    ];
}
