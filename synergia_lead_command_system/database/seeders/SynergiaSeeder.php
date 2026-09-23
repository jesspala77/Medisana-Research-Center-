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
            ['name' => 'Business Capital Funding', 'slug' => 'business-capital-funding'],
            ['name' => 'Construction / Renovation', 'slug' => 'construction-renovation'],
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
