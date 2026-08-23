<?php

namespace App\Http\Requests\Shift;

use App\Models\Shift;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{
    use ValidatesShiftHours;

    public function authorize(): bool
    {
        $shift = $this->route('shift');

        return $shift instanceof Shift && ($this->user()?->can('update', $shift) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->shiftRules();
    }
}
