<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\WorkflowAction;
use App\Services\PalmettoWorkflowService;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class LeadWorkflowController extends Controller
{
    public function apply(
        Request $request,
        Lead $lead,
        WorkflowAction $workflowAction,
        WorkflowService $service,
        PalmettoWorkflowService $palmettoWorkflowService
    ) {
        abort_unless($workflowAction->is_active, 404);
        abort_if($workflowAction->industry_id && $workflowAction->industry_id !== $lead->industry_id, 404);

        $result = $service->apply($lead, $workflowAction);

        $lead->refresh();

        $palmettoWorkflowService->handleLeadUpdate($lead);

        if (! $request->expectsJson()) {
            return back()->with('success', 'Workflow action applied.');
        }

        return response()->json([
            'data' => $result,
        ]);
    }
}
