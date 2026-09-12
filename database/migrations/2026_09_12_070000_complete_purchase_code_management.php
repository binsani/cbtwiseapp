<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_codes', function (Blueprint $table) {
            $table->unsignedInteger('duration_days')->nullable()->after('plan_duration_days');
            $table->string('assigned_name')->nullable()->after('student_name');
            $table->string('assigned_email')->nullable()->unique()->after('assigned_name');
            $table->text('assigned_password')->nullable()->after('assigned_email');
            $table->index(['status', 'assigned_email']);
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plan');
            $table->string('status', 20)->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('payment_reference')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index('ends_at');
        });

        Schema::create('purchase_code_redemption_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('code_fingerprint', 64)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('result', 30);
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_code_redemption_attempts');
        Schema::dropIfExists('subscriptions');
        Schema::table('purchase_codes', function (Blueprint $table) {
            $table->dropIndex(['status', 'assigned_email']);
            $table->dropUnique(['assigned_email']);
            $table->dropColumn(['duration_days', 'assigned_name', 'assigned_email', 'assigned_password']);
        });
    }
};
