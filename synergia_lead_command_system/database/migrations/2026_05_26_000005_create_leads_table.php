<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('status')->default('new')->index();
            $table->string('stage')->default('intake')->index();
            $table->string('priority')->default('normal');
            $table->decimal('estimated_value', 12, 2)->default(0);
            $table->integer('lead_score')->default(0);
            $table->integer('quality_score')->default(0);
            $table->integer('urgency_score')->default(0);
            $table->integer('fit_score')->default(0);
            $table->integer('completeness_score')->default(0);

            $table->timestamp('first_response_due_at')->nullable();
            $table->timestamp('next_follow_up_at')->nullable()->index();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamp('lost_at')->nullable();

            $table->string('sla_status')->default('healthy')->index();
            $table->string('next_best_action')->nullable();
            $table->text('summary')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'stage']);
            $table->index(['sla_status', 'next_follow_up_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
