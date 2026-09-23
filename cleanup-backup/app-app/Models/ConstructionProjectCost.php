<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructionProjectCost extends Model
{
    protected $fillable = [
        'construction_project_id', 'financial_transaction_id', 'cost_date',
        'cost_category', 'vendor', 'description', 'amount', 'source',
        'status', 'metadata',
    ];

    protected $casts = [
        'cost_date' => 'date',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];
}
