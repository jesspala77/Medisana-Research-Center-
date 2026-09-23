<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_vendor_rules', function (Blueprint $table) {
            $table->id();
            $table->string('normalized_vendor')->index();
            $table->string('match_type')->default('contains');
            $table->string('match_value')->index();
            $table->string('category')->index();
            $table->unsignedBigInteger('construction_project_id')->nullable()->index();
            $table->decimal('confidence', 5, 4)->default(0.95);
            $table->unsignedInteger('times_confirmed')->default(1);
            $table->boolean('active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['match_type', 'match_value', 'category'], 'txn_vendor_rule_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_vendor_rules');
    }
};
