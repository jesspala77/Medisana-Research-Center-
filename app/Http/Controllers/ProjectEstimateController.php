<?php

namespace App\Http\Controllers;

use App\Models\EstimateLineItem;
use App\Models\ProjectEstimate;
use App\Models\RemodelingProject;
use App\Services\EstimateCalculatorService;
use Illuminate\Http\Request;

class ProjectEstimateController extends Controller
{
    public function store(Request $request, RemodelingProject $project)
    {
        $data = $request->validate(['scope_of_work' => 'nullable|string', 'subcontractor_cost' => 'nullable|numeric', 'overhead_cost' => 'nullable|numeric', 'contingency_percent' => 'nullable|numeric', 'markup_percent' => 'nullable|numeric']);
        ProjectEstimate::create(['remodeling_project_id' => $project->id, 'estimate_number' => 'EST-'.now()->format('YmdHis'), 'version_number' => 1, 'status' => 'draft', 'estimate_date' => now()->toDateString(), 'scope_of_work' => $data['scope_of_work'] ?? null, 'subcontractor_cost' => $data['subcontractor_cost'] ?? 0, 'overhead_cost' => $data['overhead_cost'] ?? 0, 'contingency_percent' => $data['contingency_percent'] ?? 10, 'markup_percent' => $data['markup_percent'] ?? 20]);
        $project->update(['stage' => 'estimate_draft']);

        return back();
    }

    public function addLineItem(Request $request, ProjectEstimate $estimate, EstimateCalculatorService $calculator)
    {
        $data = $request->validate(['category' => 'nullable|string|max:255', 'description' => 'required|string|max:255', 'quantity' => 'nullable|numeric', 'unit' => 'nullable|string|max:50', 'unit_cost' => 'nullable|numeric', 'labor_hours' => 'nullable|numeric', 'labor_rate' => 'nullable|numeric', 'markup_percent' => 'nullable|numeric']);
        EstimateLineItem::create(['project_estimate_id' => $estimate->id, 'category' => $data['category'] ?? null, 'description' => $data['description'], 'quantity' => $data['quantity'] ?? 1, 'unit' => $data['unit'] ?? 'each', 'unit_cost' => $data['unit_cost'] ?? 0, 'labor_hours' => $data['labor_hours'] ?? 0, 'labor_rate' => $data['labor_rate'] ?? 0, 'markup_percent' => $data['markup_percent'] ?? 0]);
        $calculator->recalculate($estimate);

        return back();
    }

    public function markSent(ProjectEstimate $estimate)
    {
        $estimate->update(['status' => 'sent', 'sent_date' => now()->toDateString()]);
        $estimate->project->update(['stage' => 'estimate_sent']);

        return back();
    }

    public function markAccepted(ProjectEstimate $estimate)
    {
        $estimate->update(['status' => 'accepted', 'accepted_date' => now()->toDateString()]);
        $estimate->project->update(['stage' => 'won', 'accepted_budget' => $estimate->total_amount, 'probability_to_close' => 100]);

        return back();
    }
}
