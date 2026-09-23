<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['industry_id', 'name', 'source', 'channel', 'budget', 'starts_at', 'ends_at', 'is_active'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
