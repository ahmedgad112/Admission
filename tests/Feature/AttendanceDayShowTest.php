<?php

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('admins can open a roster day view', function () {
    $branch = Branch::factory()->create(['name' => 'Dokki']);
    $department = Department::factory()->create([
        'branch_id' => $branch->id,
        'name' => 'Reception',
    ]);
    $admin = User::factory()->branchAdmin()->create([
        'name' => 'Branch Lead',
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);
    $employee = User::factory()->employee()->create([
        'name' => 'Sara Nabil',
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => '2026-08-29',
        'check_in_starts_at' => '08:00',
        'check_in_ends_at' => '10:30',
        'check_out_starts_at' => '16:00',
        'check_out_ends_at' => '18:30',
        'created_by' => $admin->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-29',
        'check_in' => '2026-08-29 09:05:00',
        'check_out' => '2026-08-29 17:00:00',
        'work_hours' => 7.92,
        'status' => AttendanceStatus::Present,
    ]);

    $this->actingAs($admin)
        ->get(route('attendance.days.show', $day))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/days/Show')
            ->where('canUpdate', true)
            ->where('day.id', $day->id)
            ->where('day.date', '2026-08-29')
            ->where('day.branch.name', 'Dokki')
            ->where('day.check_in_starts_at', '08:00')
            ->where('day.check_out_ends_at', '18:30')
            ->has('day.attendances', 1)
            ->where('day.attendances.0.name', 'Sara Nabil')
            ->where('day.attendances.0.department.name', 'Reception')
            ->where('day.attendances.0.user_id', $employee->id)
            ->where('day.attendances.0.check_in', '09:05')
            ->has('departments', 1)
            ->where('departments.0.id', $department->id)
            ->where('departments.0.name', 'Reception'));
});

test('admins can download a roster day as an excel sheet', function () {
    $branch = Branch::factory()->create(['name' => 'Dokki']);
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'name' => 'Sara Nabil',
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => '2026-08-29',
        'created_by' => $admin->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-29',
        'check_in' => '2026-08-29 09:05:00',
        'check_out' => '2026-08-29 17:00:00',
        'work_hours' => 7.92,
        'status' => AttendanceStatus::Present,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('attendance.days.export', $day))
        ->assertOk()
        ->assertDownload('attendance-dokki-2026-08-29.xlsx');

    $sheet = excelSheetXml($response->streamedContent());

    expect($sheet)
        ->toContain('Sara Nabil')
        ->toContain('Dokki')
        ->toContain('09:05')
        ->toContain('17:00');
});

test('admins can download a roster day excel sheet for one department', function () {
    $branch = Branch::factory()->create(['name' => 'Dokki']);
    $nursing = Department::factory()->create([
        'branch_id' => $branch->id,
        'name' => 'Nursing',
    ]);
    $reception = Department::factory()->create([
        'branch_id' => $branch->id,
        'name' => 'Reception',
    ]);
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
        'department_id' => $nursing->id,
    ]);
    $nurse = User::factory()->employee()->create([
        'name' => 'Sara Nabil',
        'branch_id' => $branch->id,
        'department_id' => $nursing->id,
    ]);
    $receptionist = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'branch_id' => $branch->id,
        'department_id' => $reception->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => '2026-08-29',
        'created_by' => $admin->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $nurse->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-29',
        'check_in' => '2026-08-29 09:05:00',
        'check_out' => '2026-08-29 17:00:00',
        'work_hours' => 7.92,
        'status' => AttendanceStatus::Present,
    ]);
    Attendance::factory()->create([
        'user_id' => $receptionist->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-29',
        'check_in' => '2026-08-29 08:50:00',
        'check_out' => '2026-08-29 16:40:00',
        'work_hours' => 7.83,
        'status' => AttendanceStatus::Present,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('attendance.days.export', [
            'attendanceDay' => $day,
            'department_id' => $nursing->id,
        ]))
        ->assertOk()
        ->assertDownload('attendance-dokki-nursing-2026-08-29.xlsx');

    $sheet = excelSheetXml($response->streamedContent());

    expect($sheet)
        ->toContain('Sara Nabil')
        ->not->toContain('Mona Fathy');
});

test('admins cannot download a roster day excel sheet for a department in another branch', function () {
    $branch = Branch::factory()->create();
    $other = Branch::factory()->create();
    $otherDepartment = Department::factory()->create([
        'branch_id' => $other->id,
    ]);
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'created_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('attendance.days.export', [
            'attendanceDay' => $day,
            'department_id' => $otherDepartment->id,
        ]))
        ->assertNotFound();
});

test('employees cannot download a roster day for another branch', function () {
    $branch = Branch::factory()->create();
    $other = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $other->id,
    ]);

    $this->actingAs($employee)
        ->get(route('attendance.days.export', $day))
        ->assertForbidden();
});

test('employees can view a roster day for their branch', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($employee)
        ->get(route('attendance.days.show', $day))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/days/Show')
            ->where('canUpdate', false)
            ->where('day.id', $day->id)
            ->has('day.attendances', 0));
});

test('employees cannot view a roster day for another branch', function () {
    $branch = Branch::factory()->create();
    $other = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $other->id,
    ]);

    $this->actingAs($employee)
        ->get(route('attendance.days.show', $day))
        ->assertForbidden();
});
