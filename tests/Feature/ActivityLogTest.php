<?php

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

test('super admins can open the activity log', function () {
    $admin = User::factory()->superAdmin()->create();

    actingAs($admin)
        ->get(route('activity-logs.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('activity-logs/Index')
            ->where('can.viewActivityLog', true));
});

test('employees cannot open the activity log', function () {
    $employee = User::factory()->employee()->create();

    actingAs($employee)
        ->get(route('activity-logs.index'))
        ->assertForbidden();
});

test('creating staff writes an activity log entry', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);

    actingAs($admin)
        ->post(route('staff.store'), [
            'name' => 'Nour Adel',
            'email' => 'nour-activity@example.com',
            'phone' => '01000000000',
            'password' => 'password',
            'role' => UserRole::Employee->value,
            'branch_id' => $branch->id,
            'department_id' => null,
            'shift_id' => null,
            'status' => UserStatus::Active->value,
            'leave_days' => 21,
            'permissions' => [],
        ])
        ->assertRedirect(route('staff.index'));

    $staff = User::query()->where('email', 'nour-activity@example.com')->first();

    expect($staff)->not->toBeNull()
        ->and(ActivityLog::query()
            ->where('event', 'created')
            ->where('subject_type', User::class)
            ->where('subject_id', $staff->id)
            ->where('causer_id', $admin->id)
            ->exists())->toBeTrue();
});

test('login is recorded in the activity log', function () {
    $user = User::factory()->employee()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect();

    expect(ActivityLog::query()
        ->where('event', 'logged_in')
        ->where('causer_id', $user->id)
        ->exists())->toBeTrue();
});

test('employees with activity log permission can open the page', function () {
    $employee = User::factory()->employee()->withPermissions(Permission::ViewActivityLog)->create();

    actingAs($employee)
        ->get(route('activity-logs.index'))
        ->assertOk();
});

test('failed login is recorded in the activity log', function () {
    $user = User::factory()->employee()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    expect(ActivityLog::query()
        ->where('event', 'login_failed')
        ->where('properties->email', $user->email)
        ->exists())->toBeTrue();
});
