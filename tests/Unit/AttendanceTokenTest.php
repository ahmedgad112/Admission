<?php

use App\Support\AttendanceToken;

test('plain kiosk codes are kept', function () {
    expect(AttendanceToken::fromScannedValue(' 482193 '))->toBe('482193');
});

test('tokens are extracted from kiosk urls', function () {
    expect(AttendanceToken::fromScannedValue(
        'https://clinic.example.com/attendance/open?token=482193',
    ))->toBe('482193')
        ->and(AttendanceToken::fromScannedValue(
            '/attendance/open?token=AbCdEf0123456789AbCdEf0123456789',
        ))->toBe('abcdef0123456789abcdef0123456789')
        ->and(AttendanceToken::fromScannedValue(
            'https://clinic.example.com/attendance/scan?token=900111',
        ))->toBe('900111');
});

test('empty values become an empty token', function () {
    expect(AttendanceToken::fromScannedValue(null))->toBe('')
        ->and(AttendanceToken::fromScannedValue(''))->toBe('');
});
