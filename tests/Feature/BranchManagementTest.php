<?php

use App\Models\Branch;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function branchPayload(array $overrides = []): array
{
    return [
        'name' => 'Nasr City Clinic',
        ...$overrides,
    ];
}

test('super admins can create a branch by name', function () {
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->post(route('branches.store'), branchPayload())
        ->assertRedirect(route('branches.index'));

    $branch = Branch::query()->where('name', 'Nasr City Clinic')->first();

    expect($branch)->not->toBeNull();
});

test('branch admins can update their own branch name', function () {
    $branch = Branch::factory()->create([
        'name' => 'Old Point',
    ]);
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->put(route('branches.update', $branch), branchPayload([
            'name' => 'Updated Clinic',
        ]))
        ->assertRedirect(route('branches.index'));

    expect($branch->refresh()->name)->toBe('Updated Clinic');
});

test('branch admins cannot edit another branch', function () {
    $own = Branch::factory()->create();
    $other = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $own->id,
    ]);

    $this->actingAs($admin)
        ->put(route('branches.update', $other), branchPayload())
        ->assertForbidden();
});

test('branch admins cannot create branches', function () {
    $admin = User::factory()->branchAdmin()->create();

    $this->actingAs($admin)
        ->post(route('branches.store'), branchPayload())
        ->assertForbidden();
});

test('employees cannot open the branches page', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('branches.index'))
        ->assertForbidden();
});

test('the branches page lists the admin branch', function () {
    $branch = Branch::factory()->create(['name' => 'Visible Clinic']);
    Branch::factory()->create(['name' => 'Hidden Clinic']);
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->get(route('branches.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('branches/Index')
            ->has('branches.data', 1)
            ->where('branches.data.0.name', 'Visible Clinic')
            ->where('canCreate', false));
});
