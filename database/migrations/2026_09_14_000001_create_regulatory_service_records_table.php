<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulatory_service_records', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('site_name')->nullable();
            $table->string('service_category');
            $table->string('request_title');
            $table->string('sponsor')->nullable();
            $table->string('protocol')->nullable();
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_email')->nullable();
            $table->string('primary_contact_phone')->nullable();
            $table->string('regulatory_owner')->nullable();
            $table->string('status')->default('not_started');
            $table->string('priority')->default('normal');
            $table->date('due_date')->nullable();
            $table->text('current_blocker')->nullable();
            $table->text('documents_available')->nullable();
            $table->text('missing_documents')->nullable();
            $table->text('approval_path')->nullable();
            $table->string('next_step')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index(['service_category', 'due_date']);
            $table->index('client_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulatory_service_records');
    }
};
