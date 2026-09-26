<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappChatImport extends Model
{
    protected $fillable = [
        'source_name',
        'content_hash',
        'contact_name',
        'phone',
        'transcript',
        'message_timestamps',
        'message_count',
        'first_message_at',
        'last_message_at',
        'visitor_id',
        'lead_id',
        'imported_by',
    ];

    protected $casts = [
        'message_timestamps' => 'array',
        'first_message_at' => 'datetime',
        'last_message_at' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function importer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }
}
