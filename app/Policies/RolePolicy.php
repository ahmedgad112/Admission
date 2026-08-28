<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManagePermissions();
    }

    public function create(User $user): bool
    {
        return $user->canManagePermissions();
    }

    public function update(User $user, ?Role $role = null): bool
    {
        return $user->canManagePermissions();
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->canManagePermissions()
            && ! $role->is_system
            && ! $role->isLocked()
            && $role->users()->doesntExist();
    }
}
