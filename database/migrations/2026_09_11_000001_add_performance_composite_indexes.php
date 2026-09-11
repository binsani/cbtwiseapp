<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->index(['subject_id', 'topic_id', 'is_flagged'], 'idx_questions_subject_topic_flagged');
            $table->index(['subject_id', 'is_flagged', 'year'], 'idx_questions_subject_flagged_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex('idx_questions_subject_topic_flagged');
            $table->dropIndex('idx_questions_subject_flagged_year');
        });
    }
};
