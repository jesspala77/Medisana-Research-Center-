<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outreach_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ai_outreach_message_id')->nullable()->constrained()->nullOnDelete();

            $table->string('event_type');
            // generated, approved, sent, opened, clicked, replied, bounced, opted_out

            $table->string('provider')->nullable();
            // microsoft, manual, tracking_pixel

            $table->string('provider_message_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url_clicked')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outreach_events');
    }
};