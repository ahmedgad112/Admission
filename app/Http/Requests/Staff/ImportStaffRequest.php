<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'file' => ['required', 'file', 'mimes:xlsx,csv,txt', 'max:5120'],
            'department_id' => [
                'nullable',
                'integer',
                Rule::exists('departments', 'id')->where(function ($query) use ($user): void {
                    if ($user === null || $user->isSuperAdmin()) {
                        return;
                    }

                    $query->where('branch_id', $user->branch_id);

                    if ($user->limitsRecordsToTeam()) {
                        $query->whereIn('id', $user->visibleTeamDepartmentIds());
                    }
                }),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $departmentId = $this->input('department_id');

        if ($departmentId === '' || $departmentId === null) {
            $this->merge(['department_id' => null]);
        }
    }
}
