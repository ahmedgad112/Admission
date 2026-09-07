<?php

use App\Enums\QrSessionType;
use App\Models\Branch;
use App\Models\Setting;
use App\Services\AttendanceSettings;
use App\Services\QrSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('the qr timer falls back to the configured default', function () {
    expect(app(AttendanceSettings::class)->qrTtlSeconds())->toBe(20);
});

test('the stored qr timer is used for new sessions', function () {
    Setting::factory()->qrTtl(45)->create();

    expect(app(AttendanceSettings::class)->qrTtlSeconds())->toBe(45);

    $session = app(QrSessionService::class)->create(Branch::factory()->create(), QrSessionType::CheckIn);

    expect($session->expires_at->getTimestamp())->toBe(now()->addSeconds(45)->getTimestamp());
});

test('timer values are clamped to the allowed range', function () {
    $settings = app(AttendanceSettings::class);

    expect($settings->updateQrTtlSeconds(1))->toBe(10)
        ->and($settings->updateQrTtlSeconds(9999))->toBe(600)
        ->and($settings->qrTtlSeconds())->toBe(600);
});
