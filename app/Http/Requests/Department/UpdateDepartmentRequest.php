<?php

namespace App\Http\Requests\Department;

use App\Models\Department;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $department = $this->route('department');

        return $department instanceof Department
            && ($this->user()?->can('update', $department) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $department = $this->route('department');
        abort_unless($department instanceof Department, 404);

        return [
            'name' => ['required', 'string', 'max:255'],
            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
                Rule::when(
                    $this->user() !== null && ! $this->user()->isSuperAdmin(),
                    Rule::in(array_filter([$this->user()?->branch_id])),
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
        $department = $this->route('department');

        if ($user !== null && ! $user->isSuperAdmin() && $user->branch_id !== null) {
            $this->merge(['branch_id' => $user->branch_id]);
        }

        if ($department instanceof Department) {
            $this->merge(['branch_id' => $department->branch_id]);
        }
    }
}
