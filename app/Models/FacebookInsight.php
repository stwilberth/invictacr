<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookInsight extends Model
{
    protected $table = 'facebook_insights';

    protected $fillable = [
        'report_date',
        'page_id',
        'page_name',
        'page_impressions',
        'page_engaged_users',
        'page_follows',
        'page_reactions',
        'page_comments',
        'page_shares',
        'page_views',
        'raw_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'raw_data' => 'json',
    ];
}
