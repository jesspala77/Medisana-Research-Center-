<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemodelingProject extends Model
{
    protected $fillable = ['lead_id', 'contact_id', 'company_id', 'project_number', 'project_name', 'stage', 'status', 'lead_source', 'property_type', 'project_category', 'urgency_level', 'property_address', 'property_city', 'property_state', 'property_zip', 'budget_min', 'budget_max', 'accepted_budget', 'desired_start_date', 'target_completion_date', 'probability_to_close', 'project_score', 'scope_summary', 'customer_objectives', 'internal_notes', 'metadata'];

    protected $casts = ['metadata' => 'array', 'desired_start_date' => 'date', 'target_completion_date' => 'date'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function estimates()
    {
        return $this->hasMany(ProjectEstimate::class);
    }

    public function siteVisits()
    {
        return $this->hasMany(SiteVisit::class);
    }

    public function proposals()
    {
        return $this->hasMany(ProjectProposal::class);
    }

    public function contracts()
    {
        return $this->hasMany(ProjectContract::class);
    }

    public function tasks()
    {
        return $this->hasMany(RemodelingTask::class);
    }

    public function notes()
    {
        return $this->hasMany(RemodelingNote::class);
    }
}
