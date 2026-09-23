<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Services\LeadModuleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SynNexusIntakeController extends Controller
{
    public function create(LeadModuleService $service)
    {
        return view('intake.cnc-quote', [
            'module' => $service->get('cnc-quote'),
        ]);
    }

    public function store(Request $request, LeadModuleService $service)
    {
        $module = $service->get('cnc-quote');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'estimated_value' => 'nullable|numeric',
            'priority' => ['nullable', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'summary' => 'nullable|string',
            ...$service->profileRules($module),
        ]);

        $industry = $service->ensureIndustry($module);

        $company = null;
        if (! empty($validated['company_name'])) {
            $company = Company::create([
                'industry_id' => $industry->id,
                'name' => $validated['company_name'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
            ]);
        }

        $contact = Contact::create([
            'company_id' => $company?->id,
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'metadata' => [
                'lead_source' => 'synnexus_intake',
            ],
        ]);

        $lead = Lead::create([
            'industry_id' => $industry->id,
            'company_id' => $company?->id,
            'contact_id' => $contact->id,
            'title' => $validated['title'],
            'status' => $module['default_status'],
            'stage' => 'intake',
            'priority' => $validated['priority'] ?? 'high',
            'estimated_value' => $validated['estimated_value'] ?? 0,
            'lead_score' => 70,
            'quality_score' => 60,
            'urgency_score' => ($validated['priority'] ?? 'high') === 'urgent' ? 90 : 65,
            'fit_score' => 65,
            'completeness_score' => 70,
            'next_follow_up_at' => now()->addHours(4),
            'sla_status' => 'healthy',
            'next_best_action' => 'Review RFQ details and confirm manufacturability',
            'summary' => $validated['summary'] ?? null,
            'metadata' => [
                'lead_source' => 'synnexus_intake',
                'module' => 'cnc-quote',
                'intake_origin' => 'public_form',
                'business_unit' => 'k-and-g-art-designs',
            ],
        ]);

        $profileModel = $module['profile_model'];
        $profileModel::create([
            'lead_id' => $lead->id,
            ...$service->profileData($module, $validated),
        ]);

        return redirect()
            ->route('intake.cnc.create')
            ->with('success', 'Request received for K & G Art Designs. SynNexus intake created your CNC lead and queued RFQ review.');
    }
}