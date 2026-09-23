<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_platform_admin')->default(false)->after('email_verified_at');
            $table->string('organization_key')->nullable()->after('is_platform_admin');
            $table->json('module_access')->nullable()->after('organization_key');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_platform_admin', 'organization_key', 'module_access']);
        });
    }
};