<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadRequirement extends Model
{
    protected $fillable = ['industry_id', 'field_key', 'label', 'is_required', 'sort_order'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
