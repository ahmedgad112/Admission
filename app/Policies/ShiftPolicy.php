<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;

class ShiftPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageShifts();
    }

    public function view(User $user, Shift $shift): bool
    {
        return $user->canManageShifts();
    }

    public function create(User $user): bool
    {
        return $user->canManageShifts();
    }

    public function update(User $user, Shift $shift): bool
    {
        return $user->canManageShifts();
    }

    public function delete(User $user, Shift $shift): bool
    {
        return $user->canManageShifts();
    }
}
