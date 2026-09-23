<?php

namespace Tests\Feature;

use App\Models\Industry;
use App\Models\Lead;
use App\Models\User;
use App\Models\WorkflowAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadWorkflowActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_apply_workflow_action_endpoint(): void
    {
        $admin = User::factory()->platformAdmin()->create();
        $industry = Industry::create([
            'name' => 'Bond Agency Insurance',
            'slug' => 'bond-agency-insurance',
            'is_active' => true,
        ]);
        $lead = Lead::create([
            'industry_id' => $industry->id,
            'title' => 'Desert Bond Agency',
            'status' => 'new_agency',
            'stage' => 'intake',
            'priority' => 'high',
            'estimated_value' => 12000,
            'lead_score' => 70,
            'next_follow_up_at' => now()->subHour(),
            'sla_status' => 'warning',
        ]);
        $action = WorkflowAction::create([
            'industry_id' => $industry->id,
            'name' => 'Coverage Gap Found',
            'slug' => 'bond-agency-coverage-gap-found',
            'button_label' => 'Coverage Gap Found',
            'new_status' => 'coverage_gap_found',
            'new_stage' => 'active',
            'follow_up_hours' => 24,
            'activity_type' => 'qualification',
            'activity_note' => 'Coverage gap identified.',
            'task_template' => ['title' => 'Schedule coverage review', 'priority' => 'high'],
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->postJson(route('leads.workflow.apply', [$lead, $action]))
            ->assertOk()
            ->assertJsonPath('data.status', 'coverage_gap_found')
            ->assertJsonPath('data.stage', 'active');

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'coverage_gap_found',
            'stage' => 'active',
        ]);
        $this->assertDatabaseHas('lead_activities', [
            'lead_id' => $lead->id,
            'type' => 'qualification',
            'title' => 'Coverage Gap Found',
        ]);
        $this->assertDatabaseHas('lead_tasks', [
            'lead_id' => $lead->id,
            'title' => 'Schedule coverage review',
            'priority' => 'high',
        ]);
        $this->assertDatabaseHas('workflow_logs', [
            'lead_id' => $lead->id,
            'workflow_action_id' => $action->id,
            'user_id' => $admin->id,
        ]);
    }

    public function test_lead_detail_renders_applicable_workflow_action_button(): void
    {
        $admin = User::factory()->platformAdmin()->create();
        $industry = Industry::create([
            'name' => 'Bond Agency Insurance',
            'slug' => 'bond-agency-insurance',
            'is_active' => true,
        ]);
        $lead = Lead::create([
            'industry_id' => $industry->id,
            'title' => 'Summit Bond Group',
            'status' => 'new_agency',
            'stage' => 'intake',
            'priority' => 'normal',
        ]);
        $action = WorkflowAction::create([
            'industry_id' => $industry->id,
            'name' => 'Contacted',
            'slug' => 'bond-agency-contacted',
            'button_label' => 'Contacted',
            'new_status' => 'contacted',
            'new_stage' => 'active',
            'activity_type' => 'call',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('bond-agency.leads.show', $lead))
            ->assertOk()
            ->assertSee('Contacted')
            ->assertSee(route('leads.workflow.apply', [$lead, $action]), false);
    }
}
