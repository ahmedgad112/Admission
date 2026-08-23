<?php

namespace App\Http\Requests\Attendance;

use App\Enums\QrSessionType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateQrSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageKiosk() ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'type' => ['required', Rule::enum(QrSessionType::class)],
        ];
    }
}
