<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use App\Services\LeaveDaysAdjuster;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BulkAdjustLeaveDaysRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_ids' => ['required', 'array', 'min:1', 'max:500'],
            'user_ids.*' => ['integer', 'distinct'],
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

                $actor = $this->user();

                if ($actor === null) {
                    return;
                }

                /** @var list<int> $ids */
                $ids = $this->validated('user_ids');
                $adjuster = app(LeaveDaysAdjuster::class);
                $staff = $adjuster->adjustable($actor, $ids);

                if ($staff->count() !== count($ids)) {
                    $validator->errors()->add('user_ids', __('flash.staff.leave_days_bulk_forbidden'));

                    return;
                }

                $direction = $this->string('direction')->toString();
                $days = $this->integer('days');

                foreach ($staff as $member) {
                    if ($adjuster->balanceAfter($member->leave_days, $direction, $days) === null) {
                        $validator->errors()->add('days', __('flash.staff.leave_days_bulk_bounds', [
                            'name' => $member->name,
                        ]));

                        return;
                    }
                }
            },
        ];
    }
}
