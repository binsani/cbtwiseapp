<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('paystack_reference', 100)->unique();
            $table->unsignedInteger('amount_kobo'); // amount in kobo (100 kobo = ₦1)
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->unsignedSmallInteger('plan_duration_days')->default(30);
            $table->string('plan_type', 20)->nullable(); // 'monthly', 'annual'
            $table->json('paystack_data')->nullable(); // raw Paystack webhook payload
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['paystack_reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
