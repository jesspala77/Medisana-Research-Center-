<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalProfile extends Model
{
    protected $fillable = [
        'lead_id', 'dob', 'condition_interest', 'protocol_interest', 'prescreen_notes', 'prescreen_status', 'screening_date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
