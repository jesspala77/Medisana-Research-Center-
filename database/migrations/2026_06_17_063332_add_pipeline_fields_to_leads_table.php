<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('surety_pipeline')->nullable()->after('stage');
            $table->string('palmetto_interest_level')->nullable()->after('surety_pipeline');
            $table->decimal('projected_monthly_revenue', 12, 2)->nullable()->after('estimated_value');
            $table->decimal('projected_annual_revenue', 12, 2)->nullable()->after('projected_monthly_revenue');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'surety_pipeline',
                'palmetto_interest_level',
                'projected_monthly_revenue',
                'projected_annual_revenue',
            ]);
        });
    }
};