<?php

namespace App\Services;

use App\Models\Lead;

class NextBestActionService
{
    public function determine(Lead $lead): string
    {
        if ($lead->sla_status === 'overdue') {
            return 'Call now';
        }

        $missing = $lead->requirements()
            ->where('is_complete', false)
            ->with('requirement')
            ->first();

        if ($missing && $missing->requirement) {
            return 'Request missing info: '.$missing->requirement->label;
        }

        return match ($lead->industry?->slug) {
            'business-capital-funding' => $this->fundingAction($lead),
            'construction-renovation' => $this->constructionAction($lead),
            'clinical-research' => $this->clinicalAction($lead),
            'bail-bonds' => $this->bondAction($lead),
            default => 'Review lead',
        };
    }

    private function fundingAction(Lead $lead): string
    {
        $profile = $lead->fundingProfile;
        if (! $profile) {
            return 'Complete funding profile';
        }
        if (! $profile->bank_statements_uploaded) {
            return 'Request bank statements';
        }
        if (! $profile->requested_amount) {
            return 'Confirm requested amount';
        }

        return 'Send funding offer or schedule review';
    }

    private function constructionAction(Lead $lead): string
    {
        $project = $lead->constructionProject;
        if (! $project) {
            return 'Complete renovation project profile';
        }
        if (! $project->estimated_budget) {
            return 'Confirm budget';
        }
        if (! $project->address) {
            return 'Confirm project address';
        }

        return 'Schedule estimate';
    }

    private function clinicalAction(Lead $lead): string
    {
        $profile = $lead->clinicalProfile;
        if (! $profile) {
            return 'Complete clinical profile';
        }
        if (! $profile->prescreen_status) {
            return 'Run prescreen';
        }

        return 'Schedule screening visit';
    }

    private function bondAction(Lead $lead): string
    {
        $profile = $lead->bondProfile;
        if (! $profile) {
            return 'Complete bond profile';
        }
        if (! $profile->bond_amount) {
            return 'Confirm bond amount';
        }

        return 'Call agency/referral partner';
    }
}
