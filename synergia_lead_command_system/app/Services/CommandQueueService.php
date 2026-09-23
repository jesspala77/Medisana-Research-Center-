<?php

namespace App\Services;

use App\Models\Lead;

class CommandQueueService
{
    public function getQueue()
    {
        return Lead::with(['industry', 'contact', 'company'])
            ->select('*')
            ->selectRaw("
                (
                    lead_score
                    + CASE WHEN sla_status = 'overdue' THEN 40 WHEN sla_status = 'warning' THEN 20 ELSE 0 END
                    + CASE WHEN next_follow_up_at IS NOT NULL AND next_follow_up_at < NOW() THEN 30 ELSE 0 END
                    + LEAST(30, estimated_value / 1000)
                ) as command_priority_score
            ")
            ->whereNotIn('status', ['converted', 'lost'])
            ->orderByDesc('command_priority_score')
            ->orderBy('created_at')
            ->limit(100)
            ->get();
    }
}
