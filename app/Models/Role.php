<?php

namespace App\Models;

use App\Concerns\LogsActivity;
use App\Enums\HomePage;
use App\Enums\Permission;
use App\Enums\UserRole;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property list<string> $permissions
 * @property string $home_page
 * @property bool $is_system
 * @property int $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'slug',
    'name',
    'permissions',
    'home_page',
    'is_system',
    'sort',
])]
class Role extends Model
{
    /** @use HasFactory<RoleFactory> */
    use HasFactory, LogsActivity;

    public const SUPER_ADMIN = 'super_admin';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_system' => 'boolean',
            'sort' => 'integer',
        ];
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->slug === self::SUPER_ADMIN;
    }

    public function isLocked(): bool
    {
        return $this->isSuperAdmin();
    }

    public function label(): string
    {
        $key = "roles.{$this->slug}";
        $translated = __($key);

        return $translated !== $key ? $translated : $this->name;
    }

    public function homePage(): HomePage
    {
        if ($this->isSuperAdmin()) {
            return HomePage::Dashboard;
        }

        return HomePage::tryFrom($this->home_page) ?? HomePage::Dashboard;
    }

    /**
     * @return list<Permission>
     */
    public function resolvedPermissions(): array
    {
        if ($this->isSuperAdmin()) {
            return Permission::cases();
        }

        return array_values(array_filter(
            array_map(
                fn (mixed $permission): ?Permission => is_string($permission)
                    ? Permission::tryFrom($permission)
                    : null,
                $this->permissions ?? [],
            ),
        ));
    }

    /**
     * @return list<string>
     */
    public function permissionValues(): array
    {
        return array_map(
            fn (Permission $permission): string => $permission->value,
            $this->resolvedPermissions(),
        );
    }

    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, $this->resolvedPermissions(), true);
    }

    /**
     * @return array{id: int, value: string, slug: string, name: string, label: string, locked: bool, is_system: bool, permissions: list<string>, home_page: string}
     */
    public function toOption(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->slug,
            'slug' => $this->slug,
            'name' => $this->name,
            'label' => $this->label(),
            'locked' => $this->isLocked(),
            'is_system' => $this->is_system,
            'permissions' => $this->permissionValues(),
            'home_page' => $this->homePage()->value,
        ];
    }

    /**
     * @param  Builder<Role>  $query
     * @return Builder<Role>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('name');
    }

    /**
     * @param  Builder<Role>  $query
     * @return Builder<Role>
     */
    public function scopeAssignable(Builder $query): Builder
    {
        return $query->where('slug', '!=', self::SUPER_ADMIN);
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::query()->where('slug', $slug)->first();
    }

    public static function requireBySlug(string $slug): self
    {
        return static::findBySlug($slug) ?? static::ensureSystemRoles()->first(
            fn (self $role): bool => $role->slug === $slug,
        ) ?? throw new RuntimeException("Missing role [{$slug}].");
    }

    /**
     * @return Collection<int, self>
     */
    public static function ensureSystemRoles(): Collection
    {
        $roles = collect();

        foreach (UserRole::cases() as $index => $seed) {
            $roles->push(static::query()->firstOrCreate(
                ['slug' => $seed->value],
                [
                    'name' => match ($seed) {
                        UserRole::SuperAdmin => 'Super Admin',
                        UserRole::BranchAdmin => 'Branch Admin',
                        UserRole::Manager => 'Manager',
                        UserRole::Employee => 'Employee',
                    },
                    'permissions' => $seed === UserRole::SuperAdmin
                        ? []
                        : $seed->defaultPermissionValues(),
                    'home_page' => $seed->defaultHomePage()->value,
                    'is_system' => true,
                    'sort' => $index,
                ],
            ));
        }

        return $roles;
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : 'role';
        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->when($ignoreId !== null, fn (Builder $query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    protected function activityName(): string
    {
        return $this->name;
    }
}
