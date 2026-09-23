<?php

namespace App\Services;

use App\Models\BondProfile;
use App\Models\ClinicalProfile;
use App\Models\CncQuoteProfile;
use App\Models\FundingProfile;
use App\Models\Industry;
use App\Models\Lead;

class LeadModuleService
{
    public function all(): array
    {
        return [
            'capital-funding' => [
                'title' => 'Capital Funding Leads',
                'short_title' => 'Capital Funding',
                'description' => 'Business funding requests, document collection, underwriting readiness, offers, and funded deals.',
                'prefix' => 'capital-funding',
                'route_name' => 'capital-funding',
                'industry' => ['name' => 'Business Capital Funding', 'slug' => 'business-capital-funding'],
                'profile_relation' => 'fundingProfile',
                'profile_model' => FundingProfile::class,
                'default_status' => 'new_request',
                'converted_status' => 'funded',
                'lost_status' => 'lost',
                'value_label' => 'Requested Pipeline',
                'create_button' => 'Create Funding Lead',
                'status_options' => [
                    'new_request' => 'New Request',
                    'contacted' => 'Contacted',
                    'docs_needed' => 'Docs Needed',
                    'underwriting_ready' => 'Underwriting Ready',
                    'offer_sent' => 'Offer Sent',
                    'funded' => 'Funded',
                    'lost' => 'Lost',
                ],
                'profile_fields' => [
                    ['name' => 'monthly_revenue', 'label' => 'Monthly Revenue', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'requested_amount', 'label' => 'Requested Amount', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'funding_purpose', 'label' => 'Funding Purpose', 'type' => 'text'],
                    ['name' => 'time_in_business', 'label' => 'Time in Business', 'type' => 'text'],
                    ['name' => 'credit_score_range', 'label' => 'Credit Score Range', 'type' => 'select', 'options' => ['', 'Under 580', '580-639', '640-699', '700+']],
                    ['name' => 'bank_statements_uploaded', 'label' => 'Bank Statements Uploaded', 'type' => 'checkbox'],
                    ['name' => 'approval_status', 'label' => 'Approval Status', 'type' => 'text'],
                    ['name' => 'funded_amount', 'label' => 'Funded Amount', 'type' => 'number', 'step' => '0.01'],
                ],
                'required_profile_fields' => ['requested_amount', 'funding_purpose'],
            ],
            'bond-agency' => [
                'title' => 'Bond Agency Insurance Leads',
                'short_title' => 'Bond Agency',
                'description' => 'Agency outreach, coverage gaps, review scheduling, quote follow-up, and bound policy tracking.',
                'prefix' => 'bond-agency',
                'route_name' => 'bond-agency',
                'industry' => ['name' => 'Bond Agency Insurance', 'slug' => 'bond-agency-insurance'],
                'profile_relation' => 'bondProfile',
                'profile_model' => BondProfile::class,
                'default_status' => 'new_agency',
                'converted_status' => 'bound',
                'lost_status' => 'lost',
                'value_label' => 'Quote Pipeline',
                'create_button' => 'Create Agency Lead',
                'status_options' => [
                    'new_agency' => 'New Agency',
                    'contacted' => 'Contacted',
                    'coverage_gap_found' => 'Coverage Gap Found',
                    'review_scheduled' => 'Review Scheduled',
                    'quote_sent' => 'Quote Sent',
                    'bound' => 'Bound',
                    'lost' => 'Lost',
                ],
                'profile_fields' => [
                    ['name' => 'agency_type', 'label' => 'Agency Type', 'type' => 'select', 'options' => ['', 'Bail bond agency', 'Independent agency', 'Commercial insurance agency', 'Referral partner']],
                    ['name' => 'county', 'label' => 'County / Territory', 'type' => 'text'],
                    ['name' => 'current_products', 'label' => 'Current Products', 'type' => 'text'],
                    ['name' => 'coverage_gap', 'label' => 'Coverage Gap', 'type' => 'textarea'],
                    ['name' => 'renewal_date', 'label' => 'Renewal Date', 'type' => 'date'],
                    ['name' => 'review_date', 'label' => 'Review Date', 'type' => 'date'],
                    ['name' => 'quote_amount', 'label' => 'Quote Amount', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'policy_status', 'label' => 'Policy Status', 'type' => 'text'],
                    ['name' => 'referral_partner', 'label' => 'Referral Partner', 'type' => 'text'],
                ],
                'required_profile_fields' => ['coverage_gap'],
            ],
            'clinical-recruitment' => [
                'title' => 'Clinical Trial Recruitment',
                'short_title' => 'Clinical Recruitment',
                'description' => 'Patient candidate intake, prescreening, protocol fit, visit scheduling, and enrollment tracking.',
                'prefix' => 'clinical-recruitment',
                'route_name' => 'clinical-recruitment',
                'industry' => ['name' => 'Clinical Research', 'slug' => 'clinical-research'],
                'profile_relation' => 'clinicalProfile',
                'profile_model' => ClinicalProfile::class,
                'default_status' => 'new_candidate',
                'converted_status' => 'enrolled',
                'lost_status' => 'screen_failed',
                'value_label' => 'Candidate Pipeline',
                'create_button' => 'Create Candidate',
                'status_options' => [
                    'new_candidate' => 'New Candidate',
                    'contacted' => 'Contacted',
                    'prescreening' => 'Prescreening',
                    'qualified' => 'Qualified',
                    'visit_scheduled' => 'Visit Scheduled',
                    'enrolled' => 'Enrolled',
                    'screen_failed' => 'Screen Failed',
                ],
                'profile_fields' => [
                    ['name' => 'dob', 'label' => 'Date of Birth', 'type' => 'date'],
                    ['name' => 'condition_interest', 'label' => 'Condition Interest', 'type' => 'text'],
                    ['name' => 'protocol_interest', 'label' => 'Protocol Interest', 'type' => 'text'],
                    ['name' => 'prescreen_status', 'label' => 'Prescreen Status', 'type' => 'select', 'options' => ['', 'Not started', 'In review', 'Likely fit', 'Not eligible']],
                    ['name' => 'screening_date', 'label' => 'Screening Date', 'type' => 'date'],
                    ['name' => 'prescreen_notes', 'label' => 'Prescreen Notes', 'type' => 'textarea'],
                ],
                'required_profile_fields' => ['dob', 'condition_interest'],
            ],
            'cnc-quote' => [
                'title' => 'CNC Quote Intake',
                'short_title' => 'CNC Quote',
                'description' => 'Immediate CNC machining quote requests, file intake, and rapid qualification for production-ready leads.',
                'prefix' => 'cnc-quote',
                'route_name' => 'cnc-quote',
                'industry' => ['name' => 'CNC Machining Services', 'slug' => 'cnc-machining-services'],
                'profile_relation' => 'cncQuoteProfile',
                'profile_model' => CncQuoteProfile::class,
                'default_status' => 'new_quote_request',
                'converted_status' => 'quote_accepted',
                'lost_status' => 'lost',
                'value_label' => 'Quote Pipeline',
                'create_button' => 'Create CNC Quote Lead',
                'status_options' => [
                    'new_quote_request' => 'New Quote Request',
                    'rfq_review' => 'RFQ Review',
                    'quoted' => 'Quoted',
                    'quote_accepted' => 'Quote Accepted',
                    'in_production' => 'In Production',
                    'completed' => 'Completed',
                    'lost' => 'Lost',
                ],
                'profile_fields' => [
                    ['name' => 'part_name', 'label' => 'Part Name', 'type' => 'text'],
                    ['name' => 'material', 'label' => 'Material', 'type' => 'text'],
                    ['name' => 'quantity', 'label' => 'Quantity', 'type' => 'number', 'step' => '1'],
                    ['name' => 'process_type', 'label' => 'Process Type', 'type' => 'select', 'options' => ['', 'CNC Milling', 'CNC Turning', 'Mill-Turn', 'Waterjet', 'Other']],
                    ['name' => 'tolerance_notes', 'label' => 'Tolerance Notes', 'type' => 'textarea'],
                    ['name' => 'surface_finish', 'label' => 'Surface Finish', 'type' => 'text'],
                    ['name' => 'target_unit_price', 'label' => 'Target Unit Price', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'due_date', 'label' => 'Due Date', 'type' => 'date'],
                    ['name' => 'cad_file_url', 'label' => 'CAD File URL', 'type' => 'text'],
                    ['name' => 'shipping_postal_code', 'label' => 'Shipping Postal Code', 'type' => 'text'],
                ],
                'required_profile_fields' => ['part_name', 'material', 'quantity', 'process_type'],
            ],
        ];
    }

