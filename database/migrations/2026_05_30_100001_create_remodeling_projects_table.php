<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remodeling_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('project_number')->unique();
            $table->string('project_name');
            $table->string('stage')->default('new_lead')->index();
            $table->string('status')->default('active')->index();
            $table->string('lead_source')->nullable();
            $table->string('property_type')->nullable();
            $table->string('project_category')->nullable();
            $table->string('urgency_level')->nullable();
            $table->string('property_address')->nullable();
            $table->string('property_city')->nullable();
            $table->string('property_state')->nullable();
            $table->string('property_zip')->nullable();
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->decimal('accepted_budget', 12, 2)->nullable();
            $table->date('desired_start_date')->nullable();
            $table->date('target_completion_date')->nullable();
            $table->integer('probability_to_close')->default(0);
            $table->integer('project_score')->default(0);
            $table->text('scope_summary')->nullable();
            $table->text('customer_objectives')->nullable();
            $table->text('internal_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remodeling_projects');
    }
};
