<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remodeling_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status')->default('scheduled')->index();
            $table->integer('property_square_feet')->nullable();
            $table->integer('year_built')->nullable();
            $table->boolean('hoa')->default(false);
            $table->boolean('permit_likely_required')->default(false);
            $table->string('occupancy_status')->nullable();
            $table->text('measurements_notes')->nullable();
            $table->text('site_conditions')->nullable();
            $table->text('risks_or_concerns')->nullable();
            $table->text('customer_preferences')->nullable();
            $table->text('estimator_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
