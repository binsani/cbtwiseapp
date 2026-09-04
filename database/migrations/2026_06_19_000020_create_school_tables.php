<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('subdomain')->nullable()->unique(); // for white-label
            $table->string('custom_email_sender')->nullable();
            $table->enum('tier', ['starter', 'growth', 'pro', 'enterprise'])->default('starter');
            $table->unsignedSmallInteger('seat_limit')->default(50);
            $table->unsignedSmallInteger('seats_used')->default(0);
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'suspended', 'expired'])->default('pending');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('school_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'user_id']);
            $table->index(['school_id', 'role']);
        });

        Schema::create('school_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->enum('role', ['teacher', 'student'])->default('student');
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->index(['school_id', 'email']);
        });

        Schema::create('school_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->unsignedSmallInteger('question_count')->default(40);
            $table->unsignedSmallInteger('time_limit_mins')->default(60);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('allow_retake')->default(false);
            $table->boolean('show_answers_after')->default(true);
            $table->json('year_filter')->nullable(); // which years to pull Qs from
            $table->timestamps();
            $table->index(['school_id', 'end_at']);
        });

        Schema::create('school_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('school_assignments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_id')->nullable()->constrained('exam_sessions')->nullOnDelete();
            $table->unsignedSmallInteger('score')->default(0);         // 0-100
            $table->unsignedSmallInteger('correct_count')->default(0);
            $table->unsignedSmallInteger('total_questions')->default(0);
            $table->unsignedSmallInteger('rank')->nullable();
            $table->unsignedInteger('time_taken_secs')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->index(['assignment_id', 'score']);
        });

        Schema::create('school_parent_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('parent_email');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_parent_links');
        Schema::dropIfExists('school_results');
        Schema::dropIfExists('school_assignments');
        Schema::dropIfExists('school_invites');
        Schema::dropIfExists('school_members');
        Schema::dropIfExists('schools');
    }
};
