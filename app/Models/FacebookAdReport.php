<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookAdReport extends Model
{
    protected $fillable = [
        'report_date',
        'ad_account_id',
        'campaign_name',
        'campaign_id',
        'is_active',
        'impressions',
        'clicks',
        'spend',
        'reach',
        'frequency',
        'cpm',
        'cpc',
        'ctr',
        'raw_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'is_active' => 'boolean',
        'raw_data' => 'json',
    ];
}
