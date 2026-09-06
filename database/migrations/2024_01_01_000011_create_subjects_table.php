<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 60);
            $table->string('name', 100);
            $table->string('icon', 50)->nullable(); // emoji or icon key
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['exam_id', 'slug']);
            $table->index(['exam_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
