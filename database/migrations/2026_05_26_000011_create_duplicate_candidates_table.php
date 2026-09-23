<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duplicate_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('possible_duplicate_lead_id')->constrained('leads')->cascadeOnDelete();
            $table->integer('confidence_score')->default(0);
            $table->json('matching_reasons')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['lead_id', 'possible_duplicate_lead_id'], 'dup_pair_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duplicate_candidates');
    }
};