    public function accessModules(): array
    {
        return [
            'remodeling' => [
                'title' => 'Construction & Remodeling',
                'short_title' => 'Construction',
                'description' => 'Remodeling projects, site visits, estimates, proposals, and contracts.',
                'route_name' => 'remodeling',
            ],
            ...$this->all(),
        ];
    }

    public function get(string $module): array
    {
        $modules = $this->all();

        abort_if(! isset($modules[$module]), 404);

        return $modules[$module] + ['key' => $module];
    }

    public function ensureIndustry(array $module): Industry
    {
        return Industry::updateOrCreate(
            ['slug' => $module['industry']['slug']],
            ['name' => $module['industry']['name'], 'is_active' => true]
        );
    }

    public function query(array $module, ?string $organizationKey = null)
    {
        return Lead::with(['industry', 'company', 'contact', $module['profile_relation']])
            ->when($organizationKey, fn ($query) => $query->where('organization_key', $organizationKey))
            ->whereHas('industry', function ($query) use ($module) {
                $query->where('slug', $module['industry']['slug']);
            });
    }

    public function summary(array $module, ?string $organizationKey = null): array
    {
        $query = $this->query($module, $organizationKey);
        $openStatuses = array_values(array_diff(array_keys($module['status_options']), [
            $module['converted_status'],
            $module['lost_status'],
        ]));

        return [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->whereIn('status', $openStatuses)->count(),
            'new' => (clone $query)->where('status', $module['default_status'])->count(),
            'won' => (clone $query)->where('status', $module['converted_status'])->count(),
            'pipeline_value' => (clone $query)->whereIn('status', $openStatuses)->sum('estimated_value'),
            'average_score' => round((clone $query)->avg('lead_score') ?? 0),
            'status_counts' => (clone $query)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'recent_leads' => (clone $query)->latest()->limit(10)->get(),
        ];
    }

    public function profileRules(array $module): array
    {
        $rules = [];

        foreach ($module['profile_fields'] as $field) {
            $rule = in_array($field['name'], $module['required_profile_fields'], true) ? 'required' : 'nullable';
            $rule .= match ($field['type']) {
                'number' => '|numeric',
                'date' => '|date',
                'checkbox' => '|boolean',
                default => '|string',
            };

            $rules['profile.'.$field['name']] = $rule;
        }

        return $rules;
    }

    public function profileData(array $module, array $validated): array
    {
        $profile = $validated['profile'] ?? [];

        foreach ($module['profile_fields'] as $field) {
            if ($field['type'] === 'checkbox') {
                $profile[$field['name']] = (bool) ($profile[$field['name']] ?? false);
            }
        }

        return collect($profile)
            ->only(collect($module['profile_fields'])->pluck('name')->all())
            ->all();
    }
}
