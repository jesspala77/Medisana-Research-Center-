<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\WorkflowAction;
use Illuminate\Support\Facades\Auth;

class WorkflowService
{
    public function apply(Lead $lead, WorkflowAction $action): Lead
    {
        $before = $lead->only(['status', 'stage', 'next_follow_up_at', 'sla_status']);

        $updates = [];
        if ($action->new_status) {
            $updates['status'] = $action->new_status;
        }
        if ($action->new_stage) {
            $updates['stage'] = $action->new_stage;
        }
        if ($action->follow_up_hours) {
            $updates['next_follow_up_at'] = now()->addHours($action->follow_up_hours);
        }

        if ($action->slug === 'called-no-answer') {
            $updates['last_contacted_at'] = now();
        }

        if ($action->new_status && in_array($action->new_status, ['converted', 'funded', 'bound', 'enrolled', 'quote_accepted', 'completed'], true)) {
            $updates['converted_at'] = now();
        }

        if ($action->new_status && in_array($action->new_status, ['lost', 'screen_failed'], true)) {
            $updates['lost_at'] = now();
        }

        $lead->update($updates);

        $lead->activities()->create([
            'user_id' => Auth::id(),
            'type' => $action->activity_type,
            'title' => $action->button_label,
            'description' => $action->activity_note,
        ]);

        if ($action->task_template) {
            $lead->tasks()->create([
                'assigned_to' => Auth::id(),
                'title' => $action->task_template['title'] ?? 'Follow up',
                'description' => $action->task_template['description'] ?? null,
                'priority' => $action->task_template['priority'] ?? 'normal',
                'due_at' => now()->addHours($action->follow_up_hours ?? 24),
            ]);
        }

        app(SlaService::class)->updateStatus($lead->fresh());
        app(LeadScoreService::class)->recalculate($lead->fresh());

        $lead->fresh()->update([
            'next_best_action' => app(NextBestActionService::class)->determine($lead->fresh()),
        ]);

        $after = $lead->fresh()->only(['status', 'stage', 'next_follow_up_at', 'sla_status']);

        \DB::table('workflow_logs')->insert([
            'lead_id' => $lead->id,
            'workflow_action_id' => $action->id,
            'user_id' => Auth::id(),
            'before' => json_encode($before),
            'after' => json_encode($after),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $lead->fresh();
    }
}
