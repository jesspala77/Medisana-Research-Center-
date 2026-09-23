<?php

namespace App\Services;

use App\Models\Lead;

class CommandQueueService
{
    public function getQueue()
    {
        $terminalStatuses = ['converted', 'lost', 'funded', 'bound', 'enrolled', 'screen_failed', 'quote_accepted', 'completed'];

        return Lead::with(['industry', 'contact', 'company'])
            ->whereNotIn('status', $terminalStatuses)
            ->get()
            ->map(function ($lead) {
                $slaRisk = match ($lead->sla_status) {
                    'overdue' => 40,
                    'warning' => 20,
                    default => 0,
                };

                $followUpRisk = 0;

                if ($lead->next_follow_up_at && $lead->next_follow_up_at->isPast()) {
                    $followUpRisk = 30;
                }

                $dealValueScore = min(30, ($lead->estimated_value ?? 0) / 1000);

                $lead->command_priority_score =
                    ($lead->lead_score ?? 0)
                    + $slaRisk
                    + $followUpRisk
                    + $dealValueScore;

                return $lead;
            })
            ->sortByDesc('command_priority_score')
            ->take(100)
            ->values();
    }
}
