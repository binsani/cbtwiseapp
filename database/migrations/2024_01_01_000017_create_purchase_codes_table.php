<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_codes', function (Blueprint $table) {
            $table->id();
            $table->char('code', 12)->unique(); // format: CBT-XXXXXXXX
            $table->unsignedSmallInteger('plan_duration_days')->default(30);
            $table->foreignId('used_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->foreignId('created_by_admin_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['code']);
            $table->index(['used_by_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_codes');
    }
};
