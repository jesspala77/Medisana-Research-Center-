<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProposal extends Model
{
    protected $fillable = ['remodeling_project_id', 'project_estimate_id', 'proposal_number', 'status', 'sent_at', 'viewed_at', 'follow_up_due_at', 'accepted_at', 'rejected_at', 'proposal_amount', 'counteroffer_amount', 'primary_objection', 'negotiation_notes', 'proposal_terms'];

    public function project()
    {
        return $this->belongsTo(RemodelingProject::class, 'remodeling_project_id');
    }
}
