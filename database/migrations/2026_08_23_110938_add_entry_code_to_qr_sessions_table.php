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
        Schema::table('qr_sessions', function (Blueprint $table) {
            $table->string('entry_code', 8)->nullable()->after('token');
            $table->index(['entry_code', 'type', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_sessions', function (Blueprint $table) {
            $table->dropIndex(['entry_code', 'type', 'expires_at']);
            $table->dropColumn('entry_code');
        });
    }
};
