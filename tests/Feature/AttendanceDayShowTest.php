<?php

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
        'branch_id' => $branch->id,
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
    $day->staff()->sync([$employee->id]);

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
            ->has('day.staff', 1)
            ->where('day.staff.0.name', 'Sara Nabil')
            ->where('day.staff.0.department.name', 'Reception'));
});

test('employees can view a roster day for their branch', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
    ]);
    $day->staff()->sync([$employee->id]);

    $this->actingAs($employee)
        ->get(route('attendance.days.show', $day))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/days/Show')
            ->where('canUpdate', false)
            ->where('day.id', $day->id)
            ->where('day.staff.0.id', $employee->id));
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
