<?php

namespace App\Http\Requests\Staff;

use App\Enums\Permission;
use App\Enums\UserStatus;
use App\Models\Role;
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

        $assignableRoleIds = Role::query()
            ->when(
                $user === null || ! $user->isSuperAdmin(),
                fn ($query) => $query->where('slug', '!=', Role::SUPER_ADMIN),
            )
            ->pluck('id')
            ->all();

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
                'string',
                Rule::exists('roles', 'slug')->where(
                    fn ($query) => $query->whereIn('id', $assignableRoleIds),
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
                    Rule::in($user->visibleTeamDepartmentIds()),
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
        $role = Role::requireBySlug($this->validated('role'));

        return [
            ...$this->safe()->except(['password', 'permissions', 'role']),
            'role_id' => $role->id,
            'permissions' => User::extraPermissions(
                $role,
                $this->validated('permissions') ?? [],
                $actor,
                $updating && $staff instanceof User ? $staff : null,
            ),
        ];
    }
}
