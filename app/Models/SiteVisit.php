<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = ['remodeling_project_id', 'assigned_user_id', 'scheduled_at', 'completed_at', 'status', 'property_square_feet', 'year_built', 'hoa', 'permit_likely_required', 'occupancy_status', 'measurements_notes', 'site_conditions', 'risks_or_concerns', 'customer_preferences', 'estimator_notes', 'metadata'];

    protected $casts = ['scheduled_at' => 'datetime', 'completed_at' => 'datetime', 'hoa' => 'boolean', 'permit_likely_required' => 'boolean', 'metadata' => 'array'];

    public function project()
    {
        return $this->belongsTo(RemodelingProject::class, 'remodeling_project_id');
    }
}
