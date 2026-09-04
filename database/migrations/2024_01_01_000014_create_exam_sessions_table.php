<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->enum('mode', ['practice', 'mock', 'study'])->default('practice');
            $table->json('subjects'); // array of subject IDs selected
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedSmallInteger('total_questions')->default(0);
            $table->unsignedInteger('duration_seconds')->default(10800); // 3 hours default
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('score', 8, 2)->nullable(); // raw score
            $table->unsignedSmallInteger('correct_count')->default(0);
            $table->enum('status', ['in_progress', 'submitted', 'abandoned'])->default('in_progress');
            $table->json('score_breakdown')->nullable(); // per-subject breakdown
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
