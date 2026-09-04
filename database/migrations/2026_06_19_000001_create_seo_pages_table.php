<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. jamb-mathematics-2023
            $table->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('title', 160);
            $table->string('meta_description', 160)->nullable();
            $table->string('h1', 200);
            $table->longText('body_md')->nullable();      // AI-written intro (Markdown)
            $table->json('schema_json')->nullable();      // FAQPage / Course / BreadcrumbList JSON-LD
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamp('indexed_at')->nullable();  // When pinged Google Indexing API
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['exam_id', 'subject_id', 'year']);
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
