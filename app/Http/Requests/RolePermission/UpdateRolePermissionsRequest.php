<?php

namespace App\Http\Requests\RolePermission;

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\RolePermissionSet;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', RolePermissionSet::class) ?? false;
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
        ];

        foreach (UserRole::assignable() as $role) {
            $rules["roles.{$role->value}"] = ['present', 'array'];
            $rules["roles.{$role->value}.*"] = ['string', Rule::enum(Permission::class)];
        }

        return $rules;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rolePermissions(): array
    {
        /** @var array<string, list<string>> $roles */
        $roles = $this->validated('roles');

        return collect(UserRole::assignable())
            ->mapWithKeys(fn (UserRole $role): array => [
                $role->value => array_values(array_unique($roles[$role->value] ?? [])),
            ])
            ->all();
    }
}
