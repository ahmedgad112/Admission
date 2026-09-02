<?php

use App\Enums\AttendanceStatus;
use App\Enums\QrSessionType;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\QrSession;
use App\Models\Shift;
use App\Models\User;
use App\Services\QrSessionService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->travelTo(now()->setTime(9, 5));
});

function staffedEmployee(array $overrides = []): User
{
    $branch = $overrides['branch'] ?? Branch::factory()->create();
    $shift = $overrides['shift'] ?? Shift::factory()->create();

    return User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'shift_id' => $shift->id,
        ...collect($overrides)->except(['branch', 'shift'])->all(),
    ]);
}

function openAttendanceDay(Branch $branch): AttendanceDay
{
    return AttendanceDay::factory()->openAllDay()->create([
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
    ]);
}

test('employees can check in with a valid qr token', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    openAttendanceDay($branch);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Checked in successfully.');

    $attendance = Attendance::query()->first();

    expect($attendance)->not->toBeNull()
        ->and($attendance->user_id)->toBe($user->id)
        ->and($attendance->status)->toBe(AttendanceStatus::Present);
});

test('expired qr tokens are rejected', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    $session = QrSession::factory()->expired()->create([
        'branch_id' => $branch->id,
        'type' => QrSessionType::CheckIn,
    ]);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertStatus(422)
        ->assertJsonPath('message', 'Invalid or expired QR code.');
});

test('check in is allowed far from the branch location', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    openAttendanceDay($branch);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude + 0.02,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Checked in successfully.');
});

test('duplicate daily check ins are prevented', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    $device = (string) Str::uuid();
    openAttendanceDay($branch);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $payload = [
        'token' => $session->token,
        'latitude' => $branch->latitude,
        'longitude' => $branch->longitude,
        'device_uuid' => $device,
    ];

    $this->actingAs($user)->postJson(route('api.attendance.check-in'), $payload)->assertOk();
    $this->actingAs($user)->postJson(route('api.attendance.check-in'), $payload)
        ->assertStatus(422)
        ->assertJsonPath('message', 'You have already checked in today.');
});

test('a different device uuid is rejected after the account is bound', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee([
        'branch' => $branch,
        'device_uuid' => (string) Str::uuid(),
    ]);
    openAttendanceDay($branch);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertForbidden()
        ->assertJsonPath('message', 'This device is not registered to your account.');
});

test('employees can check out with a valid checkout token', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    $device = (string) Str::uuid();
    openAttendanceDay($branch);

    $checkIn = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);
    $this->actingAs($user)->postJson(route('api.attendance.check-in'), [
        'token' => $checkIn->token,
        'latitude' => $branch->latitude,
        'longitude' => $branch->longitude,
        'device_uuid' => $device,
    ])->assertOk();

    $checkOut = app(QrSessionService::class)->create($branch, QrSessionType::CheckOut);
    $this->actingAs($user)->postJson(route('api.attendance.check-out'), [
        'token' => $checkOut->token,
        'latitude' => $branch->latitude,
        'longitude' => $branch->longitude,
        'device_uuid' => $device,
    ])->assertOk();

    expect(Attendance::query()->first()->check_out)->not->toBeNull();
});

test('staff who are not on the roster can still check in', function () {
    $branch = Branch::factory()->create();
    $onDuty = staffedEmployee(['branch' => $branch]);
    $offDuty = staffedEmployee(['branch' => $branch]);
    $day = openAttendanceDay($branch);
    $day->staff()->sync([$onDuty->id]);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($offDuty)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Checked in successfully.');
});

test('staff on the roster can check in', function () {
    $branch = Branch::factory()->create();
    $onDuty = staffedEmployee(['branch' => $branch]);
    $day = openAttendanceDay($branch);
    $day->staff()->sync([$onDuty->id]);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($onDuty)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Checked in successfully.');
});

test('employees can check in by typing the kiosk entry code', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    openAttendanceDay($branch);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->entry_code,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Checked in successfully.');
});

test('an invalid entry code is rejected', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    openAttendanceDay($branch);
    app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => 'nope12',
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertStatus(422)
        ->assertJsonPath('message', 'Invalid or expired QR code.');
});

test('rotating qr tokens also rotate the typed entry code', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    openAttendanceDay($branch);
    $first = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->travel(19)->seconds();

    $second = app(QrSessionService::class)->currentOrCreate($branch, QrSessionType::CheckIn);

    expect($second->is($first))->toBeFalse()
        ->and($second->entry_code)->not->toBe($first->entry_code)
        ->and($second->token)->not->toBe($first->token);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $first->entry_code,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertStatus(422)
        ->assertJsonPath('message', 'Invalid or expired QR code.');
});

