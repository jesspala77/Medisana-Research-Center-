<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_outreach_messages', function (Blueprint $table) {
            $table->foreignId('lead_id')->nullable()->after('id');

            $table->string('agency_name')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->nullable();

            $table->string('subject')->nullable();
            $table->longText('message_body')->nullable();

            $table->string('status')->default('draft');

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('opted_out_at')->nullable();

            $table->json('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ai_outreach_messages', function (Blueprint $table) {
            $table->dropColumn([
                'lead_id',
                'agency_name',
                'recipient_name',
                'recipient_email',
                'subject',
                'message_body',
                'status',
                'approved_at',
                'sent_at',
                'replied_at',
                'opted_out_at',
                'metadata',
            ]);
        });
    }
};