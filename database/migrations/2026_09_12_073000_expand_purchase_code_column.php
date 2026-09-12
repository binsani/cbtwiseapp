<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * CBT-XXXX-XXXX-XXXX is 18 characters. The legacy CHAR(12) column
     * truncated correctly generated codes on MySQL in strict mode.
     */
    public function up(): void
    {
        Schema::table('purchase_codes', function (Blueprint $table) {
            $table->string('code', 24)->change();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_codes', function (Blueprint $table) {
            $table->string('code', 12)->change();
        });
    }
};
