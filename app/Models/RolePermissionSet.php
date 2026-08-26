<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\UserRole;
use Database\Factories\RolePermissionSetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property UserRole $role
 * @property list<string> $permissions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['role', 'permissions'])]
class RolePermissionSet extends Model
{
    /** @use HasFactory<RolePermissionSetFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'permissions' => 'array',
        ];
    }

    /**
     * @return list<Permission>
     */
    public function resolvedPermissions(): array
    {
        return array_values(array_filter(
            array_map(
                fn (mixed $permission): ?Permission => is_string($permission)
                    ? Permission::tryFrom($permission)
                    : null,
                $this->permissions ?? [],
            ),
        ));
    }
}
