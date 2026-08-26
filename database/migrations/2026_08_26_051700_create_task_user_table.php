<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('task_user')) {
            Schema::create('task_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['task_id', 'user_id']);
            });
        }

        if (! Schema::hasColumn('tasks', 'assigned_to')) {
            return;
        }

        $now = now();

        $assigned = DB::table('tasks')
            ->whereNotNull('assigned_to')
            ->orderBy('id')
            ->get(['id', 'assigned_to']);

        foreach ($assigned as $task) {
            DB::table('task_user')->insertOrIgnore([
                'task_id' => $task->id,
                'user_id' => $task->assigned_to,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['assigned_to', 'status']);
            $table->dropColumn('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('tasks', 'assigned_to')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->foreignId('assigned_to')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                $table->index(['assigned_to', 'status']);
            });
        }

        $assignments = DB::table('task_user')
            ->select('task_id', 'user_id')
            ->orderBy('id')
            ->get()
            ->unique('task_id');

        foreach ($assignments as $assignment) {
            DB::table('tasks')
                ->where('id', $assignment->task_id)
                ->update(['assigned_to' => $assignment->user_id]);
        }

        Schema::dropIfExists('task_user');
    }
};
