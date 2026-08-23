<?php

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function permissionStaffPayload(Branch $branch, array $overrides = []): array
{
    return [
        'name' => 'Nour Adel',
        'email' => 'nour-permissions@example.com',
        'phone' => '01000000000',
        'password' => 'password',
        'role' => UserRole::Employee->value,
        'branch_id' => $branch->id,
        'department_id' => null,
        'shift_id' => null,
        'status' => UserStatus::Active->value,
        'leave_days' => 21,
        'permissions' => [],
        ...$overrides,
    ];
}

test('admins can grant extra permissions when creating staff', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->post(route('staff.store'), permissionStaffPayload($branch, [
            'permissions' => [
                Permission::ManageKiosk->value,
                Permission::ManageStaff->value,
            ],
        ]))
        ->assertRedirect(route('staff.index'));

    $staff = User::query()->where('email', 'nour-permissions@example.com')->first();

    expect($staff)->not->toBeNull()
        ->and($staff->permissions)->toBe([
            Permission::ManageKiosk->value,
            Permission::ManageStaff->value,
        ])
        ->and($staff->canManageKiosk())->toBeTrue()
        ->and($staff->canManageStaff())->toBeTrue()
        ->and($staff->canManageTasks())->toBeFalse();
});

test('role defaults are not stored as extra permissions', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->post(route('staff.store'), permissionStaffPayload($branch, [
            'email' => 'manager-permissions@example.com',
            'role' => UserRole::Manager->value,
            'permissions' => [
                Permission::ManageTasks->value,
                Permission::ManageKiosk->value,
            ],
        ]))
        ->assertRedirect(route('staff.index'));

    $staff = User::query()->where('email', 'manager-permissions@example.com')->first();

    expect($staff)->not->toBeNull()
        ->and($staff->permissions)->toBe([Permission::ManageKiosk->value])
        ->and($staff->canManageTasks())->toBeTrue()
        ->and($staff->canManageKiosk())->toBeTrue();
});

test('employees with kiosk permission can open the kiosk and roster', function () {
    $branch = Branch::factory()->create();
    $other = Branch::factory()->create();
    $employee = User::factory()->employee()->withPermissions(Permission::ManageKiosk)->create([
        'branch_id' => $branch->id,
    ]);
    $ownDay = AttendanceDay::factory()->create(['branch_id' => $branch->id]);
    AttendanceDay::factory()->create(['branch_id' => $other->id]);

    $this->actingAs($employee)
        ->get(route('attendance.kiosk'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Kiosk')
            ->where('can.manageKiosk', true));

    $this->actingAs($employee)
        ->get(route('attendance.days.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('canCreate', true)
            ->has('days.data', 1)
            ->where('days.data.0.id', $ownDay->id));
});

test('employees without extra permissions still cannot open the kiosk', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('attendance.kiosk'))
        ->assertForbidden();
});

test('employees with staff permission can create staff in their branch', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->withPermissions(Permission::ManageStaff)->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($employee)
        ->get(route('staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Index')
            ->where('canCreate', true)
            ->where('can.manageStaff', true));

    $this->actingAs($employee)
        ->post(route('staff.store'), permissionStaffPayload($branch, [
            'email' => 'created-by-employee@example.com',
            'permissions' => [Permission::ManageKiosk->value],
        ]))
        ->assertRedirect(route('staff.index'));

    $created = User::query()->where('email', 'created-by-employee@example.com')->first();

    expect($created)->not->toBeNull()
        ->and($created->permissions)->toBe([])
        ->and($created->canManageKiosk())->toBeFalse();
});

test('team scoped staff managers cannot assign another department', function () {
    $branch = Branch::factory()->create();
    $department = Department::factory()->create(['branch_id' => $branch->id]);
    $other = Department::factory()->create(['branch_id' => $branch->id]);
    $employee = User::factory()->employee()->withPermissions(Permission::ManageStaff)->create([
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);

    $this->actingAs($employee)
        ->from(route('staff.create'))
        ->post(route('staff.store'), permissionStaffPayload($branch, [
            'email' => 'other-department@example.com',
            'department_id' => $other->id,
        ]))
        ->assertRedirect(route('staff.create'))
        ->assertSessionHasErrors('department_id');
});

test('admins can update extra permissions on existing staff', function () {
    $branch = Branch::factory()->create();
    $department = Department::factory()->create(['branch_id' => $branch->id]);
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);
    $staff = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'department_id' => $department->id,
        'permissions' => [Permission::ManageTasks->value],
    ]);

    $this->actingAs($admin)
        ->put(route('staff.update', $staff), permissionStaffPayload($branch, [
            'name' => $staff->name,
            'email' => $staff->email,
            'password' => '',
            'department_id' => $department->id,
            'permissions' => [
                Permission::ViewTeamAttendance->value,
                Permission::ReviewLeaveRequests->value,
            ],
        ]))
        ->assertRedirect(route('staff.index'));

    expect($staff->refresh()->permissions)->toBe([
        Permission::ViewTeamAttendance->value,
        Permission::ReviewLeaveRequests->value,
    ])
        ->and($staff->canManageTasks())->toBeFalse()
        ->and($staff->canViewTeamAttendance())->toBeTrue()
        ->and($staff->canReviewLeaveRequests())->toBeTrue();
});

test('the staff form shares grantable permissions', function () {
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->get(route('staff.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Create')
            ->has('permissionOptions', count(Permission::cases()))
            ->where('grantablePermissions', Permission::values())
            ->has('rolePermissions.employee', 0)
            ->has('rolePermissions.manager', 3));
});
