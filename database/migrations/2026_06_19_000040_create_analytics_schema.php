<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Analytics fact tables (append-only, no FKs for performance)
        Schema::create('analytics_fact_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('exam_id');
            $table->boolean('is_correct');
            $table->unsignedSmallInteger('time_spent_secs')->nullable();
            $table->date('created_date'); // partition key
            $table->timestamps();
            $table->index(['user_id', 'created_date']);
            $table->index(['subject_id', 'created_date']);
            $table->index(['exam_id', 'created_date']);
        });

        Schema::create('analytics_fact_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exam_id');
            $table->unsignedSmallInteger('score')->default(0);
            $table->unsignedSmallInteger('duration_secs')->nullable();
            $table->unsignedSmallInteger('questions_count')->default(0);
            $table->date('created_date');
            $table->timestamps();
            $table->index(['user_id', 'created_date']);
        });

        Schema::create('analytics_fact_revenue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount_ngn', 10, 2);
            $table->string('plan')->nullable();
            $table->string('source')->nullable(); // paystack, purchase_code, school
            $table->date('created_date');
            $table->timestamps();
            $table->index('created_date');
        });

        Schema::create('analytics_dim_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('plan')->nullable();
            $table->string('state')->nullable();
            $table->string('exam_year')->nullable();
            $table->timestamp('first_exam_at')->nullable();
            $table->timestamp('first_paid_at')->nullable();
            $table->date('snapshot_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_dim_user');
        Schema::dropIfExists('analytics_fact_revenue');
        Schema::dropIfExists('analytics_fact_sessions');
        Schema::dropIfExists('analytics_fact_attempts');
    }
};
