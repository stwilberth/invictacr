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
        'campaign_objective',
        'campaign_status',
        'adset_name',
        'adset_id',
        'ad_name',
        'ad_id',
        'is_active',
        'impressions',
        'clicks',
        'unique_clicks',
        'inline_link_clicks',
        'inline_post_engagement',
        'spend',
        'reach',
        'frequency',
        'cpm',
        'cpc',
        'cpp',
        'ctr',
        'conversions',
        'conversion_value',
        'roas',
        'cost_per_conversion',
        'level',
        'date_start',
        'date_stop',
        'raw_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'is_active' => 'boolean',
        'conversion_value' => 'decimal:2',
        'roas' => 'decimal:2',
        'cost_per_conversion' => 'decimal:2',
        'raw_data' => 'json',
    ];
}