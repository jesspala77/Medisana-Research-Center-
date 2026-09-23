<?php

namespace App\Services;

use App\Models\Lead;

class LeadScoreService
{
    public function recalculate(Lead $lead): Lead
    {
        $completeness = $this->completenessScore($lead);
        $urgency = $this->urgencyScore($lead);
        $fit = $this->fitScore($lead);
        $value = min(100, (int) round(($lead->estimated_value ?? 0) / 1000));

        $quality = (int) round(
            ($completeness * 0.25) +
            ($urgency * 0.20) +
            ($fit * 0.30) +
            ($value * 0.25)
        );

        $lead->update([
            'completeness_score' => $completeness,
            'urgency_score' => $urgency,
            'fit_score' => $fit,
            'quality_score' => $quality,
            'lead_score' => $quality,
        ]);

        return $lead->fresh();
    }

    private function completenessScore(Lead $lead): int
    {
        $statuses = $lead->requirements()->get();
        if ($statuses->count() === 0) {
            return 50;
        }

        $completed = $statuses->where('is_complete', true)->count();

        return (int) round(($completed / $statuses->count()) * 100);
    }

    private function urgencyScore(Lead $lead): int
    {
        if ($lead->sla_status === 'overdue') {
            return 100;
        }
        if ($lead->sla_status === 'warning') {
            return 75;
        }
        if ($lead->next_follow_up_at && $lead->next_follow_up_at->isPast()) {
            return 90;
        }

        return 50;
    }

    private function fitScore(Lead $lead): int
    {
        return match ($lead->industry?->slug) {
            'business-capital-funding' => $lead->fundingProfile ? 80 : 55,
            'construction-renovation' => $lead->constructionProject ? 80 : 55,
            'clinical-research' => $lead->clinicalProfile ? 80 : 55,
            'bail-bonds' => $lead->bondProfile ? 80 : 55,
            'bond-agency-insurance' => $lead->bondProfile ? 80 : 55,
            'cnc-machining-services' => $lead->cncQuoteProfile ? 80 : 55,
            default => 50,
        };
    }
}
