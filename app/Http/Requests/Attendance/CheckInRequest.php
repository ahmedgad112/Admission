<?php

namespace App\Http\Requests\Attendance;

use App\Models\Attendance;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    use NormalizesAttendanceToken;

    public function authorize(): bool
    {
        return $this->user()?->can('create', Attendance::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => $this->tokenRules(),
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'device_uuid' => ['required', 'uuid'],
        ];
    }
}
