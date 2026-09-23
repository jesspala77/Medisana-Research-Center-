<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BondProfile extends Model
{
    protected $fillable = [
        'lead_id',
        'county',
        'jail_facility',
        'bond_amount',
        'defendant_name',
        'case_number',
        'attorney_name',
        'urgency_level',
        'referral_partner',

        // Agency / surety profile
        'agency_type',
        'current_products',
        'current_surety',
        'current_rate',
        'palmetto_rate',
        'palmetto_eligible',
        'coverage_gap',
        'additional_coverage_needed',
        'estimated_savings',

        // ePower workflow
        'has_epowers',
        'epower_interest',
        'epower_status',
        'epower_activation_date',

        // Sales / policy workflow
        'renewal_date',
        'review_date',
        'quote_amount',
        'policy_status',
        'southernmost_status',
        'palmetto_stage',
    ];

    protected $casts = [
        'renewal_date' => 'date',
        'review_date' => 'date',
        'epower_activation_date' => 'date',
        'bond_amount' => 'decimal:2',
        'quote_amount' => 'decimal:2',
        'current_rate' => 'decimal:4',
        'palmetto_rate' => 'decimal:4',
        'estimated_savings' => 'decimal:2',
        'palmetto_eligible' => 'boolean',
        'has_epowers' => 'boolean',
        'epower_interest' => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}