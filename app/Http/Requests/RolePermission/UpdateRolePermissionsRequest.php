<?php

namespace App\Http\Requests\RolePermission;

use App\Enums\HomePage;
use App\Enums\Permission;
use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', Role::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $roles = $this->input('roles', []);

        if (! is_array($roles)) {
            return;
        }

        $this->merge([
            'roles' => collect($roles)
                ->map(fn (mixed $permissions): array => is_array($permissions)
                    ? array_values(array_filter(
                        $permissions,
                        fn (mixed $permission): bool => is_string($permission) && $permission !== '',
                    ))
                    : [])
                ->all(),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'roles' => ['required', 'array'],
            'homes' => ['required', 'array'],
            'names' => ['nullable', 'array'],
        ];

        foreach ($this->editableRoles() as $role) {
            $rules["roles.{$role->slug}"] = ['present', 'array'];
            $rules["roles.{$role->slug}.*"] = ['string', Rule::enum(Permission::class)];
            $rules["homes.{$role->slug}"] = ['required', 'string', Rule::enum(HomePage::class)];
            $rules["names.{$role->slug}"] = ['nullable', 'string', 'max:120'];
        }

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                foreach ($this->editableRoles() as $role) {
                    $home = HomePage::tryFrom((string) $this->input("homes.{$role->slug}"));

                    if ($home === null) {
                        continue;
                    }

                    $permissions = $this->input("roles.{$role->slug}", []);

                    if (! $this->homeIsAllowed($home, $permissions)) {
                        $validator->errors()->add(
                            "homes.{$role->slug}",
                            __('permissions.home_requires_access'),
                        );
                    }
                }
            },
        ];
    }

    /**
     * @return array<string, list<string>>
     */
    public function rolePermissions(): array
    {
        /** @var array<string, list<string>> $roles */
        $roles = $this->validated('roles');

        return collect($this->editableRoles())
            ->mapWithKeys(fn (Role $role): array => [
                $role->slug => array_values(array_unique($roles[$role->slug] ?? [])),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function roleHomes(): array
    {
        /** @var array<string, string> $homes */
        $homes = $this->validated('homes');

        return collect($this->editableRoles())
            ->mapWithKeys(fn (Role $role): array => [
                $role->slug => $homes[$role->slug] ?? HomePage::Dashboard->value,
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function roleNames(): array
    {
        /** @var array<string, string|null> $names */
        $names = $this->validated('names') ?? [];

        return collect($names)
            ->filter(fn (mixed $name): bool => is_string($name) && trim($name) !== '')
            ->map(fn (string $name): string => trim($name))
            ->all();
    }

    /**
     * @return list<Role>
     */
    private function editableRoles(): array
    {
        return Role::query()->assignable()->ordered()->get()->all();
    }

    /**
     * @param  list<string>  $permissions
     */
    private function homeIsAllowed(HomePage $home, array $permissions): bool
    {
        $required = $home->permission()->value;
        $staffHome = $home === HomePage::Staff;
        $hasStaffAccess = in_array(Permission::ViewStaff->value, $permissions, true)
            || in_array(Permission::ManageStaff->value, $permissions, true)
            || in_array(Permission::ViewTeamAttendance->value, $permissions, true);

        if ($staffHome && $hasStaffAccess) {
            return true;
        }

        return in_array($required, $permissions, true);
    }
}
