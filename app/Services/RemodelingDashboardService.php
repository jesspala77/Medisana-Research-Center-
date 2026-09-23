<?php

namespace App\Services;

use App\Models\ProjectContract;
use App\Models\ProjectEstimate;
use App\Models\ProjectProposal;
use App\Models\RemodelingProject;

class RemodelingDashboardService
{
    public function summary(): array
    {
        return [
            'projects_total' => RemodelingProject::count(),
            'active_projects' => RemodelingProject::where('status', 'active')->count(),
            'new_leads' => RemodelingProject::where('stage', 'new_lead')->count(),
            'site_visits_scheduled' => RemodelingProject::where('stage', 'site_visit_scheduled')->count(),
            'estimates_draft' => ProjectEstimate::where('status', 'draft')->count(),
            'estimates_sent_value' => ProjectEstimate::where('status', 'sent')->sum('total_amount'),
            'proposals_pending_value' => ProjectProposal::whereIn('status', ['sent', 'viewed', 'negotiation'])->sum('proposal_amount'),
            'contracts_signed_value' => ProjectContract::where('status', 'signed')->sum('contract_amount'),
            'recent_projects' => RemodelingProject::with('contact')->latest()->limit(10)->get(),
        ];
    }
}
