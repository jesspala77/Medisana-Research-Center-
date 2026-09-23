<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BondProfile extends Model
{
    protected $fillable = [
        'lead_id', 'county', 'jail_facility', 'bond_amount', 'defendant_name', 'case_number',
        'attorney_name', 'urgency_level', 'referral_partner',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
