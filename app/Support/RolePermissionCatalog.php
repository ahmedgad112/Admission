<?php

namespace App\Support;

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\RolePermissionSet;
use Illuminate\Support\Facades\Schema;

class RolePermissionCatalog
{
    /** @var array<string, list<Permission>>|null */
    private ?array $resolved = null;

    /**
     * @return list<Permission>
     */
    public function permissionsFor(UserRole $role): array
    {
        return $this->all()[$role->value];
    }

    /**
     * @return array<string, list<string>>
     */
    public function permissionValuesByRole(): array
    {
        return collect($this->all())
            ->map(fn (array $permissions): array => array_map(
                fn (Permission $permission): string => $permission->value,
                $permissions,
            ))
            ->all();
    }

    /**
     * @return array<string, list<Permission>>
     */
    public function all(): array
    {
        return $this->resolved ??= $this->resolve();
    }

    public function forget(): void
    {
        $this->resolved = null;
    }

    /**
     * @param  array<string, list<string>>  $rolePermissions
     */
    public function sync(array $rolePermissions): void
    {
        foreach (UserRole::assignable() as $role) {
            RolePermissionSet::query()->updateOrCreate(
                ['role' => $role->value],
                ['permissions' => array_values(array_unique($rolePermissions[$role->value] ?? []))],
            );
        }

        $this->forget();
    }

    /**
     * @return array<string, list<Permission>>
     */
    private function resolve(): array
    {
        $overrides = [];

        if (Schema::hasTable('role_permission_sets')) {
            $overrides = RolePermissionSet::query()
                ->get()
                ->mapWithKeys(fn (RolePermissionSet $set): array => [
                    $set->role->value => $set->resolvedPermissions(),
                ])
                ->all();
        }

        return collect(UserRole::cases())
            ->mapWithKeys(fn (UserRole $role): array => [
                $role->value => $role === UserRole::SuperAdmin
                    ? Permission::cases()
                    : ($overrides[$role->value] ?? $role->defaultPermissions()),
            ])
            ->all();
    }
}
