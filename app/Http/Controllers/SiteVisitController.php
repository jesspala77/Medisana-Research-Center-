<?php

namespace App\Http\Controllers;

use App\Models\RemodelingProject;
use App\Models\SiteVisit;
use Illuminate\Http\Request;

class SiteVisitController extends Controller
{
    public function store(Request $request, RemodelingProject $project)
    {
        $data = $request->validate(['scheduled_at' => 'nullable|date', 'property_square_feet' => 'nullable|integer', 'year_built' => 'nullable|integer', 'occupancy_status' => 'nullable|string|max:255', 'estimator_notes' => 'nullable|string']);
        SiteVisit::create(['remodeling_project_id' => $project->id, 'scheduled_at' => $data['scheduled_at'] ?? null, 'property_square_feet' => $data['property_square_feet'] ?? null, 'year_built' => $data['year_built'] ?? null, 'hoa' => $request->boolean('hoa'), 'permit_likely_required' => $request->boolean('permit_likely_required'), 'occupancy_status' => $data['occupancy_status'] ?? null, 'estimator_notes' => $data['estimator_notes'] ?? null, 'status' => 'scheduled']);
        $project->update(['stage' => 'site_visit_scheduled']);

        return back();
    }

    public function complete(SiteVisit $siteVisit)
    {
        $siteVisit->update(['status' => 'completed', 'completed_at' => now()]);
        $siteVisit->project->update(['stage' => 'site_visit_completed']);

        return back();
    }
}
