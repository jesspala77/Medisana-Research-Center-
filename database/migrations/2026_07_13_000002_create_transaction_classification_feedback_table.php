<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_classification_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_transaction_id')->constrained()->cascadeOnDelete();
            $table->string('predicted_category')->nullable();
            $table->string('confirmed_category');
            $table->foreignId('confirmed_project_id')->nullable()->constrained('construction_projects')->nullOnDelete();
            $table->text('reviewer_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_classification_feedback');
    }
};
