<?php

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('branch admins can open the departments page', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);
    Department::factory()->create(['branch_id' => $branch->id, 'name' => 'Engineering']);

    $this->actingAs($admin)
        ->get(route('departments.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('departments/Index')
            ->where('canCreate', true)
            ->has('departments.data', 1)
            ->where('departments.data.0.name', 'Engineering'));
});

test('employees cannot open the departments page', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('departments.index'))
        ->assertForbidden();
});

test('branch admins can create departments in their branch', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->post(route('departments.store'), [
            'name' => 'Operations',
            'branch_id' => $branch->id,
            'manager_id' => null,
        ])
        ->assertRedirect(route('departments.index'));

    expect(Department::query()->where('name', 'Operations')->exists())->toBeTrue();
});

test('branch admins only see departments in their branch', function () {
    $branch = Branch::factory()->create();
    $other = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);
    Department::factory()->create(['branch_id' => $branch->id, 'name' => 'Mine']);
    Department::factory()->create(['branch_id' => $other->id, 'name' => 'Other']);

    $this->actingAs($admin)
        ->get(route('departments.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('departments.data', 1)
            ->where('departments.data.0.name', 'Mine'));
});
