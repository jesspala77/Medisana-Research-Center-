<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionClassificationFeedback extends Model
{
    protected $table = 'transaction_classification_feedback';

    protected $fillable = [
        'financial_transaction_id',
        'predicted_category',
        'confirmed_category',
        'confirmed_project_id',
        'reviewer_note',
        'reviewed_by',
    ];

    public function transaction()
    {
        return $this->belongsTo(FinancialTransaction::class, 'financial_transaction_id');
    }
}
