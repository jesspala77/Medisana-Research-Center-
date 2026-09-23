<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
