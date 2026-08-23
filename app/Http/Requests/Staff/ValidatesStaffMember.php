<?php

namespace App\Http\Requests\Staff;

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

trait ValidatesStaffMember
{
    /**
     * @return array<string, mixed>
     */
    protected function staffMemberRules(bool $updating = false): array
    {
        $user = $this->user();
        $staff = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($updating && $staff instanceof User ? $staff->id : null),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [
                $updating ? 'nullable' : 'required',
                'string',
                Password::default(),
            ],
            'role' => [
                'required',
                Rule::enum(UserRole::class),
                Rule::when(
                    $user !== null && ! $user->isSuperAdmin(),
                    Rule::in([
                        UserRole::BranchAdmin->value,
                        UserRole::Manager->value,
                        UserRole::Employee->value,
                    ]),
                ),
            ],
            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
                Rule::when(
                    $user !== null && ! $user->isSuperAdmin(),
                    Rule::in(array_filter([$user?->branch_id])),
                ),
            ],
            'department_id' => [
                $user !== null && $user->limitsRecordsToTeam() ? 'required' : 'nullable',
                'integer',
                Rule::exists('departments', 'id')->where(
                    fn ($query) => $query->where('branch_id', $this->integer('branch_id')),
                ),
                Rule::when(
                    $user !== null && $user->limitsRecordsToTeam(),
                    Rule::in(array_filter([$user->department_id])),
                ),
            ],
            'shift_id' => ['nullable', 'integer', 'exists:shifts,id'],
            'status' => ['required', Rule::enum(UserStatus::class)],
            'leave_days' => ['required', 'integer', 'min:0', 'max:365'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::enum(Permission::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $permissions = $this->input('permissions', []);

        $this->merge([
            'permissions' => is_array($permissions)
                ? array_values(array_filter(
                    $permissions,
                    fn (mixed $permission): bool => is_string($permission) && $permission !== '',
                ))
                : [],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function staffAttributes(bool $updating = false): array
    {
        $actor = $this->user();
        abort_unless($actor !== null, 403);

        $staff = $this->route('user');

        return [
            ...$this->safe()->except(['password', 'permissions']),
            'permissions' => User::extraPermissions(
                UserRole::from($this->validated('role')),
                $this->validated('permissions') ?? [],
                $actor,
                $updating && $staff instanceof User ? $staff : null,
            ),
        ];
    }
}
