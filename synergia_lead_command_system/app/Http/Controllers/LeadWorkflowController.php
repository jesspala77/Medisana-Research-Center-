<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\WorkflowAction;
use App\Services\WorkflowService;

class LeadWorkflowController extends Controller
{
    public function apply(Lead $lead, WorkflowAction $workflowAction, WorkflowService $service)
    {
        return response()->json([
            'data' => $service->apply($lead, $workflowAction),
        ]);
    }
}
