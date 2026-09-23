<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateLineItem extends Model
{
    protected $fillable = ['project_estimate_id', 'category', 'description', 'quantity', 'unit', 'unit_cost', 'labor_hours', 'labor_rate', 'markup_percent', 'line_total', 'sort_order'];

    public function estimate()
    {
        return $this->belongsTo(ProjectEstimate::class, 'project_estimate_id');
    }
}
