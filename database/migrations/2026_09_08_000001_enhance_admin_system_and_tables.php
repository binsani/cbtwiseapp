<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. System Settings Table
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('group')->default('general'); // general, exam, question, notification, payment
                $table->boolean('is_encrypted')->default(false);
                $table->timestamps();

                $table->index(['group', 'key']);
            });
        }

        // 2. Enhance Purchase Codes Table
        if (Schema::hasTable('purchase_codes')) {
            Schema::table('purchase_codes', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_codes', 'status')) {
                    $table->string('status', 20)->default('available')->after('plan_duration_days');
                }
                if (!Schema::hasColumn('purchase_codes', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('purchase_codes', 'disabled_at')) {
                    $table->timestamp('disabled_at')->nullable()->after('expires_at');
                }
                if (!Schema::hasColumn('purchase_codes', 'code_hash')) {
                    $table->string('code_hash', 64)->nullable()->after('code');
                }
            });
        }

        // 3. Enhance Subjects Table
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('subjects', 'target_question_count')) {
                    $table->unsignedInteger('target_question_count')->default(200)->after('sort_order');
                }
            });
        }

        // 4. Enhance Users Table with suspension tracking
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'is_suspended')) {
                    $table->boolean('is_suspended')->default(false)->after('plan');
                }
                if (!Schema::hasColumn('users', 'suspended_at')) {
                    $table->timestamp('suspended_at')->nullable()->after('is_suspended');
                }
            });
        }

        // 5. Enhance Admin Activity Log Table
        if (Schema::hasTable('admin_activity_log')) {
            Schema::table('admin_activity_log', function (Blueprint $table) {
                if (!Schema::hasColumn('admin_activity_log', 'user_agent')) {
                    $table->text('user_agent')->nullable()->after('ip_address');
                }
                if (!Schema::hasColumn('admin_activity_log', 'old_values')) {
                    $table->json('old_values')->nullable()->after('meta');
                }
                if (!Schema::hasColumn('admin_activity_log', 'new_values')) {
                    $table->json('new_values')->nullable()->after('old_values');
                }
            });
        }

        // 6. Enhance Contact Messages Table
        if (Schema::hasTable('contact_messages')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('contact_messages', 'status')) {
                    $table->string('status', 20)->default('new')->after('message'); // new, read, replied, spam, closed
                }
                if (!Schema::hasColumn('contact_messages', 'reply')) {
                    $table->text('reply')->nullable()->after('status');
                }
                if (!Schema::hasColumn('contact_messages', 'replied_at')) {
                    $table->timestamp('replied_at')->nullable()->after('reply');
                }
                if (!Schema::hasColumn('contact_messages', 'internal_notes')) {
                    $table->text('internal_notes')->nullable()->after('replied_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');

        if (Schema::hasTable('purchase_codes')) {
            Schema::table('purchase_codes', function (Blueprint $table) {
                $columns = array_filter(['status', 'expires_at', 'disabled_at', 'code_hash'], fn($col) => Schema::hasColumn('purchase_codes', $col));
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                if (Schema::hasColumn('subjects', 'target_question_count')) {
                    $table->dropColumn('target_question_count');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columns = array_filter(['is_suspended', 'suspended_at'], fn($col) => Schema::hasColumn('users', $col));
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('admin_activity_log')) {
            Schema::table('admin_activity_log', function (Blueprint $table) {
                $columns = array_filter(['user_agent', 'old_values', 'new_values'], fn($col) => Schema::hasColumn('admin_activity_log', $col));
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
