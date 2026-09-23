<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('trigger_status')->nullable();
            $table->integer('first_response_minutes')->default(60);
            $table->integer('follow_up_hours')->default(24);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sla_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('event_type');
            $table->timestamp('due_at');
            $table->timestamp('completed_at')->nullable();
            $table->string('status')->default('open')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_events');
        Schema::dropIfExists('sla_rules');
    }
};
