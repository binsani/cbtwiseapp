<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('topic_lessons', function (Blueprint $table) { $table->id(); $table->foreignId('topic_id')->unique()->constrained()->cascadeOnDelete(); $table->string('title', 180); $table->longText('lesson_notes')->nullable(); $table->text('worked_example_question')->nullable(); $table->longText('worked_example_solution')->nullable(); $table->enum('status', ['draft', 'published'])->default('draft'); $table->timestamps(); }); } public function down(): void { Schema::dropIfExists('topic_lessons'); } };
