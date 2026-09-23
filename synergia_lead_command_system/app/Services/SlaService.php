<?php

namespace App\Services;

use App\Models\Lead;

class SlaService
{
    public function updateStatus(Lead $lead): Lead
    {
        $status = 'healthy';

        if ($lead->first_response_due_at && $lead->first_response_due_at->isPast() && ! $lead->last_contacted_at) {
            $status = 'overdue';
        } elseif ($lead->next_follow_up_at && $lead->next_follow_up_at->isPast()) {
            $status = 'overdue';
        } elseif ($lead->next_follow_up_at && $lead->next_follow_up_at->lte(now()->addHours(4))) {
            $status = 'warning';
        }

        $lead->update(['sla_status' => $status]);

        return $lead->fresh();
    }

    public function setInitialSla(Lead $lead, int $firstResponseMinutes = 60): Lead
    {
        $lead->update([
            'first_response_due_at' => now()->addMinutes($firstResponseMinutes),
            'sla_status' => 'healthy',
        ]);

        return $lead->fresh();
    }
}
