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
        Schema::table('attendance_days', function (Blueprint $table) {
            $table->boolean('check_in_is_open')->default(false)->after('check_out_ends_at');
            $table->boolean('check_out_is_open')->default(false)->after('check_in_is_open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_days', function (Blueprint $table) {
            $table->dropColumn(['check_in_is_open', 'check_out_is_open']);
        });
    }
};
