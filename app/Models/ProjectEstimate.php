<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEstimate extends Model
{
    protected $fillable = ['remodeling_project_id', 'created_by', 'estimate_number', 'version_number', 'status', 'estimate_date', 'sent_date', 'viewed_date', 'accepted_date', 'rejected_date', 'expires_at', 'labor_cost', 'material_cost', 'subcontractor_cost', 'overhead_cost', 'contingency_percent', 'markup_percent', 'tax_amount', 'total_amount', 'gross_profit_amount', 'gross_profit_percent', 'scope_of_work', 'exclusions', 'customer_objections', 'follow_up_notes', 'next_follow_up_date', 'metadata'];

    protected $casts = ['metadata' => 'array'];

    public function project()
    {
        return $this->belongsTo(RemodelingProject::class, 'remodeling_project_id');
    }

    public function lineItems()
    {
        return $this->hasMany(EstimateLineItem::class);
    }
}
