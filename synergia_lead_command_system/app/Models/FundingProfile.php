<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundingProfile extends Model
{
    protected $fillable = [
        'lead_id', 'monthly_revenue', 'requested_amount', 'funding_purpose', 'time_in_business',
        'credit_score_range', 'bank_statements_uploaded', 'approval_status', 'funded_amount',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
