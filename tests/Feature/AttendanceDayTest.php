<?php

use App\Enums\QrSessionType;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\User;
use App\Services\QrSessionService;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

function attendanceDayPayload(Branch $branch, array $overrides = []): array
{
    return [
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        ...$overrides,
    ];
}

test('admins can create a daily check-in and check-out session', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->post(route('attendance.days.store'), attendanceDayPayload($branch))
        ->assertRedirect(route('attendance.days.index'));

    $day = AttendanceDay::query()->first();

    expect($day)->not->toBeNull()
        ->and($day->branch_id)->toBe($branch->id)
        ->and($day->created_by)->toBe($admin->id)
        ->and($day->staff)->toBeEmpty()
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
            ->where('days.data.0.present_count', 0));
});

test('a roster can be created without check-in and check-out windows', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($admin)
        ->post(route('attendance.days.store'), [
            'branch_id' => $branch->id,
            'date' => now()->toDateString(),
        ])
        ->assertRedirect(route('attendance.days.index'));

    $day = AttendanceDay::query()->first();

    expect($day)->not->toBeNull()
        ->and($day->staff)->toBeEmpty();
});

test('employees cannot create attendance sessions', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($employee)
        ->post(route('attendance.days.store'), attendanceDayPayload($branch))
        ->assertForbidden();
});

test('a branch cannot have two sessions on the same day', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create();
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'created_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->post(route('attendance.days.store'), attendanceDayPayload($branch))
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

test('staff can check in even when they were not preselected on a roster', function () {
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
        ->assertOk();

    expect(Attendance::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

test('the create form lists the admin branch', function () {
    $branch = Branch::factory()->create(['name' => 'Dokki']);
    $otherBranch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    User::factory()->employee()->create([
        'branch_id' => $otherBranch->id,
    ]);

    $this->actingAs($admin)
        ->get(route('attendance.days.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/days/Create')
            ->has('branches', 1)
            ->where('branches.0.name', 'Dokki')
            ->missing('staff'));
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
        ->assertJsonPath('message', 'This session is closed.');

    expect(AttendanceDay::query()
        ->where('branch_id', $branch->id)
        ->whereDate('date', now()->toDateString())
        ->exists())->toBeTrue();
});

test('the kiosk pending list shows branch staff who have not checked in', function () {
    $this->travelTo(now()->setTime(9, 5));

    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
        'name' => 'Branch Admin',
    ]);
    $checkedIn = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'name' => 'Checked In Staff',
    ]);
    $pending = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'name' => 'Pending Staff',
    ]);
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
    ]);

    Attendance::factory()->create([
        'user_id' => $checkedIn->id,
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in' => now(),
    ]);

    $names = $this->actingAs($admin)
        ->getJson(route('api.kiosk.pending', ['type' => 'check_in']))
        ->assertOk()
        ->json('pending');

    expect(collect($names)->pluck('name'))
        ->toContain('Pending Staff')
        ->not->toContain('Checked In Staff');
});

test('the kiosk pending list shows roster staff who have not checked out', function () {
    $this->travelTo(now()->setTime(16, 5));

    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $checkedOut = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'name' => 'Checked Out Staff',
    ]);
    $pending = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'name' => 'Awaiting Checkout',
    ]);
    AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
    ]);

    Attendance::factory()->create([
        'user_id' => $checkedOut->id,
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in' => now()->setTime(9, 0),
        'check_out' => now()->setTime(16, 0),
    ]);
    Attendance::factory()->create([
        'user_id' => $pending->id,
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in' => now()->setTime(9, 0),
        'check_out' => null,
    ]);

    $this->actingAs($admin)
        ->getJson(route('api.kiosk.pending', ['type' => 'check_out']))
        ->assertOk()
        ->assertJsonCount(1, 'pending')
        ->assertJsonPath('pending.0.name', 'Awaiting Checkout');
});
