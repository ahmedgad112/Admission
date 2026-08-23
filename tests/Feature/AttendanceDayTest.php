<?php

use App\Enums\QrSessionType;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\User;
use App\Services\QrSessionService;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

function attendanceDayPayload(Branch $branch, array $overrides = []): array
{
    $employee = $overrides['employee'] ?? User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);

    return [
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in_starts_at' => '08:00',
        'check_in_ends_at' => '10:30',
        'check_out_starts_at' => '16:00',
        'check_out_ends_at' => '18:30',
        'staff_ids' => [$employee->id],
        ...collect($overrides)->except('employee')->all(),
    ];
}

test('admins can create a daily check-in and check-out session', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->post(route('attendance.days.store'), attendanceDayPayload($branch, [
            'employee' => $employee,
        ]))
        ->assertRedirect(route('attendance.days.index'));

    $day = AttendanceDay::query()->first();

    expect($day)->not->toBeNull()
        ->and($day->branch_id)->toBe($branch->id)
        ->and($day->created_by)->toBe($admin->id)
        ->and($day->staff->pluck('id')->all())->toBe([$employee->id])
        ->and($day->check_in_is_open)->toBeFalse()
        ->and($day->check_out_is_open)->toBeFalse();
});

test('employees can view the roster for their branch', function () {
    $branch = Branch::factory()->create();
    $other = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $ownDay = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
    ]);
    $ownDay->staff()->sync([$employee->id]);
    AttendanceDay::factory()->create([
        'branch_id' => $other->id,
    ]);

    $this->actingAs($employee)
        ->get(route('attendance.days.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/days/Index')
            ->where('canCreate', false)
            ->has('days.data', 1)
            ->where('days.data.0.id', $ownDay->id)
            ->where('days.data.0.staff.0.id', $employee->id));
});

test('a roster can be created without check-in and check-out windows', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->post(route('attendance.days.store'), [
            'branch_id' => $branch->id,
            'date' => now()->toDateString(),
            'staff_ids' => [$employee->id],
        ])
        ->assertRedirect(route('attendance.days.index'));

    $day = AttendanceDay::query()->first();

    expect($day)->not->toBeNull()
        ->and($day->staff->pluck('id')->all())->toBe([$employee->id]);
});

test('employees cannot create attendance sessions', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($employee)
        ->post(route('attendance.days.store'), attendanceDayPayload($branch, [
            'employee' => $employee,
        ]))
        ->assertForbidden();
});

test('a branch cannot have two sessions on the same day', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'created_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->post(route('attendance.days.store'), attendanceDayPayload($branch, [
            'employee' => $employee,
        ]))
        ->assertSessionHasErrors('date');
});

test('check in is rejected when no daily session exists', function () {
    $this->travelTo(now()->setTime(9, 5));

    $branch = Branch::factory()->create();
    $user = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertStatus(422)
        ->assertJsonPath('message', 'No attendance session is open for today.');
});

test('check in is rejected when the session is closed', function () {
    $this->travelTo(now()->setTime(9, 5));

    $branch = Branch::factory()->create();
    $user = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in_is_open' => false,
    ]);
    $day->staff()->sync([$user->id]);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertStatus(422)
        ->assertJsonPath('message', 'The check-in session is not open now.');
});

test('check in is allowed inside the session window', function () {
    $this->travelTo(now()->setTime(9, 5));

    $branch = Branch::factory()->create();
    $user = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in_starts_at' => '08:00:00',
        'check_in_ends_at' => '11:00:00',
        'check_out_starts_at' => '16:00:00',
        'check_out_ends_at' => '19:00:00',
    ]);
    $day->staff()->sync([$user->id]);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertOk();
});

test('check in is rejected when the employee is not on the roster', function () {
    $this->travelTo(now()->setTime(9, 5));

    $branch = Branch::factory()->create();
    $scheduled = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $user = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
    ]);
    $day->staff()->sync([$scheduled->id]);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertStatus(422)
        ->assertJsonPath('message', 'You are not on today\'s roster.');
});

