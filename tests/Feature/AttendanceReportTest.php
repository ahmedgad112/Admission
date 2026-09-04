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

test('admins can open an attendance report with a column per employee', function () {
    $this->travelTo('2026-09-04');

    $branch = Branch::factory()->create();
    $department = Department::factory()->create(['branch_id' => $branch->id]);
    $admin = User::factory()->superAdmin()->create([
        'name' => 'System Admin',
        'branch_id' => $branch->id,
    ]);
    $mona = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);
    $sara = User::factory()->employee()->create([
        'name' => 'Sara Adel',
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);

    foreach (['2026-09-01', '2026-09-02', '2026-09-03'] as $date) {
        AttendanceDay::factory()->create([
            'branch_id' => $branch->id,
            'date' => $date,
            'created_by' => $admin->id,
        ]);
    }

    Attendance::factory()->create([
        'user_id' => $mona->id,
        'branch_id' => $branch->id,
        'date' => '2026-09-01',
        'check_in' => '2026-09-01 09:00:00',
        'status' => AttendanceStatus::Present,
    ]);
    Attendance::factory()->create([
        'user_id' => $mona->id,
        'branch_id' => $branch->id,
        'date' => '2026-09-02',
        'check_in' => '2026-09-02 09:40:00',
        'status' => AttendanceStatus::Late,
    ]);
    Attendance::factory()->create([
        'user_id' => $sara->id,
        'branch_id' => $branch->id,
        'date' => '2026-09-02',
        'check_in' => '2026-09-02 09:00:00',
        'status' => AttendanceStatus::Present,
    ]);
    LeaveRequest::factory()->approved()->create([
        'user_id' => $sara->id,
        'branch_id' => $branch->id,
        'department_id' => $department->id,
        'type' => LeaveRequestType::Personal,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-01',
        'status' => LeaveRequestStatus::Approved,
        'reviewed_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('attendance.reports', ['month' => '2026-09']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Reports')
            ->where('month', '2026-09')
            ->has('dates', 3)
            ->has('people', 2)
            ->where('people.0.name', 'Mona Fathy')
            ->where('people.0.present_days', 2)
            ->where('people.0.absent_days', 1)
            ->where('people.0.attendance_rate', 66.7)
            ->where('people.0.marks.2026-09-01', 'present')
            ->where('people.0.marks.2026-09-02', 'late')
            ->where('people.0.marks.2026-09-03', 'absent')
            ->where('people.1.name', 'Sara Adel')
            ->where('people.1.present_days', 1)
            ->where('people.1.absent_days', 1)
            ->where('people.1.leave_days', 1)
            ->where('people.1.attendance_rate', 50)
            ->where('people.1.marks.2026-09-01', 'leave')
            ->where('people.1.marks.2026-09-02', 'present')
            ->where('people.1.marks.2026-09-03', 'absent'));
});

test('employees cannot open the attendance report', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('attendance.reports'))
        ->assertForbidden();
});
