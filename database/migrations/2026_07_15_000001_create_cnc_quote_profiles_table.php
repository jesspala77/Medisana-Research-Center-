<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cnc_quote_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('part_name')->nullable();
            $table->string('material')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->string('process_type')->nullable();
            $table->text('tolerance_notes')->nullable();
            $table->string('surface_finish')->nullable();
            $table->decimal('target_unit_price', 12, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->string('cad_file_url')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cnc_quote_profiles');
    }
};