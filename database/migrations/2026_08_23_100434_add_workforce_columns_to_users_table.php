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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('role', 32)->default('employee')->after('password');
            $table->foreignId('branch_id')->nullable()->after('role')->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->after('branch_id')->constrained()->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->after('department_id')->constrained()->nullOnDelete();
            $table->string('status', 32)->default('active')->after('shift_id');
            $table->uuid('device_uuid')->nullable()->after('status');

            $table->index('role');
            $table->index('status');
            $table->index('device_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
            $table->dropConstrainedForeignId('department_id');
            $table->dropConstrainedForeignId('shift_id');
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
            $table->dropIndex(['device_uuid']);
            $table->dropColumn(['phone', 'role', 'status', 'device_uuid']);
        });
    }
};
