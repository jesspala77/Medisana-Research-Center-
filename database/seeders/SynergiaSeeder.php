<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\LeadRequirement;
use App\Models\WorkflowAction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SynergiaSeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            ['name' => 'Clinical Research', 'slug' => 'clinical-research'],
            ['name' => 'Bail Bonds', 'slug' => 'bail-bonds'],
            ['name' => 'Bond Agency Insurance', 'slug' => 'bond-agency-insurance'],
            ['name' => 'Business Capital Funding', 'slug' => 'business-capital-funding'],
            ['name' => 'Construction / Renovation', 'slug' => 'construction-renovation'],
            ['name' => 'CNC Machining Services', 'slug' => 'cnc-machining-services'],
        ];

        foreach ($industries as $industry) {
            Industry::updateOrCreate(['slug' => $industry['slug']], $industry);
        }

        $requirements = [
            'clinical-research' => [
                ['field_key' => 'dob', 'label' => 'DOB'],
                ['field_key' => 'phone', 'label' => 'Phone'],
                ['field_key' => 'condition_interest', 'label' => 'Condition / Protocol Interest'],
                ['field_key' => 'prescreen_notes', 'label' => 'Prescreen Notes'],
            ],
            'bail-bonds' => [
                ['field_key' => 'contact_name', 'label' => 'Contact Name'],
                ['field_key' => 'phone', 'label' => 'Phone'],
                ['field_key' => 'county', 'label' => 'County'],
                ['field_key' => 'coverage_gap', 'label' => 'Coverage Gap / Need'],
            ],
            'bond-agency-insurance' => [
                ['field_key' => 'contact_name', 'label' => 'Contact Name'],
                ['field_key' => 'phone', 'label' => 'Phone'],
                ['field_key' => 'county', 'label' => 'County'],
                ['field_key' => 'coverage_gap', 'label' => 'Coverage Gap / Need'],
            ],
            'business-capital-funding' => [
                ['field_key' => 'monthly_revenue', 'label' => 'Monthly Revenue'],
                ['field_key' => 'requested_amount', 'label' => 'Requested Amount'],
                ['field_key' => 'time_in_business', 'label' => 'Time in Business'],
                ['field_key' => 'bank_statements', 'label' => 'Bank Statements'],
            ],
            'construction-renovation' => [
                ['field_key' => 'address', 'label' => 'Project Address'],
                ['field_key' => 'project_type', 'label' => 'Project Type'],
                ['field_key' => 'budget', 'label' => 'Budget'],
                ['field_key' => 'desired_start_date', 'label' => 'Desired Start Date'],
            ],
            'cnc-machining-services' => [
                ['field_key' => 'part_name', 'label' => 'Part Name'],
                ['field_key' => 'material', 'label' => 'Material'],
                ['field_key' => 'quantity', 'label' => 'Quantity'],
                ['field_key' => 'process_type', 'label' => 'Process Type'],
            ],
        ];

        foreach ($requirements as $slug => $items) {
            $industry = Industry::where('slug', $slug)->first();
            foreach ($items as $index => $item) {
                LeadRequirement::updateOrCreate(
                    ['industry_id' => $industry->id, 'field_key' => $item['field_key']],
                    [
                        'label' => $item['label'],
                        'is_required' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }

        $actions = [
            [
                'name' => 'Called - No Answer',
                'slug' => 'called-no-answer',
                'button_label' => 'Called - No Answer',
                'new_status' => 'contact_attempted',
                'new_stage' => 'follow_up',
                'follow_up_hours' => 24,
                'activity_type' => 'call',
                'activity_note' => 'Called lead. No answer. Follow-up scheduled.',
                'task_template' => ['title' => 'Follow up after no answer', 'priority' => 'normal'],
            ],
            [
                'name' => 'Qualified',
                'slug' => 'qualified',
                'button_label' => 'Qualified',
                'new_status' => 'qualified',
                'new_stage' => 'qualified',
                'follow_up_hours' => 24,
                'activity_type' => 'qualification',
                'activity_note' => 'Lead marked as qualified.',
                'task_template' => ['title' => 'Complete next qualification step', 'priority' => 'high'],
            ],
            [
                'name' => 'Needs Info',
                'slug' => 'needs-info',
                'button_label' => 'Needs Info',
                'new_status' => 'needs_info',
                'new_stage' => 'intake',
                'follow_up_hours' => 12,
                'activity_type' => 'missing_info',
                'activity_note' => 'Lead requires additional information.',
                'task_template' => ['title' => 'Collect missing information', 'priority' => 'high'],
            ],
            [
                'name' => 'Converted',
                'slug' => 'converted',
                'button_label' => 'Converted',
                'new_status' => 'converted',
                'new_stage' => 'closed',
                'follow_up_hours' => null,
                'activity_type' => 'conversion',
                'activity_note' => 'Lead converted.',
                'task_template' => null,
            ],
            [
                'name' => 'Not Interested',
                'slug' => 'not-interested',
                'button_label' => 'Not Interested',
                'new_status' => 'lost',
                'new_stage' => 'closed',
                'follow_up_hours' => null,
                'activity_type' => 'lost',
                'activity_note' => 'Lead marked not interested.',
                'task_template' => null,
            ],
        ];

        foreach ($actions as $action) {
            WorkflowAction::updateOrCreate(
                ['slug' => $action['slug']],
                $action
            );
        }

        $industryActions = [
            'business-capital-funding' => [
                ['name' => 'Contacted', 'slug' => 'capital-funding-contacted', 'button_label' => 'Contacted', 'new_status' => 'contacted', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'call', 'activity_note' => 'Funding lead contacted.'],
                ['name' => 'Docs Needed', 'slug' => 'capital-funding-docs-needed', 'button_label' => 'Docs Needed', 'new_status' => 'docs_needed', 'new_stage' => 'active', 'follow_up_hours' => 12, 'activity_type' => 'missing_info', 'activity_note' => 'Funding lead needs underwriting documents.', 'task_template' => ['title' => 'Collect funding documents', 'priority' => 'high']],
                ['name' => 'Offer Sent', 'slug' => 'capital-funding-offer-sent', 'button_label' => 'Offer Sent', 'new_status' => 'offer_sent', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'offer', 'activity_note' => 'Funding offer sent to lead.'],
                ['name' => 'Funded', 'slug' => 'capital-funding-funded', 'button_label' => 'Funded', 'new_status' => 'funded', 'new_stage' => 'closed', 'activity_type' => 'conversion', 'activity_note' => 'Funding lead funded.'],
                ['name' => 'Lost', 'slug' => 'capital-funding-lost', 'button_label' => 'Lost', 'new_status' => 'lost', 'new_stage' => 'closed', 'activity_type' => 'lost', 'activity_note' => 'Funding lead marked lost.'],
            ],
            'bond-agency-insurance' => [
                ['name' => 'Contacted', 'slug' => 'bond-agency-contacted', 'button_label' => 'Contacted', 'new_status' => 'contacted', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'call', 'activity_note' => 'Bond agency lead contacted.'],
                ['name' => 'Coverage Gap Found', 'slug' => 'bond-agency-coverage-gap-found', 'button_label' => 'Coverage Gap Found', 'new_status' => 'coverage_gap_found', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'qualification', 'activity_note' => 'Coverage gap identified.', 'task_template' => ['title' => 'Schedule coverage review', 'priority' => 'high']],
                ['name' => 'Review Scheduled', 'slug' => 'bond-agency-review-scheduled', 'button_label' => 'Review Scheduled', 'new_status' => 'review_scheduled', 'new_stage' => 'active', 'follow_up_hours' => 48, 'activity_type' => 'meeting', 'activity_note' => 'Coverage review scheduled.'],
                ['name' => 'Quote Sent', 'slug' => 'bond-agency-quote-sent', 'button_label' => 'Quote Sent', 'new_status' => 'quote_sent', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'quote', 'activity_note' => 'Quote sent to agency.'],
                ['name' => 'Bound', 'slug' => 'bond-agency-bound', 'button_label' => 'Bound', 'new_status' => 'bound', 'new_stage' => 'closed', 'activity_type' => 'conversion', 'activity_note' => 'Bond agency policy bound.'],
                ['name' => 'Lost', 'slug' => 'bond-agency-lost', 'button_label' => 'Lost', 'new_status' => 'lost', 'new_stage' => 'closed', 'activity_type' => 'lost', 'activity_note' => 'Bond agency lead marked lost.'],
            ],
            'clinical-research' => [
                ['name' => 'Contacted', 'slug' => 'clinical-contacted', 'button_label' => 'Contacted', 'new_status' => 'contacted', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'call', 'activity_note' => 'Candidate contacted.'],
                ['name' => 'Prescreening', 'slug' => 'clinical-prescreening', 'button_label' => 'Prescreening', 'new_status' => 'prescreening', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'prescreen', 'activity_note' => 'Candidate moved to prescreening.', 'task_template' => ['title' => 'Complete prescreen review', 'priority' => 'high']],
                ['name' => 'Qualified', 'slug' => 'clinical-qualified', 'button_label' => 'Qualified', 'new_status' => 'qualified', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'qualification', 'activity_note' => 'Candidate qualified.'],
                ['name' => 'Visit Scheduled', 'slug' => 'clinical-visit-scheduled', 'button_label' => 'Visit Scheduled', 'new_status' => 'visit_scheduled', 'new_stage' => 'active', 'follow_up_hours' => 48, 'activity_type' => 'schedule', 'activity_note' => 'Screening visit scheduled.'],
                ['name' => 'Enrolled', 'slug' => 'clinical-enrolled', 'button_label' => 'Enrolled', 'new_status' => 'enrolled', 'new_stage' => 'closed', 'activity_type' => 'conversion', 'activity_note' => 'Candidate enrolled.'],
                ['name' => 'Screen Failed', 'slug' => 'clinical-screen-failed', 'button_label' => 'Screen Failed', 'new_status' => 'screen_failed', 'new_stage' => 'closed', 'activity_type' => 'lost', 'activity_note' => 'Candidate screen failed.'],
            ],
            'cnc-machining-services' => [
                ['name' => 'RFQ Review', 'slug' => 'cnc-rfq-review', 'button_label' => 'RFQ Review', 'new_status' => 'rfq_review', 'new_stage' => 'active', 'follow_up_hours' => 12, 'activity_type' => 'review', 'activity_note' => 'CNC RFQ moved to review.', 'task_template' => ['title' => 'Review RFQ details', 'priority' => 'high']],
                ['name' => 'Quoted', 'slug' => 'cnc-quoted', 'button_label' => 'Quoted', 'new_status' => 'quoted', 'new_stage' => 'active', 'follow_up_hours' => 24, 'activity_type' => 'quote', 'activity_note' => 'CNC quote sent.'],
                ['name' => 'Quote Accepted', 'slug' => 'cnc-quote-accepted', 'button_label' => 'Quote Accepted', 'new_status' => 'quote_accepted', 'new_stage' => 'closed', 'activity_type' => 'conversion', 'activity_note' => 'CNC quote accepted.'],
                ['name' => 'In Production', 'slug' => 'cnc-in-production', 'button_label' => 'In Production', 'new_status' => 'in_production', 'new_stage' => 'active', 'follow_up_hours' => 48, 'activity_type' => 'production', 'activity_note' => 'CNC job moved into production.'],
                ['name' => 'Completed', 'slug' => 'cnc-completed', 'button_label' => 'Completed', 'new_status' => 'completed', 'new_stage' => 'closed', 'activity_type' => 'conversion', 'activity_note' => 'CNC job completed.'],
                ['name' => 'Lost', 'slug' => 'cnc-lost', 'button_label' => 'Lost', 'new_status' => 'lost', 'new_stage' => 'closed', 'activity_type' => 'lost', 'activity_note' => 'CNC quote lead marked lost.'],
            ],
        ];

        foreach ($industryActions as $industrySlug => $actions) {
            $industry = Industry::where('slug', $industrySlug)->first();

            foreach ($actions as $action) {
                WorkflowAction::updateOrCreate(
                    ['slug' => $action['slug']],
                    [
                        'industry_id' => $industry->id,
                        ...$action,
                    ]
                );
            }
        }

        DB::table('sla_rules')->updateOrInsert(
            ['name' => 'Default 60 Minute First Response'],
            [
                'industry_id' => null,
                'trigger_status' => 'new',
                'first_response_minutes' => 60,
                'follow_up_hours' => 24,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
