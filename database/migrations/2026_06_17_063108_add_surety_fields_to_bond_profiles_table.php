<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bond_profiles', function (Blueprint $table) {
            $table->string('current_surety')->nullable();
            $table->decimal('current_rate', 8, 4)->nullable();
            $table->decimal('palmetto_rate', 8, 4)->nullable();
            $table->boolean('palmetto_eligible')->default(false);
            $table->boolean('additional_coverage_needed')->default(false);
            $table->decimal('estimated_savings', 12, 2)->nullable();

            $table->boolean('has_epowers')->default(false);
            $table->boolean('epower_interest')->default(false);
            $table->string('epower_status')->nullable();
            $table->date('epower_activation_date')->nullable();

            $table->string('southernmost_status')->nullable();
            $table->string('palmetto_stage')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bond_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'current_surety',
                'current_rate',
                'palmetto_rate',
                'palmetto_eligible',
                'additional_coverage_needed',
                'estimated_savings',
                'has_epowers',
                'epower_interest',
                'epower_status',
                'epower_activation_date',
                'southernmost_status',
                'palmetto_stage',
            ]);
        });
    }
};