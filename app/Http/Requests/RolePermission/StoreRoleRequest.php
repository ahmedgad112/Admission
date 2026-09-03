<?php

namespace App\Http\Requests\RolePermission;

use App\Enums\HomePage;
use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Role::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('permissions')) {
            $this->merge([
                'permissions' => UserRole::Employee->defaultPermissionValues(),
            ]);
        }

        if (! $this->filled('home_page')) {
            $this->merge([
                'home_page' => HomePage::Dashboard->value,
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', Rule::enum(Permission::class)],
            'home_page' => ['required', 'string', Rule::enum(HomePage::class)],
        ];
    }

    /**
     * @return list<\Closure(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $home = HomePage::tryFrom((string) $this->input('home_page'));
                $permissions = $this->input('permissions', []);

                if ($home === null || ! is_array($permissions)) {
                    return;
                }

                $required = $home->permission()->value;
                $staffHome = $home === HomePage::Staff;
                $hasStaffAccess = in_array(Permission::ViewStaff->value, $permissions, true)
                    || in_array(Permission::ManageStaff->value, $permissions, true)
                    || in_array(Permission::ViewTeamAttendance->value, $permissions, true);

                if ($staffHome && $hasStaffAccess) {
                    return;
                }

                if (! in_array($required, $permissions, true)) {
                    $validator->errors()->add('home_page', __('permissions.home_requires_access'));
                }
            },
        ];
    }

    /**
     * @return array{name: string, permissions: list<string>, home_page: string, slug: string, is_system: bool, sort: int}
     */
    public function roleAttributes(): array
    {
        $name = trim((string) $this->validated('name'));

        return [
            'name' => $name,
            'slug' => Role::uniqueSlug($name),
            'permissions' => array_values(array_unique($this->validated('permissions'))),
            'home_page' => $this->validated('home_page'),
            'is_system' => false,
            'sort' => ((int) Role::query()->max('sort')) + 1,
        ];
    }
}
