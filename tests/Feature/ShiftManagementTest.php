<?php

use App\Models\Shift;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function shiftPayload(array $overrides = []): array
{
    return [
        'name' => 'Night Shift',
        'start_time' => '22:00',
        'end_time' => '06:00',
        'grace_period_minutes' => 10,
        ...$overrides,
    ];
}

test('super admins can create a shift', function () {
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->post(route('shifts.store'), shiftPayload())
        ->assertRedirect(route('shifts.index'));

    $shift = Shift::query()->where('name', 'Night Shift')->first();

    expect($shift)->not->toBeNull()
        ->and($shift->start_time)->toStartWith('22:00')
        ->and($shift->end_time)->toStartWith('06:00')
        ->and($shift->grace_period_minutes)->toBe(10);
});

test('branch admins can update a shift', function () {
    $shift = Shift::factory()->create([
        'name' => 'Day Shift',
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'grace_period_minutes' => 15,
    ]);
    $admin = User::factory()->branchAdmin()->create();

    $this->actingAs($admin)
        ->put(route('shifts.update', $shift), shiftPayload([
            'name' => 'Morning Shift',
            'start_time' => '08:00',
            'end_time' => '16:00',
            'grace_period_minutes' => 20,
        ]))
        ->assertRedirect(route('shifts.index'));

    expect($shift->refresh()->name)->toBe('Morning Shift')
        ->and($shift->grace_period_minutes)->toBe(20);
});

test('employees cannot open the shifts page', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->get(route('shifts.index'))
        ->assertForbidden();
});

test('managers cannot manage shifts', function () {
    $manager = User::factory()->manager()->create();

    $this->actingAs($manager)
        ->get(route('shifts.index'))
        ->assertForbidden();
});

test('the shifts page lists existing shifts', function () {
    Shift::factory()->create(['name' => 'Standard Day']);
    $admin = User::factory()->branchAdmin()->create();

    $this->actingAs($admin)
        ->get(route('shifts.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shifts/Index')
            ->has('shifts.data', 1)
            ->where('shifts.data.0.name', 'Standard Day')
            ->where('canCreate', true));
});

test('admins can delete a shift', function () {
    $shift = Shift::factory()->create();
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->delete(route('shifts.destroy', $shift))
        ->assertRedirect(route('shifts.index'));

    expect(Shift::query()->find($shift->id))->toBeNull();
});
