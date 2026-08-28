<?php

use App\Enums\HomePage;
use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use App\Support\RolePermissionCatalog;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

/**
 * @return array<string, mixed>
 */
function rolePermissionPayload(array $overrides = []): array
{
    $homes = [
        UserRole::BranchAdmin->value => HomePage::Dashboard->value,
        UserRole::Manager->value => HomePage::Dashboard->value,
        UserRole::Employee->value => HomePage::Dashboard->value,
    ];

    if (isset($overrides['homes']) && is_array($overrides['homes'])) {
        $homes = [...$homes, ...$overrides['homes']];
        unset($overrides['homes']);
    }

    $names = [];

    if (isset($overrides['names']) && is_array($overrides['names'])) {
        $names = $overrides['names'];
        unset($overrides['names']);
    }

    return [
        'roles' => [
            UserRole::BranchAdmin->value => UserRole::BranchAdmin->defaultPermissionValues(),
            UserRole::Manager->value => UserRole::Manager->defaultPermissionValues(),
            UserRole::Employee->value => UserRole::Employee->defaultPermissionValues(),
            ...$overrides,
        ],
        'homes' => $homes,
        'names' => $names,
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
            ->has('homePageOptions')
            ->where('rolePermissions.manager', UserRole::Manager->defaultPermissionValues())
            ->where('rolePermissions.employee', UserRole::Employee->defaultPermissionValues())
            ->where('roleHomes.employee', HomePage::Dashboard->value)
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
            UserRole::Manager->value => [
                Permission::ViewDashboard->value,
                Permission::ManageKiosk->value,
            ],
        ]))
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();
    $manager->unsetRelation('role');

    expect($manager->fresh()->canManageTasks())->toBeFalse()
        ->and($manager->fresh()->canManageKiosk())->toBeTrue()
        ->and($manager->fresh()->canViewTeamAttendance())->toBeFalse();
});

test('super admins can set a role landing page', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->put(route('permissions.update'), rolePermissionPayload([
            'homes' => [
                UserRole::Employee->value => HomePage::Scan->value,
            ],
        ]))
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();

    expect(Role::requireBySlug(UserRole::Employee->value)->homePage())->toBe(HomePage::Scan);
});

test('a landing page is rejected when the role cannot open it', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->from(route('permissions.edit'))
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Employee->value => UserRole::Employee->defaultPermissionValues(),
            'homes' => [
                UserRole::Employee->value => HomePage::Kiosk->value,
            ],
        ]))
        ->assertRedirect(route('permissions.edit'))
        ->assertSessionHasErrors('homes.employee');
});

test('super admin permissions cannot be reduced', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->put(route('permissions.update'), [
            'roles' => [
                UserRole::SuperAdmin->value => [],
                UserRole::BranchAdmin->value => UserRole::BranchAdmin->defaultPermissionValues(),
                UserRole::Manager->value => UserRole::Manager->defaultPermissionValues(),
                UserRole::Employee->value => UserRole::Employee->defaultPermissionValues(),
            ],
            'homes' => [
                UserRole::BranchAdmin->value => HomePage::Dashboard->value,
                UserRole::Manager->value => HomePage::Dashboard->value,
                UserRole::Employee->value => HomePage::Dashboard->value,
            ],
        ])
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();

    expect($admin->fresh()->canManageStaff())->toBeTrue()
        ->and(Role::requireBySlug(UserRole::SuperAdmin->value)->permissionValues())->toBe(Permission::values());
});

test('employees keep extra permissions when a role default is removed', function () {
    $admin = User::factory()->superAdmin()->create();
    $employee = User::factory()->employee()->withPermissions(Permission::ManageKiosk)->create();

    actingAs($admin)
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Employee->value => UserRole::Employee->defaultPermissionValues(),
        ]))
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();

    expect($employee->fresh()->canManageKiosk())->toBeTrue();
});

test('the staff form uses updated role defaults', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->put(route('permissions.update'), rolePermissionPayload([
            UserRole::Manager->value => [
                Permission::ViewDashboard->value,
                Permission::ManageKiosk->value,
            ],
            UserRole::Employee->value => [
                ...UserRole::Employee->defaultPermissionValues(),
                Permission::ManageTasks->value,
            ],
        ]))
        ->assertRedirect(route('permissions.edit'));

    actingAs($admin)
        ->get(route('staff.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('rolePermissions.manager', [
                Permission::ViewDashboard->value,
                Permission::ManageKiosk->value,
            ])
            ->where('rolePermissions.employee', [
                ...UserRole::Employee->defaultPermissionValues(),
                Permission::ManageTasks->value,
            ]));
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

test('super admins can create a custom role', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->post(route('permissions.roles.store'), [
            'name' => 'HR Lead',
            'permissions' => UserRole::Employee->defaultPermissionValues(),
            'home_page' => HomePage::Dashboard->value,
        ])
        ->assertRedirect(route('permissions.edit'));

    $role = Role::query()->where('slug', 'hr-lead')->first();

    expect($role)->not->toBeNull()
        ->and($role->name)->toBe('HR Lead')
        ->and($role->is_system)->toBeFalse();
});

test('custom roles with assigned users cannot be deleted', function () {
    $admin = User::factory()->superAdmin()->create();
    $role = Role::factory()->create(['name' => 'Auditor', 'slug' => 'auditor']);
    User::factory()->forRole($role)->create();

    actingAs($admin)
        ->delete(route('permissions.roles.destroy', $role))
        ->assertForbidden();

    expect(Role::query()->whereKey($role->id)->exists())->toBeTrue();
});

test('unused custom roles can be deleted', function () {
    $admin = User::factory()->superAdmin()->create();
    $role = Role::factory()->create(['name' => 'Temp Role', 'slug' => 'temp-role']);

    actingAs($admin)
        ->delete(route('permissions.roles.destroy', $role))
        ->assertRedirect(route('permissions.edit'));

    expect(Role::query()->whereKey($role->id)->exists())->toBeFalse();
});
