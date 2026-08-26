<?php

use App\Enums\AttendanceStatus;
use App\Enums\TaskStatus;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Task;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('the dashboard includes attendance and task analytics', function () {
    $branch = Branch::factory()->create();
    $user = User::factory()->superAdmin()->create([
        'branch_id' => $branch->id,
    ]);

    Attendance::factory()->create([
        'user_id' => $user->id,
        'branch_id' => $branch->id,
        'status' => AttendanceStatus::Late,
        'late_minutes' => 20,
        'work_hours' => 7.5,
        'check_out' => now(),
    ]);

    Task::factory()->assignedTo($user)->create([
        'created_by' => $user->id,
        'status' => TaskStatus::Completed,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('metrics.attendance_rate')
            ->has('metrics.task_completion_rate')
            ->where('metrics.late_today', 1)
            ->where('metrics.completed_tasks', 1)
        );
});

test('the json dashboard endpoint returns the same metrics payload', function () {
    $user = User::factory()->employee()->create();

    $this->actingAs($user)
        ->getJson(route('api.dashboard'))
        ->assertOk()
        ->assertJsonStructure([
            'metrics' => [
                'headcount',
                'present_today',
                'late_today',
                'attendance_rate',
                'total_tasks',
                'completed_tasks',
            ],
        ]);
});
