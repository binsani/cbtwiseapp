<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->text('option_e')->nullable();
            $table->enum('correct_option', ['a', 'b', 'c', 'd', 'e']);
            $table->text('explanation')->nullable();
            $table->enum('source', ['manual', 'aloc', 'csv', 'ai'])->default('manual');
            $table->char('dedupe_hash', 64)->unique(); // sha256 of first 60 normalized chars
            $table->unsignedInteger('times_served')->default(0);
            $table->unsignedInteger('times_correct')->default(0);
            $table->unsignedSmallInteger('reports_count')->default(0);
            $table->boolean('is_flagged')->default(false);
            $table->timestamps();

            // Performance indexes
            $table->index(['exam_id', 'subject_id', 'year']);
            $table->index(['exam_id', 'subject_id', 'is_flagged']);
            $table->index(['source']);
            $table->index(['is_flagged']);
            $table->index(['times_served']); // for weighted random fetch
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
