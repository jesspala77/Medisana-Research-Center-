<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('organization_key')->nullable()->after('campaign_id')->index();
        });

        Schema::table('regulatory_service_records', function (Blueprint $table) {
            $table->string('organization_key')->nullable()->after('id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('leads', fn (Blueprint $table) => $table->dropColumn('organization_key'));
        Schema::table('regulatory_service_records', fn (Blueprint $table) => $table->dropColumn('organization_key'));
    }
};
