<?php

namespace App\Http\Requests\Shift;

trait ValidatesShiftHours
{
    /**
     * @return array<string, mixed>
     */
    protected function shiftRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'grace_period_minutes' => ['required', 'integer', 'min:0', 'max:180'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['start_time', 'end_time'] as $field) {
            $value = $this->input($field);

            if (is_string($value) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $value) === 1) {
                $this->merge([$field => substr($value, 0, 5)]);
            }
        }
    }
}
