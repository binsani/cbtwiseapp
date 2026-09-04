<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->decimal('balance_ngn', 12, 2)->default(0);
            $table->decimal('total_earned_ngn', 12, 2)->default(0);
            $table->string('bank_code')->nullable();
            $table->string('account_number', 20)->nullable();
            $table->string('account_name')->nullable();
            $table->string('payout_method')->default('paystack');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('cookie_token', 64)->nullable();
            $table->string('referral_code', 20);
            $table->string('landing_url')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('clicked_at');
            $table->timestamps();
            $table->index(['affiliate_id', 'clicked_at']);
            $table->index('cookie_token');
        });

        Schema::create('affiliate_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referred_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('commission_ngn', 10, 2)->default(0);
            $table->unsignedTinyInteger('commission_rate')->default(20); // %
            $table->enum('status', ['pending', 'approved', 'paid', 'reversed'])->default('pending');
            $table->string('cookie_token', 64)->nullable();
            $table->timestamp('converted_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['affiliate_id', 'status']);
        });

        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_ngn', 12, 2);
            $table->string('paystack_reference')->nullable()->unique();
            $table->string('paystack_transfer_code')->nullable();
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->date('batch_date')->nullable(); // e.g. 2026-07-01 for monthly batch
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['affiliate_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_payouts');
        Schema::dropIfExists('affiliate_conversions');
        Schema::dropIfExists('affiliate_clicks');
        Schema::dropIfExists('affiliates');
    }
};
