<?php

namespace App\Support;

use App\Enums\HomePage;
use App\Enums\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class RolePermissionCatalog
{
    /** @var Collection<string, Role>|null */
    private ?Collection $roles = null;

    public function role(string $slug): ?Role
    {
        return $this->all()->get($slug);
    }

    public function roleById(int $id): ?Role
    {
        return $this->all()->first(fn (Role $role): bool => $role->id === $id);
    }

    /**
     * @return list<Permission>
     */
    public function permissionsFor(Role|string $role): array
    {
        $resolved = $this->resolveRole($role);

        return $resolved?->resolvedPermissions() ?? [];
    }

    public function homePageFor(Role|string $role): HomePage
    {
        $resolved = $this->resolveRole($role);

        return $resolved?->homePage() ?? HomePage::Dashboard;
    }

    /**
     * @return array<string, list<string>>
     */
    public function permissionValuesByRole(): array
    {
        return $this->all()
            ->mapWithKeys(fn (Role $role): array => [
                $role->slug => $role->permissionValues(),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function homePagesByRole(): array
    {
        return $this->all()
            ->mapWithKeys(fn (Role $role): array => [
                $role->slug => $role->homePage()->value,
            ])
            ->all();
    }

    /**
     * @return Collection<string, Role>
     */
    public function all(): Collection
    {
        return $this->roles ??= $this->load();
    }

    public function forget(): void
    {
        $this->roles = null;
    }

    /**
     * @param  array<string, list<string>>  $rolePermissions  keyed by slug
     * @param  array<string, string>  $homes  keyed by slug
     */
    public function sync(array $rolePermissions, array $homes = []): void
    {
        foreach ($this->all()->where(fn (Role $role): bool => ! $role->isLocked()) as $role) {
            $home = HomePage::tryFrom($homes[$role->slug] ?? '')
                ?? $role->homePage();

            $role->update([
                'permissions' => array_values(array_unique($rolePermissions[$role->slug] ?? [])),
                'home_page' => $home->value,
            ]);
        }

        $this->forget();
    }

    private function resolveRole(Role|string $role): ?Role
    {
        if ($role instanceof Role) {
            return $this->all()->get($role->slug) ?? $role;
        }

        return $this->all()->get($role);
    }

    /**
     * @return Collection<string, Role>
     */
    private function load(): Collection
    {
        if (! Schema::hasTable('roles')) {
            return collect();
        }

        Role::ensureSystemRoles();

        return Role::query()
            ->ordered()
            ->get()
            ->keyBy('slug');
    }
}
