<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageKiosk();
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->canAccessBranch($branch->id);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->canManageKiosk() && $user->canAccessBranch($branch->id);
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->isSuperAdmin() && $user->canAccessBranch($branch->id);
    }
}
