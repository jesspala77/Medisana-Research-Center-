<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'industry_id',
        'name',
        'website',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}