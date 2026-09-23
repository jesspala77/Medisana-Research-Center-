<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadActivity extends Model
{
    protected $fillable = ['lead_id', 'user_id', 'type', 'title', 'description', 'old_value', 'new_value', 'metadata'];

    protected $casts = ['metadata' => 'array'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
