<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectContract extends Model
{
    protected $fillable = ['remodeling_project_id', 'project_proposal_id', 'contract_number', 'status', 'sent_at', 'signed_at', 'contract_amount', 'deposit_amount', 'deposit_due_date', 'deposit_received_date', 'financing_required', 'financing_company', 'financing_status', 'financing_amount', 'insurance_claim', 'insurance_company', 'claim_number', 'contract_terms'];

    public function project()
    {
        return $this->belongsTo(RemodelingProject::class, 'remodeling_project_id');
    }
}
