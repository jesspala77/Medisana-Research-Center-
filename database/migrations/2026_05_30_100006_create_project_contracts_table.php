<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remodeling_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_proposal_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contract_number')->unique();
            $table->string('status')->default('draft')->index();
            $table->date('sent_at')->nullable();
            $table->date('signed_at')->nullable();
            $table->decimal('contract_amount', 12, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->date('deposit_due_date')->nullable();
            $table->date('deposit_received_date')->nullable();
            $table->boolean('financing_required')->default(false);
            $table->string('financing_company')->nullable();
            $table->string('financing_status')->nullable();
            $table->decimal('financing_amount', 12, 2)->nullable();
            $table->boolean('insurance_claim')->default(false);
            $table->string('insurance_company')->nullable();
            $table->string('claim_number')->nullable();
            $table->text('contract_terms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_contracts');
    }
};
