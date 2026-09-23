<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('freshbooks');
            $table->string('external_id')->nullable()->index();
            $table->date('transaction_date')->nullable()->index();
            $table->string('merchant')->nullable()->index();
            $table->text('description')->nullable();
            $table->decimal('amount', 14, 2);
            $table->string('original_category')->nullable();
            $table->foreignId('construction_project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('suggested_category')->nullable()->index();
            $table->decimal('classification_confidence', 5, 4)->nullable();
            $table->string('classification_status')->default('pending')->index();
            $table->json('classification_reasons')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamp('classified_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
