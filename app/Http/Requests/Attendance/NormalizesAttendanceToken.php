<?php

namespace App\Http\Requests\Attendance;

use App\Support\AttendanceToken;

trait NormalizesAttendanceToken
{
    protected function prepareForValidation(): void
    {
        $token = $this->input('token');

        if (! is_string($token)) {
            return;
        }

        $this->merge([
            'token' => AttendanceToken::fromScannedValue($token),
        ]);
    }

    /**
     * @return array<int, string>
     */
    protected function tokenRules(): array
    {
        return ['required', 'string', 'min:6', 'max:32'];
    }
}
