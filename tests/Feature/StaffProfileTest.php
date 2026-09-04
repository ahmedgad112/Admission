<?php

use App\Enums\AttendanceStatus;
use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('admins can open a staff profile with attendance and leave totals', function () {
    $this->travelTo('2026-09-04');

    $branch = Branch::factory()->create();
    $department = Department::factory()->create(['branch_id' => $branch->id]);
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);
    $employee = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'branch_id' => $branch->id,
        'department_id' => $department->id,
        'leave_days' => 21,
    ]);
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => '2026-08-20',
        'created_by' => $admin->id,
    ]);
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => '2026-08-21',
        'created_by' => $admin->id,
    ]);
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => '2026-08-22',
        'created_by' => $admin->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-20',
        'check_in' => '2026-08-20 09:05:00',
        'status' => AttendanceStatus::Present,
    ]);
    LeaveRequest::factory()->approved()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'department_id' => $department->id,
        'type' => LeaveRequestType::Permission,
        'start_date' => '2026-08-22',
        'end_date' => '2026-08-22',
        'status' => LeaveRequestStatus::Approved,
        'reviewed_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('staff.show', ['user' => $employee, 'month' => '2026-08']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Show')
            ->where('member.id', $employee->id)
            ->where('member.name', 'Mona Fathy')
            ->where('summary.month', '2026-08')
            ->where('summary.present_days', 1)
            ->where('summary.absent_days', 1)
            ->where('summary.permission_days', 1)
            ->has('summary.records', 1)
            ->has('summary.leaves', 1)
            ->where('canUpdate', true));
});

test('employees can open their own profile but not another staff profile', function () {
    $employee = User::factory()->employee()->create();
    $other = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('staff.show', $employee))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Show')
            ->where('member.id', $employee->id)
            ->where('canUpdate', false));

    $this->actingAs($employee)
        ->get(route('staff.show', $other))
        ->assertForbidden();
});
