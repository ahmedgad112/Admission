<?php

namespace App\Http\Requests\Attendance;

use App\Models\AttendanceDay;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttendanceDayRequest extends FormRequest
{
    use ValidatesAttendanceDayRoster;

    public function authorize(): bool
    {
        $day = $this->route('attendanceDay');

        return $day instanceof AttendanceDay && ($this->user()?->can('update', $day) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $day = $this->route('attendanceDay');
        $user = $this->user();

        return [
            ...$this->staffRules(),
            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
                Rule::when(
                    $user !== null && ! $user->isSuperAdmin(),
                    Rule::in(array_filter([$user?->branch_id])),
                ),
            ],
            'date' => [
                'required',
                'date',
                $this->uniqueDateRule($day instanceof AttendanceDay ? $day->id : null),
            ],
        ];
    }
}
