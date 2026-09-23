<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['company_id', 'first_name', 'last_name', 'title', 'phone', 'email', 'preferred_contact_method', 'metadata'];

    protected $casts = ['metadata' => 'array'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
