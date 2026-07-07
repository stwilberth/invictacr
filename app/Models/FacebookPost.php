<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    protected $fillable = [
        'post_id',
        'message',
        'link',
        'media_type',
        'posted_at',
        'likes',
        'comments',
        'shares',
        'reach',
        'impressions',
        'raw_data',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'raw_data' => 'json',
    ];
}
