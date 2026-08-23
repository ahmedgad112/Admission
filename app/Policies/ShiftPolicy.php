<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;

class ShiftPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageStaff();
    }

    public function view(User $user, Shift $shift): bool
    {
        return $user->canManageStaff();
    }

    public function create(User $user): bool
    {
        return $user->canManageStaff();
    }

    public function update(User $user, Shift $shift): bool
    {
        return $user->canManageStaff();
    }

    public function delete(User $user, Shift $shift): bool
    {
        return $user->canManageStaff();
    }
}
