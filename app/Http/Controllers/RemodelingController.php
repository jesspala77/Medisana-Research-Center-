<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\RemodelingProject;
use App\Services\RemodelingDashboardService;
use Illuminate\Http\Request;

class RemodelingController extends Controller
{
    public function dashboard(RemodelingDashboardService $service)
    {
        return view('remodeling.dashboard', ['summary' => $service->summary()]);
    }

    public function index()
    {
        return view('remodeling.index', ['projects' => RemodelingProject::with('contact')->latest()->paginate(25)]);
    }

    public function create()
    {
        return view('remodeling.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['project_name' => 'required|string|max:255', 'first_name' => 'nullable|string|max:255', 'last_name' => 'nullable|string|max:255', 'phone' => 'nullable|string|max:255', 'email' => 'nullable|email|max:255', 'lead_source' => 'nullable|string|max:255', 'property_type' => 'nullable|string|max:255', 'project_category' => 'nullable|string|max:255', 'property_address' => 'nullable|string|max:255', 'budget_min' => 'nullable|numeric', 'budget_max' => 'nullable|numeric', 'desired_start_date' => 'nullable|date', 'scope_summary' => 'nullable|string']);
        $contact = Contact::create(['first_name' => $data['first_name'] ?? null, 'last_name' => $data['last_name'] ?? null, 'phone' => $data['phone'] ?? null, 'email' => $data['email'] ?? null]);
        $project = RemodelingProject::create(['contact_id' => $contact->id, 'project_number' => 'RMD-'.now()->format('YmdHis'), 'project_name' => $data['project_name'], 'stage' => 'new_lead', 'status' => 'active', 'lead_source' => $data['lead_source'] ?? null, 'property_type' => $data['property_type'] ?? null, 'project_category' => $data['project_category'] ?? null, 'property_address' => $data['property_address'] ?? null, 'budget_min' => $data['budget_min'] ?? null, 'budget_max' => $data['budget_max'] ?? null, 'desired_start_date' => $data['desired_start_date'] ?? null, 'scope_summary' => $data['scope_summary'] ?? null, 'probability_to_close' => 25, 'project_score' => 50]);

        return redirect()->route('remodeling.projects.show', $project);
    }

    public function show(RemodelingProject $project)
    {
        $project->load(['contact', 'siteVisits', 'estimates.lineItems', 'proposals', 'contracts', 'tasks', 'notes']);

        return view('remodeling.show', compact('project'));
    }

    public function updateStage(Request $request, RemodelingProject $project)
    {
        $request->validate(['stage' => 'required|string|max:255']);
        $project->update(['stage' => $request->stage]);

        return back();
    }
}
