<?php

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\QrSessionService;
use Carbon\CarbonImmutable;
use Tests\TestCase;

uses(TestCase::class);

function attendanceService(): AttendanceService
{
    return new AttendanceService(new QrSessionService);
}

test('haversine distance is zero for the same coordinates', function () {
    expect(attendanceService()->distanceInMeters(30.0444, 31.2357, 30.0444, 31.2357))->toBe(0.0);
});

test('haversine distance estimates one thousandth of a degree of latitude', function () {
    $distance = attendanceService()->distanceInMeters(30.0444, 31.2357, 30.0454, 31.2357);

    expect($distance)->toBeGreaterThan(100)->toBeLessThan(130);
});

test('late arrivals start after the shift grace period', function () {
    $user = new User;
    $user->setRelation('shift', new Shift([
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'grace_period_minutes' => 15,
    ]));

    $onTime = attendanceService()->evaluateCheckIn($user, CarbonImmutable::parse('2026-08-23 09:10:00'));
    $late = attendanceService()->evaluateCheckIn($user, CarbonImmutable::parse('2026-08-23 09:40:00'));

    expect($onTime['status'])->toBe(AttendanceStatus::Present)
        ->and($onTime['late_minutes'])->toBe(0)
        ->and($late['status'])->toBe(AttendanceStatus::Late)
        ->and($late['late_minutes'])->toBe(40);
});

test('early departures and work hours are calculated from the shift window', function () {
    $user = new User;
    $user->setRelation('shift', new Shift([
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'grace_period_minutes' => 15,
    ]));

    $attendance = new Attendance([
        'check_in' => CarbonImmutable::parse('2026-08-23 09:00:00'),
        'status' => AttendanceStatus::Present,
        'late_minutes' => 0,
    ]);

    $result = attendanceService()->evaluateCheckOut(
        $attendance,
        $user,
        CarbonImmutable::parse('2026-08-23 16:30:00'),
    );

    expect($result['early_leave_minutes'])->toBe(30)
        ->and($result['work_hours'])->toBe(7.5)
        ->and($result['status'])->toBe(AttendanceStatus::EarlyLeave);
});

test('overnight shifts extend the end time into the next day', function () {
    $window = attendanceService()->shiftWindow(new Shift([
        'start_time' => '22:00:00',
        'end_time' => '06:00:00',
        'grace_period_minutes' => 10,
    ]), CarbonImmutable::parse('2026-08-23 22:15:00'));

    expect($window['end']->toDateTimeString())->toBe('2026-08-24 06:00:00');
});
