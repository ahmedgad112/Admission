<?php

namespace App\Http\Requests\Department;

use App\Models\Department;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Department::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        abort_unless($user !== null, 403);

        return [
            'name' => ['required', 'string', 'max:255'],
            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
                Rule::when(
                    ! $user->isSuperAdmin(),
                    Rule::in(array_filter([$user->branch_id])),
                ),
            ],
            'manager_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('branch_id', $this->integer('branch_id')),
                ),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user !== null && ! $user->isSuperAdmin() && $user->branch_id !== null) {
            $this->merge(['branch_id' => $user->branch_id]);
        }
    }
}
