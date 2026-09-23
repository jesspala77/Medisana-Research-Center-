<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remodeling_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_estimate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('proposal_number')->unique();
            $table->string('status')->default('draft')->index();
            $table->date('sent_at')->nullable();
            $table->date('viewed_at')->nullable();
            $table->date('follow_up_due_at')->nullable();
            $table->date('accepted_at')->nullable();
            $table->date('rejected_at')->nullable();
            $table->decimal('proposal_amount', 12, 2)->default(0);
            $table->decimal('counteroffer_amount', 12, 2)->nullable();
            $table->string('primary_objection')->nullable();
            $table->text('negotiation_notes')->nullable();
            $table->text('proposal_terms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_proposals');
    }
};
