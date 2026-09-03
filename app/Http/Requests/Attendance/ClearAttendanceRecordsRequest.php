<?php

namespace App\Http\Requests\Attendance;

use App\Models\Attendance;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ClearAttendanceRecordsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('deleteAny', Attendance::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date', 'required_without_all:from,to'],
            'from' => ['nullable', 'date', 'required_with:to'],
            'to' => ['nullable', 'date', 'required_with:from'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
