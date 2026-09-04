<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Bookmarks ─────────────────────────────────────────────────────────
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'question_id']);
        });

        // ── User In-App Notifications ─────────────────────────────────────────
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('info'); // info, success, warning, streak, achievement
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->string('action_url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'read_at']);
        });

        // ── Blog Categories ───────────────────────────────────────────────────
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // ── Blog Posts ────────────────────────────────────────────────────────
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body'); // Markdown stored, rendered on output
            $table->string('featured_image')->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->unsignedSmallInteger('reading_time')->default(1); // minutes
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['is_published', 'published_at']);
        });

        // ── FAQ Items ─────────────────────────────────────────────────────────
        Schema::create('faq_items', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_schema_visible')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Static CMS Pages ──────────────────────────────────────────────────
        Schema::create('static_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // about, terms, privacy, refund-policy
            $table->string('title');
            $table->longText('body'); // HTML or Markdown
            $table->string('meta_description', 160)->nullable();
            $table->timestamps();
        });

        // ── Coupons ───────────────────────────────────────────────────────────
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 8, 2); // % or NGN
            $table->unsignedInteger('usage_limit')->default(1);
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Referrals ─────────────────────────────────────────────────────────
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();
            $table->unique('referred_user_id'); // each user can only be referred once
        });

        // ── App Settings (key-value store) ─────────────────────────────────
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // ── Alter: users — add missing columns ────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 12)->unique()->nullable()->after('avatar');
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete()->after('referral_code');
            $table->unsignedTinyInteger('streak_freeze_tokens')->default(0)->after('study_streak_days');
            $table->softDeletes()->after('updated_at');
        });

        // ── Alter: exam_sessions — add autosave and anti-cheat columns ────────
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->json('auto_saved_answers')->nullable()->after('score_breakdown');
            $table->unsignedSmallInteger('tab_switches')->default(0)->after('auto_saved_answers');
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropColumn(['auto_saved_answers', 'tab_switches']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by', 'streak_freeze_tokens']);
        });
        Schema::dropIfExists('app_settings');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('static_pages');
        Schema::dropIfExists('faq_items');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('bookmarks');
    }
};
