<?php

namespace App\Http\Requests\Shift;

use App\Models\Shift;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    use ValidatesShiftHours;

    public function authorize(): bool
    {
        return $this->user()?->can('create', Shift::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->shiftRules();
    }
}
