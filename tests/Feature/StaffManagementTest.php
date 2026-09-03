<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Task;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function staffPayload(Branch $branch, array $overrides = []): array
{
    return [
        'name' => 'Nour Adel',
        'email' => 'nour@example.com',
        'phone' => '01000000000',
        'password' => 'password',
        'role' => UserRole::Employee->value,
        'branch_id' => $branch->id,
        'department_id' => null,
        'shift_id' => null,
        'status' => UserStatus::Active->value,
        'leave_days' => 21,
        ...$overrides,
    ];
}

test('branch admins can view and create staff in their branch', function () {
    $branch = Branch::factory()->create();
    $department = Department::factory()->create(['branch_id' => $branch->id]);
    $shift = Shift::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);

    $this->actingAs($admin)
        ->post(route('staff.store'), staffPayload($branch, [
            'department_id' => $department->id,
            'shift_id' => $shift->id,
        ]))
        ->assertRedirect(route('staff.index'));

    $staff = User::query()->where('email', 'nour@example.com')->first();

    expect($staff)->not->toBeNull()
        ->and($staff->name)->toBe('Nour Adel')
        ->and($staff->branch_id)->toBe($branch->id)
        ->and($staff->department_id)->toBe($department->id)
        ->and($staff->shift_id)->toBe($shift->id)
        ->and($staff->role?->slug)->toBe(UserRole::Employee->value)
        ->and($staff->leave_days)->toBe(21)
        ->and($staff->permissions)->toBe([]);
});

test('branch admins only see staff in their department', function () {
    $department = Department::factory()->create();
    $otherDepartment = Department::factory()->create(['branch_id' => $department->branch_id]);
    $admin = User::factory()->branchAdmin()->create([
        'name' => 'Branch Admin',
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $teammate = User::factory()->employee()->create([
        'name' => 'Dept Staff',
        'department_id' => $department->id,
        'branch_id' => $department->branch_id,
    ]);
    User::factory()->employee()->create([
        'name' => 'Other Dept Staff',
        'department_id' => $otherDepartment->id,
        'branch_id' => $department->branch_id,
    ]);

    $this->actingAs($admin)
        ->get(route('staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Index')
            ->where('canCreate', true)
            ->has('staff.data', 2)
            ->where('staff.data.0.name', 'Branch Admin')
            ->where('staff.data.1.name', 'Dept Staff'));
});

test('employees cannot open the staff page', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('staff.index'))
        ->assertForbidden();
});

test('managers can view department staff but cannot create them', function () {
    $department = Department::factory()->create();
    $manager = User::factory()->manager()->create([
        'name' => 'Amina Manager',
        'department_id' => $department->id,
        'branch_id' => $department->branch_id,
    ]);
    $teammate = User::factory()->employee()->create([
        'name' => 'Team Nurse',
        'department_id' => $department->id,
        'branch_id' => $department->branch_id,
    ]);
    User::factory()->employee()->create([
        'name' => 'Other Floor',
        'department_id' => Department::factory(),
    ]);

    $this->actingAs($manager)
        ->get(route('staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Index')
            ->where('canCreate', false)
            ->has('staff.data', 2)
            ->where('staff.data.0.name', 'Amina Manager')
            ->where('staff.data.1.name', 'Team Nurse'));

    $this->actingAs($manager)
        ->post(route('staff.store'), staffPayload($department->branch, [
            'email' => 'blocked@example.com',
        ]))
        ->assertForbidden();
});

test('branch admins can save staff and create another', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->post(route('staff.store'), staffPayload($branch, [
            'email' => 'first@example.com',
            'create_another' => true,
        ]))
        ->assertRedirect(route('staff.create'));

    expect(User::query()->where('email', 'first@example.com')->exists())->toBeTrue();
});

test('branch admins cannot assign staff to another branch', function () {
    $branch = Branch::factory()->create();
    $other = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->from(route('staff.create'))
        ->post(route('staff.store'), staffPayload($other))
        ->assertRedirect(route('staff.create'))
        ->assertSessionHasErrors('branch_id');
});

test('branch admins cannot create a super admin', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->from(route('staff.create'))
        ->post(route('staff.store'), staffPayload($branch, [
            'role' => UserRole::SuperAdmin->value,
        ]))
        ->assertRedirect(route('staff.create'))
        ->assertSessionHasErrors('role');
});

test('admins can update staff details', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);
    $staff = User::factory()->employee()->create([
        'name' => 'Old Name',
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->put(route('staff.update', $staff), staffPayload($branch, [
            'name' => 'Updated Name',
            'email' => $staff->email,
            'password' => '',
            'role' => UserRole::Manager->value,
            'leave_days' => 10,
        ]))
        ->assertRedirect(route('staff.index'));

    expect($staff->refresh()->name)->toBe('Updated Name')
        ->and($staff->role?->slug)->toBe(UserRole::Manager->value)
        ->and($staff->leave_days)->toBe(10);
});

test('admins cannot delete themselves', function () {
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->delete(route('staff.destroy', $admin))
        ->assertForbidden();
});

test('staff index marks which members can be deleted', function () {
    $admin = User::factory()->superAdmin()->create(['name' => 'Admin User']);
    $employee = User::factory()->employee()->create(['name' => 'Ziad Nurse']);

    $this->actingAs($admin)
        ->get(route('staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Index')
            ->has('staff.data', 2)
            ->where('staff.data.0.name', 'Admin User')
            ->where('staff.data.0.can_delete', false)
            ->where('staff.data.1.name', 'Ziad Nurse')
            ->where('staff.data.1.can_delete', true));
});

test('admins can delete any employee including ones with related records', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);
    $staff = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'created_by' => $staff->id,
    ]);
    $task = Task::factory()->assignedTo($staff)->create([
        'title' => 'Keep this task',
        'created_by' => $staff->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $staff->id,
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->delete(route('staff.destroy', $staff))
        ->assertRedirect(route('staff.index'));

    expect(User::query()->whereKey($staff->id)->exists())->toBeFalse()
        ->and($day->refresh()->created_by)->toBe($admin->id)
        ->and($task->refresh()->created_by)->toBe($admin->id)
        ->and($task->assignees()->whereKey($staff->id)->exists())->toBeFalse()
        ->and(Attendance::query()->where('user_id', $staff->id)->exists())->toBeFalse();
});

test('branch admins cannot delete super admins', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);
    $super = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->delete(route('staff.destroy', $super))
        ->assertForbidden();

    expect(User::query()->whereKey($super->id)->exists())->toBeTrue();
});
