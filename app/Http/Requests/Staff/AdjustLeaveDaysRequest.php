<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AdjustLeaveDaysRequest extends FormRequest
{
    public function authorize(): bool
    {
        $staff = $this->route('user');

        return $staff instanceof User && ($this->user()?->can('update', $staff) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'direction' => ['required', Rule::in(['add', 'deduct'])],
            'days' => ['required', 'integer', 'min:1', 'max:365'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return list<\Closure(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $staff = $this->route('user');

                if (! $staff instanceof User) {
                    return;
                }

                $days = $this->integer('days');
                $next = $this->string('direction')->toString() === 'deduct'
                    ? $staff->leave_days - $days
                    : $staff->leave_days + $days;

                if ($next < 0) {
                    $validator->errors()->add('days', __('flash.staff.leave_days_below_zero', [
                        'available' => $staff->leave_days,
                    ]));
                }

                if ($next > 365) {
                    $validator->errors()->add('days', __('flash.staff.leave_days_above_max', [
                        'max' => 365,
                    ]));
                }
            },
        ];
    }
}
