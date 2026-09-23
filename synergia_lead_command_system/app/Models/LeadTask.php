<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadTask extends Model
{
    protected $fillable = ['lead_id', 'assigned_to', 'title', 'description', 'status', 'priority', 'due_at', 'completed_at'];

    protected $casts = ['due_at' => 'datetime', 'completed_at' => 'datetime'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
