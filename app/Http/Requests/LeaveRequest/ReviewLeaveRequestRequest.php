<?php

namespace App\Http\Requests\LeaveRequest;

use App\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        $leaveRequest = $this->route('leaveRequest');

        return $leaveRequest instanceof LeaveRequest
            && ($this->user()?->can('review', $leaveRequest) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::enum(LeaveRequestStatus::class)->only([
                    LeaveRequestStatus::Approved,
                    LeaveRequestStatus::Rejected,
                ]),
            ],
            'review_note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
