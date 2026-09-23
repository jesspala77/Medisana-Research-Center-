<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutreachEvent extends Model
{
    protected $fillable = [
        'lead_id',
        'ai_outreach_message_id',
        'event_type',
        'provider',
        'provider_message_id',
        'ip_address',
        'user_agent',
        'url_clicked',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function message()
    {
        return $this->belongsTo(AiOutreachMessage::class, 'ai_outreach_message_id');
    }
}