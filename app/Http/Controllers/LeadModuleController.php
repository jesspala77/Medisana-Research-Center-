<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\WorkflowAction;
use App\Services\LeadModuleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadModuleController extends Controller
{
    public function dashboard(Request $request, LeadModuleService $service)
    {
        $module = $request->route('module');
        $moduleConfig = $service->get($module);
        $service->ensureIndustry($moduleConfig);

        return view('lead-modules.dashboard', [
            'module' => $moduleConfig,
            'modules' => $service->all(),
            'summary' => $service->summary($moduleConfig, $this->organizationKey($request)),
        ]);
    }

    public function index(Request $request, LeadModuleService $service)
    {
        $module = $request->route('module');
        $moduleConfig = $service->get($module);
        $service->ensureIndustry($moduleConfig);

        return view('lead-modules.index', [
            'module' => $moduleConfig,
            'leads' => $service->query($moduleConfig, $this->organizationKey($request))->latest()->paginate(25),
        ]);
    }

    public function create(Request $request, LeadModuleService $service)
    {
        return view('lead-modules.create', [
            'module' => $service->get($request->route('module')),
        ]);
    }

    public function store(Request $request, LeadModuleService $service)
    {
        $module = $request->route('module');
        $moduleConfig = $service->get($module);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'lead_source' => 'nullable|string|max:255',
            'priority' => ['nullable', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'estimated_value' => 'nullable|numeric',
            'next_follow_up_at' => 'nullable|date',
            'summary' => 'nullable|string',
            ...$service->profileRules($moduleConfig),
        ]);

        $industry = $service->ensureIndustry($moduleConfig);

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
                'lead_source' => $validated['lead_source'] ?? null,
            ],
        ]);

        $lead = Lead::create([
            'organization_key' => $request->user()->organization_key,
            'industry_id' => $industry->id,
            'company_id' => $company?->id,
            'contact_id' => $contact->id,
            'title' => $validated['title'],
            'status' => $moduleConfig['default_status'],
            'stage' => 'intake',
            'priority' => $validated['priority'] ?? 'normal',
            'estimated_value' => $validated['estimated_value'] ?? 0,
            'lead_score' => $this->scoreLead($validated, $moduleConfig),
            'quality_score' => 50,
            'urgency_score' => ($validated['priority'] ?? 'normal') === 'urgent' ? 90 : 50,
            'fit_score' => 60,
            'completeness_score' => $this->completenessScore($validated, $moduleConfig),
            'next_follow_up_at' => $validated['next_follow_up_at'] ?? now()->addDay(),
            'sla_status' => 'healthy',
            'next_best_action' => $this->nextBestAction($moduleConfig['default_status'], $moduleConfig),
            'summary' => $validated['summary'] ?? null,
            'metadata' => [
                'lead_source' => $validated['lead_source'] ?? null,
                'module' => $moduleConfig['key'],
            ],
        ]);

        $profileModel = $moduleConfig['profile_model'];
        $profileModel::create([
            'lead_id' => $lead->id,
            ...$service->profileData($moduleConfig, $validated),
        ]);

        return redirect()->route($moduleConfig['route_name'].'.leads.show', $lead);
    }

    public function show(Request $request, Lead $lead, LeadModuleService $service)
    {
        $module = $request->route('module');
        $moduleConfig = $service->get($module);
        $lead->load(['industry', 'company', 'contact', $moduleConfig['profile_relation']]);
        $this->ensureLeadAccess($request, $lead);
        abort_unless($lead->industry?->slug === $moduleConfig['industry']['slug'], 404);

        $validWorkflowAction = fn ($query) => $query
            ->where('is_active', true)
            ->where(function ($query) use ($moduleConfig) {
                $query->whereNull('new_status')
                    ->orWhereIn('new_status', array_keys($moduleConfig['status_options']));
            });

        $industryWorkflowActions = $validWorkflowAction(WorkflowAction::query())
            ->where('industry_id', $lead->industry_id)
            ->orderBy('name')
            ->get();

        $workflowActions = $industryWorkflowActions->isNotEmpty()
            ? $industryWorkflowActions
            : $validWorkflowAction(WorkflowAction::query())->whereNull('industry_id')->orderBy('name')->get();

        return view('lead-modules.show', [
            'module' => $moduleConfig,
            'lead' => $lead,
            'profile' => $lead->{$moduleConfig['profile_relation']},
            'workflowActions' => $workflowActions,
        ]);
    }

    public function updateStatus(Request $request, Lead $lead, LeadModuleService $service)
    {
        $module = $request->route('module');
        $moduleConfig = $service->get($module);
        $lead->load('industry');
        $this->ensureLeadAccess($request, $lead);
        abort_unless($lead->industry?->slug === $moduleConfig['industry']['slug'], 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($moduleConfig['status_options']))],
            'priority' => ['required', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'next_follow_up_at' => 'nullable|date',
            'next_best_action' => 'nullable|string|max:255',
        ]);

        $lead->update([
            'status' => $validated['status'],
            'stage' => in_array($validated['status'], [$moduleConfig['converted_status'], $moduleConfig['lost_status']], true) ? 'closed' : 'active',
            'priority' => $validated['priority'],
            'next_follow_up_at' => $validated['next_follow_up_at'] ?? null,
            'next_best_action' => $validated['next_best_action'] ?: $this->nextBestAction($validated['status'], $moduleConfig),
            'converted_at' => $validated['status'] === $moduleConfig['converted_status'] ? now() : $lead->converted_at,
            'lost_at' => $validated['status'] === $moduleConfig['lost_status'] ? now() : $lead->lost_at,
        ]);

        return back();
    }

    private function scoreLead(array $validated, array $module): int
    {
        $score = 45;
        $score += ! empty($validated['phone']) ? 10 : 0;
        $score += ! empty($validated['email']) ? 10 : 0;
        $score += ! empty($validated['estimated_value']) ? 10 : 0;
        $score += ($validated['priority'] ?? 'normal') === 'urgent' ? 15 : 0;
        $score += count(array_filter($validated['profile'] ?? [])) >= count($module['required_profile_fields']) ? 10 : 0;

        return min(100, $score);
    }

    private function completenessScore(array $validated, array $module): int
    {
        $fields = ['title', 'phone', 'email', 'estimated_value', ...array_map(fn ($field) => 'profile.'.$field, $module['required_profile_fields'])];
        $filled = 0;

        foreach ($fields as $field) {
            if (str_starts_with($field, 'profile.')) {
                $profileField = substr($field, 8);
                $filled += ! empty($validated['profile'][$profileField]) ? 1 : 0;

                continue;
            }

            $filled += ! empty($validated[$field]) ? 1 : 0;
        }

        return (int) round(($filled / count($fields)) * 100);
    }

    private function nextBestAction(string $status, array $module): string
    {
        return match ($status) {
            'new_request' => 'Call owner and qualify funding request',
            'docs_needed' => 'Collect bank statements and underwriting package',
            'offer_sent' => 'Follow up on offer acceptance',
            'new_agency' => 'Identify decision maker and relationship owner',
            'coverage_gap_found' => 'Schedule coverage review',
            'quote_sent' => 'Follow up to bind or handle objections',
            'new_candidate' => 'Call patient candidate for first touch',
            'prescreening' => 'Complete prescreen criteria review',
            'qualified' => 'Schedule screening visit',
            'new_quote_request' => 'Review RFQ details and confirm manufacturability',
            'rfq_review' => 'Request any missing drawings, tolerances, or finish notes',
            'quoted' => 'Follow up on quote decision and production slot',
            default => 'Move lead to the next workflow step',
        };
    }

    private function organizationKey(Request $request): ?string
    {
        return $request->user()->isPlatformAdmin() ? null : $request->user()->organization_key;
    }

    private function ensureLeadAccess(Request $request, Lead $lead): void
    {
        abort_unless(
            $request->user()->isPlatformAdmin()
                || ($lead->organization_key && $request->user()->canAccessOrganization($lead->organization_key)),
            403
        );
    }
}
