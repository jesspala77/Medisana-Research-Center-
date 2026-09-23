<?php

namespace App\Services;

use App\Models\RegulatoryServiceRecord;

class ClinicalRegulatoryService
{
    public function statuses(): array
    {
        return [
            'not_started' => 'Not Started',
            'in_review' => 'In Review',
            'needs_client_action' => 'Needs Client Action',
            'ready' => 'Ready',
            'complete' => 'Complete',
            'on_hold' => 'On Hold',
            'canceled' => 'Canceled',
        ];
    }

    public function priorities(): array
    {
        return [
            'low' => 'Low',
            'normal' => 'Normal',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
    }

    public function services(): array
    {
        return [
            'regulatory_binder' => [
                'title' => 'Regulatory Binder / eReg Setup',
                'summary' => 'Build the essential document structure for each client, site, protocol, or sponsor request.',
                'tools' => ['Binder index', 'Essential document checklist', 'Version naming rules', 'Expiration tracker'],
                'default_priority' => 'high',
                'default_notes' => 'Set up binder structure, collect essential documents, confirm version control, and identify missing items.',
            ],
            'credentialing' => [
                'title' => 'CV & Credential Packet Management',
                'summary' => 'Prepare sponsor-ready staff CVs, licenses, certifications, and training packets.',
                'tools' => ['CV Template', 'License tracker', 'GCP/HSP/HIPAA tracker', 'Delegation packet checklist'],
                'default_priority' => 'high',
                'default_notes' => 'Collect current CV, signed/date version when required, license or certification, GCP, HSP, HIPAA, delegation, and disclosure forms.',
            ],
            'irb_submission' => [
                'title' => 'IRB Submission Support',
                'summary' => 'Track submission packets, amendments, continuing reviews, consent versions, and reportable items.',
                'tools' => ['Initial submission checklist', 'Amendment tracker', 'Continuing review tracker', 'Consent version log'],
                'default_priority' => 'urgent',
                'default_notes' => 'Confirm required IRB materials, consent version, submission type, due date, and client-side approval owner.',
            ],
            'sop_library' => [
                'title' => 'SOP Creation & Maintenance',
                'summary' => 'Create, update, and track site SOPs for repeatable clinical research operations.',
                'tools' => ['Consent SOP', 'Source documentation SOP', 'Delegation SOP', 'AE/SAE reporting SOP'],
                'default_priority' => 'normal',
                'default_notes' => 'Define SOP scope, owner, review cycle, approval path, and implementation notes.',
            ],
            'source_documents' => [
                'title' => 'Source Document Templates',
                'summary' => 'Build visit worksheets and source tools that match protocol requirements.',
                'tools' => ['Screening source', 'Eligibility checklist', 'Visit worksheet', 'AE/SAE worksheet'],
                'default_priority' => 'normal',
                'default_notes' => 'List required visits, source forms, eligibility checks, and review/approval needs.',
            ],
            'training_delegation' => [
                'title' => 'Training & Delegation Logs',
                'summary' => 'Track staff role assignment, protocol training, delegation, and expiration dates.',
                'tools' => ['Delegation of authority log', 'Protocol training log', 'SIV attendance log', 'Role matrix'],
                'default_priority' => 'high',
                'default_notes' => 'Confirm staff roles, required training, missing dates, PI approval, and delegation scope.',
            ],
            'audit_readiness' => [
                'title' => 'Audit Readiness Review',
                'summary' => 'Review documents, consent versions, credentials, logs, and open findings before sponsor or regulatory inspection.',
                'tools' => ['Missing document review', 'Version mismatch review', 'Readiness checklist', 'Finding tracker'],
                'default_priority' => 'urgent',
                'default_notes' => 'Review binder completeness, consent versions, delegation, training, deviations, and open findings.',
            ],
            'deviation_capa' => [
                'title' => 'Protocol Deviation & CAPA Support',
                'summary' => 'Track deviations, root cause, corrective actions, preventive actions, and follow-up dates.',
                'tools' => ['Deviation log', 'Root-cause worksheet', 'CAPA tracker', 'Follow-up queue'],
                'default_priority' => 'urgent',
                'default_notes' => 'Capture event summary, root cause, corrective action, preventive action, reporting needs, and due dates.',
            ],
            'study_startup' => [
                'title' => 'Study Startup Support',
                'summary' => 'Coordinate feasibility, site qualification, startup documents, SIV readiness, and activation milestones.',
                'tools' => ['Feasibility checklist', 'SQV/SIV prep', 'Startup timeline', 'Activation tracker'],
                'default_priority' => 'high',
                'default_notes' => 'Track feasibility, startup packet, site qualification, SIV materials, and activation blockers.',
            ],
            'recruitment_compliance' => [
                'title' => 'Recruitment Compliance Support',
                'summary' => 'Manage approved recruitment materials, outreach scripts, referral logs, and prescreen documentation standards.',
                'tools' => ['IRB-approved flyer log', 'Recruitment script tracker', 'Referral source log', 'Prescreen note standard'],
                'default_priority' => 'normal',
                'default_notes' => 'Confirm recruitment materials, approval status, scripts, referral source tracking, and prescreen documentation rules.',
            ],
        ];
    }

    public function resources(): array
    {
        return [
            [
                'title' => 'Clinical Research Regulatory Services Menu',
                'type' => 'Service Menu / Workflow Guide',
                'path' => 'portfolio/templates/clinical-research-regulatory-services.md',
                'description' => 'Reusable operating menu for positioning, scoping, and tracking clinical research regulatory services across clients.',
            ],
            [
                'title' => 'CV Template',
                'type' => 'Staff CV / Credential Packet',
                'path' => 'portfolio/templates/cv-template.md',
                'description' => 'Reusable clinical research CV structure for investigators, sub-investigators, coordinators, regulatory specialists, recruitment specialists, and site operations staff.',
            ],
        ];
    }

    public function workflowSteps(): array
    {
        return [
            'Choose the service card that matches the client request.',
            'Start a regulatory record from the card, or create one manually.',
            'Use Client, Site, Sponsor, and Protocol to identify the work without tying it to recruitment candidates.',
            'Use missing documents, blocker, approval path, owner, due date, and next step to drive follow-up.',
            'Move status from Not Started to In Review, Needs Client Action, Ready, and Complete.',
        ];
    }

    public function summary(?string $organizationKey = null): array
    {
        $query = RegulatoryServiceRecord::query()
            ->when($organizationKey, fn ($query) => $query->where('organization_key', $organizationKey));
        $openStatuses = ['not_started', 'in_review', 'needs_client_action', 'ready', 'on_hold'];

        return [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->whereIn('status', $openStatuses)->count(),
            'needs_client_action' => (clone $query)->where('status', 'needs_client_action')->count(),
            'ready' => (clone $query)->where('status', 'ready')->count(),
            'complete' => (clone $query)->where('status', 'complete')->count(),
            'overdue' => (clone $query)
                ->whereIn('status', $openStatuses)
                ->whereDate('due_date', '<', now()->toDateString())
                ->count(),
            'due_soon' => (clone $query)
                ->whereIn('status', $openStatuses)
                ->whereBetween('due_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->count(),
            'status_counts' => (clone $query)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'category_counts' => (clone $query)
                ->selectRaw('service_category, count(*) as total')
                ->groupBy('service_category')
                ->pluck('total', 'service_category'),
            'recent_records' => (clone $query)->latest()->limit(10)->get(),
        ];
    }
}
