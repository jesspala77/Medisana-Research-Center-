<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estimate_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_estimate_id')->constrained()->cascadeOnDelete();
            $table->string('category')->nullable();
            $table->string('description');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit')->default('each');
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('labor_hours', 10, 2)->default(0);
            $table->decimal('labor_rate', 12, 2)->default(0);
            $table->decimal('markup_percent', 5, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimate_line_items');
    }
};
