<?php

namespace App\Http\Requests\Attendance;

trait NormalizesAttendanceToken
{
    protected function prepareForValidation(): void
    {
        $token = $this->input('token');

        if (! is_string($token)) {
            return;
        }

        $this->merge([
            'token' => strtolower((string) preg_replace('/\s+/', '', $token)),
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
