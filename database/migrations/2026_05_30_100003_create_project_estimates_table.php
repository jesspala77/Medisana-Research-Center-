<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remodeling_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('estimate_number')->unique();
            $table->integer('version_number')->default(1);
            $table->string('status')->default('draft')->index();
            $table->date('estimate_date')->nullable();
            $table->date('sent_date')->nullable();
            $table->date('viewed_date')->nullable();
            $table->date('accepted_date')->nullable();
            $table->date('rejected_date')->nullable();
            $table->date('expires_at')->nullable();
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('material_cost', 12, 2)->default(0);
            $table->decimal('subcontractor_cost', 12, 2)->default(0);
            $table->decimal('overhead_cost', 12, 2)->default(0);
            $table->decimal('contingency_percent', 5, 2)->default(10);
            $table->decimal('markup_percent', 5, 2)->default(20);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('gross_profit_amount', 12, 2)->default(0);
            $table->decimal('gross_profit_percent', 5, 2)->default(0);
            $table->text('scope_of_work')->nullable();
            $table->text('exclusions')->nullable();
            $table->text('customer_objections')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_estimates');
    }
};
