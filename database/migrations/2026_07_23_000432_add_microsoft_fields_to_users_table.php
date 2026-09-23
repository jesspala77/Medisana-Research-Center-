<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('microsoft_id')->nullable()->unique();
            $table->text('microsoft_access_token')->nullable();
            $table->text('microsoft_refresh_token')->nullable();
            $table->timestamp('microsoft_token_expires_at')->nullable();
            $table->string('microsoft_email')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'microsoft_id',
                'microsoft_access_token',
                'microsoft_refresh_token',
                'microsoft_token_expires_at',
                'microsoft_email',
            ]);
        });
    }
};