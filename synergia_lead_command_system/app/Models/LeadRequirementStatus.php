<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadRequirementStatus extends Model
{
    protected $fillable = ['lead_id', 'lead_requirement_id', 'is_complete', 'value'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function requirement()
    {
        return $this->belongsTo(LeadRequirement::class, 'lead_requirement_id');
    }
}
