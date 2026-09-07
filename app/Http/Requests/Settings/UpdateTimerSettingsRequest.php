<?php

namespace App\Http\Requests\Settings;

use App\Models\Attendance;
use App\Services\AttendanceSettings;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimerSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manageKiosk', Attendance::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $settings = app(AttendanceSettings::class);

        return [
            'qr_ttl_seconds' => [
                'required',
                'integer',
                'min:'.$settings->minTtl(),
                'max:'.$settings->maxTtl(),
            ],
        ];
    }
}
