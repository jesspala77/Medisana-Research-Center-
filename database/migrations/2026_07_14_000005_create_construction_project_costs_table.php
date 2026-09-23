<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('construction_project_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('construction_project_id')->index();
            $table->foreignId('financial_transaction_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('cost_date');
            $table->string('cost_category')->index();
            $table->string('vendor')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 14, 2);
            $table->string('source')->default('financial_transaction');
            $table->string('status')->default('posted');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('construction_project_costs');
    }
};
