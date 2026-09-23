<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bond_profiles', function (Blueprint $table) {
            $table->string('agency_type')->nullable()->after('referral_partner');
            $table->string('current_products')->nullable()->after('agency_type');
            $table->date('renewal_date')->nullable()->after('current_products');
            $table->text('coverage_gap')->nullable()->after('renewal_date');
            $table->date('review_date')->nullable()->after('coverage_gap');
            $table->decimal('quote_amount', 12, 2)->nullable()->after('review_date');
            $table->string('policy_status')->nullable()->after('quote_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bond_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'agency_type',
                'current_products',
                'renewal_date',
                'coverage_gap',
                'review_date',
                'quote_amount',
                'policy_status',
            ]);
        });
    }
};
