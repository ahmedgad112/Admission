<?php

namespace App\Http\Requests\Attendance;

use App\Models\AttendanceDay;
use Closure;

trait ValidatesAttendanceDayRoster
{
    protected function uniqueDateRule(?int $ignoreId = null): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($ignoreId): void {
            $alreadyExists = AttendanceDay::query()
                ->where('branch_id', $this->integer('branch_id'))
                ->whereDate('date', $value)
                ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists();

            if ($alreadyExists) {
                $fail('A roster already exists for this branch on that date.');
            }
        };
    }
}
