<?php

namespace App\Http\Requests\Attendance;

use App\Models\AttendanceDay;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceDayRequest extends FormRequest
{
    use ValidatesAttendanceDayRoster;

    public function authorize(): bool
    {
        return $this->user()?->can('create', AttendanceDay::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
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
            'date' => ['required', 'date', $this->uniqueDateRule()],
            'check_in_starts_at' => ['required', 'date_format:H:i'],
            'check_in_ends_at' => ['required', 'date_format:H:i', 'after:check_in_starts_at'],
            'check_out_starts_at' => ['required', 'date_format:H:i', 'after_or_equal:check_in_ends_at'],
            'check_out_ends_at' => ['required', 'date_format:H:i', 'after:check_out_starts_at'],
        ];
    }
}
