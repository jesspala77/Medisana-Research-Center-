<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionVendorRule extends Model
{
    protected $fillable = [
        'normalized_vendor', 'match_type', 'match_value', 'category',
        'construction_project_id', 'confidence', 'times_confirmed',
        'active', 'metadata',
    ];

    protected $casts = [
        'confidence' => 'float',
        'active' => 'boolean',
        'metadata' => 'array',
    ];
}
