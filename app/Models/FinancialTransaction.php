<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'source','external_id','transaction_date','merchant','description','amount',
        'original_category','construction_project_id','suggested_category',
        'classification_confidence','classification_status','classification_reasons',
        'raw_payload','classified_at','reviewed_at','reviewed_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'classification_confidence' => 'float',
        'classification_reasons' => 'array',
        'raw_payload' => 'array',
        'classified_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(ConstructionProject::class, 'construction_project_id');
    }

    public function classificationFeedback()
    {
        return $this->hasMany(TransactionClassificationFeedback::class);
    }
}