test('employees cannot open the admin kiosk', function () {
    $user = staffedEmployee();

    $this->actingAs($user)
        ->get(route('attendance.kiosk'))
        ->assertForbidden();
});

test('branch admins can generate a time limited qr session', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    openAttendanceDay($branch);

    $this->actingAs($admin)
        ->getJson(route('api.qr-sessions.current', ['type' => 'check_in']))
        ->assertOk()
        ->assertJsonPath('branch_id', $branch->id)
        ->assertJsonPath('type', 'check_in')
        ->assertJsonStructure(['token', 'entry_code', 'expires_at', 'refresh_in_seconds']);

    expect(QrSession::query()->count())->toBe(1)
        ->and(QrSession::query()->value('entry_code'))->toHaveLength(6);
});

test('an active qr session is reused until it is close to expiring', function () {
    $branch = Branch::factory()->create();
    $first = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);
    $reused = app(QrSessionService::class)->currentOrCreate($branch, QrSessionType::CheckIn);

    expect($reused->is($first))->toBeTrue();

    $this->travel(16)->seconds();

    $rotated = app(QrSessionService::class)->currentOrCreate($branch, QrSessionType::CheckIn);

    expect($rotated->is($first))->toBeFalse()
        ->and($rotated->entry_code)->not->toBe($first->entry_code);
});

test('employees can open the scan page', function () {
    $user = staffedEmployee();

    $this->actingAs($user)
        ->get(route('attendance.scan'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Scan')
            ->where('recorded', null));
});

test('employees can check in from the scan page with the kiosk entry code', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    openAttendanceDay($branch);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->from(route('attendance.scan'))
        ->post(route('attendance.scan.store'), [
            'token' => $session->entry_code,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertRedirect(route('attendance.scan'))
        ->assertSessionHas('attendance_recorded', 'check_in');

    $this->actingAs($user)
        ->get(route('attendance.scan'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Scan')
            ->where('recorded', 'check_in'));

    expect(Attendance::query()->first()?->user_id)->toBe($user->id);
});

test('the scan page records check out when the kiosk is in check out mode', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    $device = (string) Str::uuid();
    openAttendanceDay($branch);

    $checkIn = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);
    $this->actingAs($user)->postJson(route('api.attendance.scan'), [
        'token' => $checkIn->token,
        'latitude' => $branch->latitude,
        'longitude' => $branch->longitude,
        'device_uuid' => $device,
    ])->assertOk();

    $checkOut = app(QrSessionService::class)->create($branch, QrSessionType::CheckOut);
    $this->actingAs($user)->postJson(route('api.attendance.scan'), [
        'token' => $checkOut->token,
        'latitude' => $branch->latitude,
        'longitude' => $branch->longitude,
        'device_uuid' => $device,
    ])->assertOk();

    expect(Attendance::query()->first()?->check_out)->not->toBeNull();
});

test('check in is stored in africa cairo time', function () {
    expect(config('app.timezone'))->toBe('Africa/Cairo');

    $this->travelTo(CarbonImmutable::parse('2026-09-02 09:05:00', 'Africa/Cairo'));

    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    openAttendanceDay($branch);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user)
        ->postJson(route('api.attendance.check-in'), [
            'token' => $session->token,
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ])
        ->assertOk();

    $attendance = Attendance::query()->first();

    expect($attendance)->not->toBeNull()
        ->and($attendance->check_in->timezone('Africa/Cairo')->format('Y-m-d H:i'))->toBe('2026-09-02 09:05')
        ->and($attendance->date->toDateString())->toBe('2026-09-02');
});

test('qr scan endpoints are rate limited', function () {
    $branch = Branch::factory()->create();
    $user = staffedEmployee(['branch' => $branch]);
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    $this->actingAs($user);

    for ($attempt = 0; $attempt < 10; $attempt++) {
        $this->postJson(route('api.attendance.check-in'), [
            'token' => 'invalid-token-invalid-token-inva',
            'latitude' => $branch->latitude,
            'longitude' => $branch->longitude,
            'device_uuid' => (string) Str::uuid(),
        ]);
    }

    $this->postJson(route('api.attendance.check-in'), [
        'token' => $session->token,
        'latitude' => $branch->latitude,
        'longitude' => $branch->longitude,
        'device_uuid' => (string) Str::uuid(),
    ])->assertTooManyRequests();
});
