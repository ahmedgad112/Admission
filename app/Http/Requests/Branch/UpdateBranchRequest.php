<?php

namespace App\Http\Requests\Branch;

use App\Models\Branch;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    use ValidatesBranchLocation;

    public function authorize(): bool
    {
        $branch = $this->route('branch');

        return $branch instanceof Branch && ($this->user()?->can('update', $branch) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->locationRules();
    }
}
