<?php

namespace App\Http\Requests\LeaveRequest;

use App\Enums\LeaveRequestType;
use App\Models\LeaveRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', LeaveRequest::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'type' => ['required', Rule::enum(LeaveRequestType::class)],
            'reason' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return list<\Closure(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $user = $this->user();

                if ($user === null || $validator->errors()->isNotEmpty()) {
                    return;
                }

                $overlaps = LeaveRequest::query()
                    ->where('user_id', $user->id)
                    ->blocking()
                    ->overlapping(
                        $this->string('start_date')->toString(),
                        $this->string('end_date')->toString(),
                    )
                    ->exists();

                if ($overlaps) {
                    $validator->errors()->add(
                        'start_date',
                        __('flash.leave.overlap'),
                    );
                }

                $requestedDays = LeaveRequest::daysBetween(
                    $this->string('start_date')->toString(),
                    $this->string('end_date')->toString(),
                );

                if (! $user->hasEnoughLeaveDays($requestedDays)) {
                    $validator->errors()->add(
                        'end_date',
                        __('flash.leave.exceeds_balance', [
                            'remaining' => $user->remainingLeaveDays(),
                        ]),
                    );
                }
            },
        ];
    }
}
