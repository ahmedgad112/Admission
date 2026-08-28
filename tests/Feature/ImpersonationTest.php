<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('super admins can log in as another staff member', function () {
    $admin = User::factory()->superAdmin()->create(['name' => 'Super Admin']);
    $employee = User::factory()->employee()->create(['name' => 'Ahmed Hassan']);

    $this->actingAs($admin)
        ->post(route('staff.impersonate', $employee))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($employee);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('auth.user.id', $employee->id)
            ->where('impersonation.active', true)
            ->where('impersonation.impersonator.id', $admin->id)
            ->where('impersonation.impersonator.name', 'Super Admin')
            ->where('can.impersonate', false));
});

test('stopping impersonation restores the original account', function () {
    $admin = User::factory()->superAdmin()->create(['name' => 'Super Admin']);
    $employee = User::factory()->employee()->create();

    $this->actingAs($admin)
        ->post(route('staff.impersonate', $employee))
        ->assertRedirect(route('dashboard'));

    $this->delete(route('impersonation.destroy'))
        ->assertRedirect(route('staff.index'));

    $this->assertAuthenticatedAs($admin);

    $this->get(route('staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('auth.user.id', $admin->id)
            ->where('impersonation.active', false)
            ->where('impersonation.impersonator', null)
            ->where('can.impersonate', true));
});

test('super admins cannot impersonate themselves', function () {
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->post(route('staff.impersonate', $admin))
        ->assertForbidden();

    $this->assertAuthenticatedAs($admin);
});

test('nested impersonation is forbidden', function () {
    $admin = User::factory()->superAdmin()->create();
    $branchAdmin = User::factory()->branchAdmin()->create();
    $employee = User::factory()->employee()->create();

    $this->actingAs($admin)
        ->post(route('staff.impersonate', $branchAdmin))
        ->assertRedirect(route('dashboard'));

    $this->post(route('staff.impersonate', $employee))
        ->assertForbidden();

    $this->assertAuthenticatedAs($branchAdmin);
});

test('branch admins and employees cannot impersonate staff', function () {
    $branchAdmin = User::factory()->branchAdmin()->create();
    $employee = User::factory()->employee()->create();
    $target = User::factory()->employee()->create();

    $this->actingAs($branchAdmin)
        ->get(route('staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('can.impersonate', false));

    $this->actingAs($branchAdmin)
        ->post(route('staff.impersonate', $target))
        ->assertForbidden();

    $this->actingAs($employee)
        ->post(route('staff.impersonate', $target))
        ->assertForbidden();
});

test('guests cannot start or stop impersonation', function () {
    $employee = User::factory()->employee()->create();

    $this->post(route('staff.impersonate', $employee))
        ->assertRedirect();

    $this->delete(route('impersonation.destroy'))
        ->assertRedirect();
});
