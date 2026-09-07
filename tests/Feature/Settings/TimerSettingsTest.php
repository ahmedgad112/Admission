<?php

use App\Enums\QrSessionType;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\User;
use App\Services\AttendanceSettings;
use App\Services\QrSessionService;
use Inertia\Testing\AssertableInertia as Assert;

test('kiosk managers can open timer settings', function () {
    $admin = User::factory()->branchAdmin()->create();

    $this->actingAs($admin)
        ->get(route('timer.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/Timer')
            ->where('qrTtlSeconds', 20)
            ->where('minTtl', 10)
            ->where('maxTtl', 600)
            ->has('presets'));
});

test('employees cannot open or update timer settings', function () {
    $user = User::factory()->employee()->create();

    $this->actingAs($user)
        ->get(route('timer.edit'))
        ->assertForbidden();

    $this->actingAs($user)
        ->put(route('timer.update'), ['qr_ttl_seconds' => 120])
        ->assertForbidden();
});

test('kiosk managers can lengthen the qr code duration', function () {
    $admin = User::factory()->branchAdmin()->create();
    $branch = Branch::factory()->create();

    $this->actingAs($admin)
        ->from(route('timer.edit'))
        ->put(route('timer.update'), ['qr_ttl_seconds' => 120])
        ->assertRedirect(route('timer.edit'));

    expect(app(AttendanceSettings::class)->qrTtlSeconds())->toBe(120)
        ->and(Setting::query()->where('key', AttendanceSettings::QR_TTL_KEY)->value('value'))->toBe('120');

    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);

    expect($session->expires_at->getTimestamp())->toBe(now()->addSeconds(120)->getTimestamp());
});

test('the kiosk can update the timer over json', function () {
    $admin = User::factory()->branchAdmin()->create();

    $this->actingAs($admin)
        ->putJson(route('timer.update'), ['qr_ttl_seconds' => 90])
        ->assertOk()
        ->assertJsonPath('qr_ttl_seconds', 90);

    expect(app(AttendanceSettings::class)->qrTtlSeconds())->toBe(90);
});

test('updating the timer expires the current qr code', function () {
    $branch = Branch::factory()->create();
    $session = app(QrSessionService::class)->create($branch, QrSessionType::CheckIn);
    $admin = User::factory()->branchAdmin()->create();

    $this->actingAs($admin)
        ->put(route('timer.update'), ['qr_ttl_seconds' => 180]);

    expect($session->fresh()?->isExpired())->toBeTrue();
});

test('timer values outside the allowed range are rejected', function () {
    $admin = User::factory()->branchAdmin()->create();

    $this->actingAs($admin)
        ->from(route('timer.edit'))
        ->put(route('timer.update'), ['qr_ttl_seconds' => 5])
        ->assertRedirect(route('timer.edit'))
        ->assertSessionHasErrors('qr_ttl_seconds');

    $this->actingAs($admin)
        ->from(route('timer.edit'))
        ->put(route('timer.update'), ['qr_ttl_seconds' => 900])
        ->assertRedirect(route('timer.edit'))
        ->assertSessionHasErrors('qr_ttl_seconds');
});

test('the kiosk page uses the saved timer duration', function () {
    $admin = User::factory()->branchAdmin()->create();
    app(AttendanceSettings::class)->updateQrTtlSeconds(180);

    $this->actingAs($admin)
        ->get(route('attendance.kiosk'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Kiosk')
            ->where('qrTtlSeconds', 180));
});
