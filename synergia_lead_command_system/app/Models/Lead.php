<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'industry_id', 'company_id', 'contact_id', 'campaign_id', 'title', 'status', 'stage', 'priority',
        'estimated_value', 'lead_score', 'quality_score', 'urgency_score', 'fit_score', 'completeness_score',
        'first_response_due_at', 'next_follow_up_at', 'last_contacted_at', 'qualified_at', 'converted_at', 'lost_at',
        'sla_status', 'next_best_action', 'summary', 'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'first_response_due_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'last_contacted_at' => 'datetime',
        'qualified_at' => 'datetime',
        'converted_at' => 'datetime',
        'lost_at' => 'datetime',
        'estimated_value' => 'decimal:2',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function tasks()
    {
        return $this->hasMany(LeadTask::class);
    }

    public function requirements()
    {
        return $this->hasMany(LeadRequirementStatus::class);
    }

    public function fundingProfile()
    {
        return $this->hasOne(FundingProfile::class);
    }

    public function constructionProject()
    {
        return $this->hasOne(ConstructionProject::class);
    }

    public function clinicalProfile()
    {
        return $this->hasOne(ClinicalProfile::class);
    }

    public function bondProfile()
    {
        return $this->hasOne(BondProfile::class);
    }
}
