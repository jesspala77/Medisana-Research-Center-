<?php

namespace Tests\Feature;

use App\Models\ConstructionProject;
use App\Models\FinancialTransaction;
use App\Models\Industry;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TransactionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_import_api_is_registered_and_dispatches_classification(): void
    {
        $user = User::factory()->create();
        $csv = implode("\n", [
            'id,date,merchant,description,amount,category',
            'tx-1,2026-07-10,Home Depot,Tile and grout,123.45,Materials',
        ]);

        $this->actingAs($user)
            ->post('/api/transactions/imports', [
                'file' => UploadedFile::fake()->createWithContent('transactions.csv', $csv),
                'source' => 'csv',
            ], ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonPath('rows_imported', 1);

        $this->assertDatabaseHas('financial_transactions', [
            'source' => 'csv',
            'external_id' => 'tx-1',
            'merchant' => 'Home Depot',
            'suggested_category' => 'direct_materials',
            'classification_status' => 'needs_review',
        ]);
    }

    public function test_transaction_approval_records_feedback_and_posts_direct_project_cost(): void
    {
        $user = User::factory()->create();
        $industry = Industry::create([
            'name' => 'Construction / Renovation',
            'slug' => 'construction-renovation',
            'is_active' => true,
        ]);
        $lead = Lead::create([
            'industry_id' => $industry->id,
            'title' => 'Kitchen Remodel',
            'status' => 'new',
            'stage' => 'intake',
        ]);
        $project = ConstructionProject::create([
            'lead_id' => $lead->id,
            'project_type' => 'Kitchen',
            'address' => '123 Main St',
            'estimated_budget' => 45000,
        ]);
        $transaction = FinancialTransaction::create([
            'source' => 'csv',
            'external_id' => 'tx-approve',
            'transaction_date' => '2026-07-11',
            'merchant' => 'Floor & Decor',
            'description' => 'Tile materials',
            'amount' => 400,
            'suggested_category' => 'uncategorized_review',
            'classification_status' => 'needs_review',
            'classification_confidence' => 0.72,
        ]);

        $this->actingAs($user)
            ->postJson('/api/transactions/'.$transaction->id.'/approve', [
                'category' => 'direct_materials',
                'construction_project_id' => $project->id,
                'learn_vendor_rule' => false,
                'notes' => 'Confirmed from receipt.',
            ])
            ->assertOk()
            ->assertJsonPath('classification_status', 'approved')
            ->assertJsonPath('suggested_category', 'direct_materials');

        $this->assertDatabaseHas('transaction_classification_feedback', [
            'financial_transaction_id' => $transaction->id,
            'predicted_category' => 'uncategorized_review',
            'confirmed_category' => 'direct_materials',
            'confirmed_project_id' => $project->id,
            'reviewer_note' => 'Confirmed from receipt.',
            'reviewed_by' => $user->id,
        ]);
        $this->assertDatabaseHas('construction_project_costs', [
            'financial_transaction_id' => $transaction->id,
            'construction_project_id' => $project->id,
            'cost_category' => 'direct_materials',
            'vendor' => 'Floor & Decor',
        ]);
    }

    public function test_transaction_review_page_renders_from_active_view_path(): void
    {
        $user = User::factory()->create();

        FinancialTransaction::create([
            'source' => 'csv',
            'external_id' => 'tx-review',
            'transaction_date' => '2026-07-12',
            'merchant' => 'Unknown Vendor',
            'description' => 'Needs review',
            'amount' => 75,
            'classification_status' => 'needs_review',
        ]);

        $this->actingAs($user)
            ->get('/finance/transactions/review')
            ->assertOk()
            ->assertSee('Transaction Review Queue')
            ->assertSee('Unknown Vendor');
    }
}
