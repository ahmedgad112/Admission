<?php

namespace App\Policies;

use App\Models\User;

class RolePermissionSetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManagePermissions();
    }

    public function update(User $user): bool
    {
        return $user->canManagePermissions();
    }
}
