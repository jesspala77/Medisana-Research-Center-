<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remodeling_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remodeling_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('open')->index();
            $table->string('priority')->default('normal');
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('remodeling_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remodeling_project_id')->constrained()->cascadeOnDelete();
            $table->string('note_type')->default('general');
            $table->text('note');
            $table->timestamps();
        });
        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remodeling_project_id')->constrained()->cascadeOnDelete();
            $table->string('document_type')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('remodeling_notes');
        Schema::dropIfExists('remodeling_tasks');
    }
};
