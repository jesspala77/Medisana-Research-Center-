<?php

namespace App\Http\Controllers;

use App\Models\RegulatoryServiceRecord;
use App\Services\ClinicalRegulatoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClinicalRegulatoryController extends Controller
{
    public function dashboard(Request $request, ClinicalRegulatoryService $service)
    {
        return view('clinical-regulatory.dashboard', [
            'services' => $service->services(),
            'resources' => $service->resources(),
            'workflowSteps' => $service->workflowSteps(),
            'statuses' => $service->statuses(),
            'summary' => $service->summary($this->organizationKey($request)),
        ]);
    }

    public function index(Request $request, ClinicalRegulatoryService $service)
    {
        $records = RegulatoryServiceRecord::query()
            ->when($this->organizationKey($request), fn ($query, $organizationKey) => $query->where('organization_key', $organizationKey))
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->query('service_category'), fn ($query, $category) => $query->where('service_category', $category))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('clinical-regulatory.index', [
            'records' => $records,
            'services' => $service->services(),
            'statuses' => $service->statuses(),
        ]);
    }

    public function create(Request $request, ClinicalRegulatoryService $service)
    {
        return view('clinical-regulatory.create', [
            'services' => $service->services(),
            'statuses' => $service->statuses(),
            'priorities' => $service->priorities(),
            'prefill' => $this->prefill($request, $service),
        ]);
    }

    public function store(Request $request, ClinicalRegulatoryService $service)
    {
        $validated = $request->validate($this->rules($service));

        $record = RegulatoryServiceRecord::create([
            ...$validated,
            'organization_key' => $request->user()->organization_key,
        ]);

        return redirect()->route('clinical-regulatory.records.show', $record)
            ->with('success', 'Regulatory service record created.');
    }

    public function show(Request $request, RegulatoryServiceRecord $regulatoryServiceRecord, ClinicalRegulatoryService $service)
    {
        $this->ensureRecordAccess($request, $regulatoryServiceRecord);

        return view('clinical-regulatory.show', [
            'record' => $regulatoryServiceRecord,
            'services' => $service->services(),
            'statuses' => $service->statuses(),
            'priorities' => $service->priorities(),
        ]);
    }

    public function updateStatus(Request $request, RegulatoryServiceRecord $regulatoryServiceRecord, ClinicalRegulatoryService $service)
    {
        $this->ensureRecordAccess($request, $regulatoryServiceRecord);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($service->statuses()))],
            'priority' => ['required', Rule::in(array_keys($service->priorities()))],
            'due_date' => 'nullable|date',
            'current_blocker' => 'nullable|string',
            'missing_documents' => 'nullable|string',
            'next_step' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $regulatoryServiceRecord->update($validated);

        return back()->with('success', 'Regulatory service record updated.');
    }

    private function prefill(Request $request, ClinicalRegulatoryService $service): array
    {
        $category = (string) $request->query('service_category');
        $selected = $service->services()[$category] ?? null;

        return [
            'service_category' => $category,
            'request_title' => $request->query('request_title', $selected['title'] ?? ''),
            'priority' => $request->query('priority', $selected['default_priority'] ?? 'normal'),
            'status' => $request->query('status', 'not_started'),
            'notes' => $request->query('notes', $selected['default_notes'] ?? ''),
        ];
    }

    private function rules(ClinicalRegulatoryService $service): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'site_name' => 'nullable|string|max:255',
            'service_category' => ['required', Rule::in(array_keys($service->services()))],
            'request_title' => 'required|string|max:255',
            'sponsor' => 'nullable|string|max:255',
            'protocol' => 'nullable|string|max:255',
            'primary_contact_name' => 'nullable|string|max:255',
            'primary_contact_email' => 'nullable|email|max:255',
            'primary_contact_phone' => 'nullable|string|max:255',
            'regulatory_owner' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(array_keys($service->statuses()))],
            'priority' => ['required', Rule::in(array_keys($service->priorities()))],
            'due_date' => 'nullable|date',
            'current_blocker' => 'nullable|string',
            'documents_available' => 'nullable|string',
            'missing_documents' => 'nullable|string',
            'approval_path' => 'nullable|string',
            'next_step' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    private function organizationKey(Request $request): ?string
    {
        return $request->user()->isPlatformAdmin() ? null : $request->user()->organization_key;
    }

    private function ensureRecordAccess(Request $request, RegulatoryServiceRecord $record): void
    {
        abort_unless(
            $request->user()->isPlatformAdmin()
                || ($record->organization_key && $request->user()->canAccessOrganization($record->organization_key)),
            403
        );
    }
}
