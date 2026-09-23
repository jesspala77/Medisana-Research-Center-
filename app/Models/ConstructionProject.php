<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructionProject extends Model
{
    protected $fillable = [
        'lead_id', 'project_type', 'property_type', 'address', 'square_footage', 'estimated_budget',
        'financing_needed', 'permit_required', 'contractor_assigned', 'project_status', 'desired_start_date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
