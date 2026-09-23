<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funding_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('monthly_revenue', 12, 2)->nullable();
            $table->decimal('requested_amount', 12, 2)->nullable();
            $table->string('funding_purpose')->nullable();
            $table->string('time_in_business')->nullable();
            $table->string('credit_score_range')->nullable();
            $table->boolean('bank_statements_uploaded')->default(false);
            $table->string('approval_status')->nullable();
            $table->decimal('funded_amount', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('construction_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('project_type')->nullable();
            $table->string('property_type')->nullable();
            $table->string('address')->nullable();
            $table->integer('square_footage')->nullable();
            $table->decimal('estimated_budget', 12, 2)->nullable();
            $table->boolean('financing_needed')->default(false);
            $table->boolean('permit_required')->default(false);
            $table->string('contractor_assigned')->nullable();
            $table->string('project_status')->default('intake');
            $table->date('desired_start_date')->nullable();
            $table->timestamps();
        });

        Schema::create('clinical_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('dob')->nullable();
            $table->string('condition_interest')->nullable();
            $table->string('protocol_interest')->nullable();
            $table->text('prescreen_notes')->nullable();
            $table->string('prescreen_status')->nullable();
            $table->date('screening_date')->nullable();
            $table->timestamps();
        });

        Schema::create('bond_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('county')->nullable();
            $table->string('jail_facility')->nullable();
            $table->decimal('bond_amount', 12, 2)->nullable();
            $table->string('defendant_name')->nullable();
            $table->string('case_number')->nullable();
            $table->string('attorney_name')->nullable();
            $table->string('urgency_level')->nullable();
            $table->string('referral_partner')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bond_profiles');
        Schema::dropIfExists('clinical_profiles');
        Schema::dropIfExists('construction_projects');
        Schema::dropIfExists('funding_profiles');
    }
};