test('a roster must include at least one staff member', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);

    $this->actingAs($admin)
        ->from(route('attendance.days.create'))
        ->post(route('attendance.days.store'), attendanceDayPayload($branch, [
            'staff_ids' => [],
        ]))
        ->assertRedirect(route('attendance.days.create'))
        ->assertSessionHasErrors('staff_ids');
});

test('staff from another branch cannot be assigned to a roster', function () {
    $branch = Branch::factory()->create();
    $otherBranch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);
    $foreignStaff = User::factory()->employee()->create(['branch_id' => $otherBranch->id]);

    $this->actingAs($admin)
        ->from(route('attendance.days.create'))
        ->post(route('attendance.days.store'), attendanceDayPayload($branch, [
            'staff_ids' => [$foreignStaff->id],
        ]))
        ->assertRedirect(route('attendance.days.create'))
        ->assertSessionHasErrors('staff_ids.0');
});

test('admins can replace the staff assigned to a roster', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);
    $original = User::factory()->employee()->create(['branch_id' => $branch->id]);
    $replacement = User::factory()->employee()->create(['branch_id' => $branch->id]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'created_by' => $admin->id,
    ]);
    $day->staff()->sync([$original->id]);

    $this->actingAs($admin)
        ->put(route('attendance.days.update', $day), attendanceDayPayload($branch, [
            'employee' => $replacement,
            'date' => $day->date->toDateString(),
        ]))
        ->assertRedirect(route('attendance.days.index'));

    expect($day->staff()->pluck('users.id')->all())->toBe([$replacement->id]);
});

test('the create form lists staff from the admin branch', function () {
    $branch = Branch::factory()->create();
    $otherBranch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'name' => 'Amina Admin',
        'branch_id' => $branch->id,
    ]);
    User::factory()->employee()->create([
        'name' => 'Local Nurse',
        'branch_id' => $branch->id,
    ]);
    User::factory()->employee()->create([
        'name' => 'Other Clinic',
        'branch_id' => $otherBranch->id,
    ]);

    $this->actingAs($admin)
        ->get(route('attendance.days.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/days/Create')
            ->has('staff', 2)
            ->where('staff.0.name', 'Amina Admin')
            ->where('staff.1.name', 'Local Nurse'));
});

test('admins can open and close a kiosk session', function () {
    $this->travelTo(now()->setTime(9, 5));

    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in_is_open' => false,
        'created_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->getJson(route('api.qr-sessions.current', ['type' => 'check_in']))
        ->assertStatus(422)
        ->assertJsonPath('message', 'This session is closed.');

    $this->actingAs($admin)
        ->postJson(route('api.qr-sessions.open'), ['type' => 'check_in'])
        ->assertOk()
        ->assertJsonPath('message', 'Session opened.')
        ->assertJsonPath('type', 'check_in');

    expect($day->refresh()->check_in_is_open)->toBeTrue();

    $this->actingAs($admin)
        ->getJson(route('api.qr-sessions.current', ['type' => 'check_in']))
        ->assertOk()
        ->assertJsonPath('type', 'check_in');

    $this->actingAs($admin)
        ->postJson(route('api.qr-sessions.close'), ['type' => 'check_in'])
        ->assertOk()
        ->assertJsonPath('message', 'Session closed.');

    expect($day->refresh()->check_in_is_open)->toBeFalse();
});

test('employees cannot open or close a kiosk session', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
    ]);

    $this->actingAs($employee)
        ->postJson(route('api.qr-sessions.open'), ['type' => 'check_in'])
        ->assertForbidden();
});

test('the kiosk cannot generate a qr code without an open session', function () {
    $this->travelTo(now()->setTime(9, 5));

    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->getJson(route('api.qr-sessions.current', ['type' => 'check_in']))
        ->assertStatus(422)
        ->assertJsonPath('message', 'Create an attendance session for today before opening the kiosk.');
});
