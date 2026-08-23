<?php

namespace App\Http\Requests\Attendance;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\AttendanceDay;
use Closure;
use Illuminate\Validation\Rule;

trait ValidatesAttendanceDayRoster
{
    /**
     * @return array<string, mixed>
     */
    protected function staffRules(): array
    {
        return [
            'staff_ids' => ['required', 'array', 'min:1'],
            'staff_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query
                        ->where('branch_id', $this->integer('branch_id'))
                        ->where('status', UserStatus::Active->value)
                        ->whereIn('role', [
                            UserRole::BranchAdmin->value,
                            UserRole::Manager->value,
                            UserRole::Employee->value,
                        ]),
                ),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'staff_ids.required' => 'Select the staff who are on duty.',
            'staff_ids.min' => 'Select the staff who are on duty.',
        ];
    }

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
