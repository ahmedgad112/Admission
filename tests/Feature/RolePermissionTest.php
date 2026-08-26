<?php

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\User;
use App\Support\RolePermissionCatalog;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

/**
 * @return array<string, list<string>>
 */
function rolePermissionPayload(array $overrides = []): array
{
    return [
        'roles' => [
            UserRole::BranchAdmin->value => UserRole::BranchAdmin->defaultPermissionValues(),
            UserRole::Manager->value => UserRole::Manager->defaultPermissionValues(),
            UserRole::Employee->value => UserRole::Employee->defaultPermissionValues(),
            ...$overrides,
        ],
    ];
}

test('super admins can open the role permissions page', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->get(route('permissions.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('permissions/Edit')
            ->has('permissionOptions', count(Permission::cases()))
            ->where('rolePermissions.manager', UserRole::Manager->defaultPermissionValues())
            ->where('rolePermissions.employee', [])
            ->where('can.managePermissions', true));
});

test('branch admins cannot manage role permissions', function () {
    $admin = User::factory()->branchAdmin()->create();

    actingAs($admin)
        ->get(route('permissions.edit'))
        ->assertForbidden();

    actingAs($admin)
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Employee->value => [Permission::ManageKiosk->value],
        ]))
        ->assertForbidden();
});

test('employees cannot manage role permissions', function () {
    $employee = User::factory()->employee()->create();

    actingAs($employee)
        ->get(route('permissions.edit'))
        ->assertForbidden();
});

test('super admins can change default permissions for a role', function () {
    $admin = User::factory()->superAdmin()->create();
    $manager = User::factory()->manager()->create();

    expect($manager->canManageTasks())->toBeTrue()
        ->and($manager->canManageKiosk())->toBeFalse();

    actingAs($admin)
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Manager->value => [Permission::ManageKiosk->value],
        ]))
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();

    expect($manager->fresh()->canManageTasks())->toBeFalse()
        ->and($manager->fresh()->canManageKiosk())->toBeTrue()
        ->and($manager->fresh()->canViewTeamAttendance())->toBeFalse();
});

test('super admin permissions cannot be reduced', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->put(route('permissions.update'), [
            'roles' => [
                UserRole::SuperAdmin->value => [],
                UserRole::BranchAdmin->value => UserRole::BranchAdmin->defaultPermissionValues(),
                UserRole::Manager->value => UserRole::Manager->defaultPermissionValues(),
                UserRole::Employee->value => [],
            ],
        ])
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();

    expect($admin->fresh()->canManageStaff())->toBeTrue()
        ->and(UserRole::SuperAdmin->permissionValues())->toBe(Permission::values());
});

test('employees keep extra permissions when a role default is removed', function () {
    $admin = User::factory()->superAdmin()->create();
    $employee = User::factory()->employee()->withPermissions(Permission::ManageKiosk)->create();

    actingAs($admin)
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Employee->value => [],
        ]))
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();

    expect($employee->fresh()->canManageKiosk())->toBeTrue();
});

test('the staff form uses updated role defaults', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Manager->value => [Permission::ManageKiosk->value],
            UserRole::Employee->value => [Permission::ManageTasks->value],
        ]))
        ->assertRedirect(route('permissions.edit'));

    actingAs($admin)
        ->get(route('staff.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('rolePermissions.manager', [Permission::ManageKiosk->value])
            ->where('rolePermissions.employee', [Permission::ManageTasks->value]));
});

test('invalid permissions are rejected', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->from(route('permissions.edit'))
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Employee->value => ['not_a_permission'],
        ]))
        ->assertRedirect(route('permissions.edit'))
        ->assertSessionHasErrors('roles.employee.0');
});
