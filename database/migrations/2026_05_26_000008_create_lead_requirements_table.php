<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_id')->constrained()->cascadeOnDelete();
            $table->string('field_key');
            $table->string('label');
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['industry_id', 'field_key']);
        });

        Schema::create('lead_requirement_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_requirement_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_complete')->default(false);
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['lead_id', 'lead_requirement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_requirement_statuses');
        Schema::dropIfExists('lead_requirements');
    }
};
