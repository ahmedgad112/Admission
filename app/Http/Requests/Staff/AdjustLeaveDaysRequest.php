<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use App\Services\LeaveDaysAdjuster;
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
            'days' => ['required', 'integer', 'min:1', 'max:'.LeaveDaysAdjuster::MAX],
            'month' => ['nullable', 'date_format:Y-m'],
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

                $month = $this->validated('month');
                $month = is_string($month) && $month !== '' ? $month : now()->format('Y-m');
                $adjuster = app(LeaveDaysAdjuster::class);
                $next = $adjuster->balanceAfter(
                    $adjuster->credit($staff, $month),
                    $this->string('direction')->toString(),
                    $this->integer('days'),
                );

                if ($next === null) {
                    $validator->errors()->add('days', __('flash.staff.leave_days_bulk_bounds', [
                        'name' => $staff->name,
                        'min' => LeaveDaysAdjuster::MIN,
                        'max' => LeaveDaysAdjuster::MAX,
                    ]));
                }
            },
        ];
    }
}
