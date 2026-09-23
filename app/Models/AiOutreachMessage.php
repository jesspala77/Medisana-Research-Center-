<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiOutreachMessage extends Model
{
    protected $fillable = [
        'lead_id',
        'agency_name',
        'recipient_name',
        'recipient_email',
        'subject',
        'message_body',
        'status',
        'approved_at',
        'sent_at',
        'replied_at',
        'opted_out_at',
        'metadata',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'sent_at' => 'datetime',
        'replied_at' => 'datetime',
        'opted_out_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function events()
    {
        return $this->hasMany(OutreachEvent::class);
    }

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function markSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markReplied(): void
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now(),
        ]);
    }

    public function markOptedOut(): void
    {
        $this->update([
            'status' => 'opted_out',
            'opted_out_at' => now(),
        ]);
    }
}
