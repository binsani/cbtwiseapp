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
        Schema::table('purchase_codes', function (Blueprint $table) {
            $table->string('student_name')->nullable()->after('plan_duration_days');
            $table->string('notes')->nullable()->after('used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_codes', function (Blueprint $table) {
            $table->dropColumn(['student_name', 'notes']);
        });
    }
};
