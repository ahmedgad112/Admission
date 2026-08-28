<?php

use App\Enums\HomePage;
use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use App\Support\HomeRedirect;
use App\Support\RolePermissionCatalog;

use function Pest\Laravel\actingAs;

test('users land on the dashboard by default after login', function () {
    $user = User::factory()->employee()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard', absolute: false));
});

test('users land on the role home page after login', function () {
    $admin = User::factory()->superAdmin()->create();
    $employee = User::factory()->employee()->create();

    actingAs($admin)
        ->put(route('permissions.update'), [
            'roles' => [
                UserRole::BranchAdmin->value => UserRole::BranchAdmin->defaultPermissionValues(),
                UserRole::Manager->value => UserRole::Manager->defaultPermissionValues(),
                UserRole::Employee->value => UserRole::Employee->defaultPermissionValues(),
            ],
            'homes' => [
                UserRole::BranchAdmin->value => HomePage::Dashboard->value,
                UserRole::Manager->value => HomePage::Dashboard->value,
                UserRole::Employee->value => HomePage::Scan->value,
            ],
        ])
        ->assertRedirect(route('permissions.edit'));

    app(RolePermissionCatalog::class)->forget();

    $this->post(route('logout'));

    $this->post(route('login.store'), [
        'email' => $employee->email,
        'password' => 'password',
    ])->assertRedirect(route('attendance.scan', absolute: false));
});

test('home redirect falls back when the role page is not allowed', function () {
    $employee = User::factory()->employee()->create();

    app(RolePermissionCatalog::class)->sync(
        [
            UserRole::BranchAdmin->value => UserRole::BranchAdmin->defaultPermissionValues(),
            UserRole::Manager->value => UserRole::Manager->defaultPermissionValues(),
            UserRole::Employee->value => [Permission::ScanAttendance->value],
        ],
        [
            UserRole::BranchAdmin->value => HomePage::Dashboard->value,
            UserRole::Manager->value => HomePage::Dashboard->value,
            UserRole::Employee->value => HomePage::Dashboard->value,
        ],
    );

    $employee->unsetRelation('role');

    expect(app(HomeRedirect::class)->url($employee->fresh()))->toBe(route('attendance.scan', absolute: false))
        ->and(Role::requireBySlug(UserRole::Employee->value)->homePage())->toBe(HomePage::Dashboard);
});
