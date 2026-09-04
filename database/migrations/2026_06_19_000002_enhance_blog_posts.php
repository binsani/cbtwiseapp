<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('meta_description');
            $table->text('author_bio')->nullable()->after('author_id');
            $table->json('toc_json')->nullable()->after('body');          // Table of Contents
            $table->boolean('newsletter_cta_enabled')->default(false)->after('is_published');
            $table->unsignedInteger('view_count')->default(0)->after('reading_time');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['tags', 'author_bio', 'toc_json', 'newsletter_cta_enabled', 'view_count']);
        });
    }
};
