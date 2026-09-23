<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadTask;

class PalmettoWorkflowService
{
    public function handleLeadUpdate(Lead $lead): void
    {
        if ($lead->palmetto_interest_level === 'High') {
            $this->createTaskIfMissing($lead, 'palmetto_follow_up');
        }

        if ($lead->surety_pipeline === 'Application Submitted') {
            $this->createTaskIfMissing($lead, 'palmetto_application_check');
        }

        if ($lead->surety_pipeline === 'Approved') {
            $this->createTaskIfMissing($lead, 'epower_onboarding');
        }
    }

    private function createTaskIfMissing(Lead $lead, string $taskType): void
    {
        $exists = $lead->tasks()
            ->where('task_type', $taskType)
            ->whereIn('status', ['pending', 'open'])
            ->exists();

        if ($exists) {
            return;
        }

        match ($taskType) {
            'palmetto_follow_up' => LeadTask::createPalmettoFollowUp($lead, 3),
            'palmetto_application_check' => LeadTask::createApplicationCheckTask($lead, 2),
            'epower_onboarding' => LeadTask::createEPowerOnboardingTask($lead, 1),
            default => null,
        };
    }
}