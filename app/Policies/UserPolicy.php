<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewTeamAttendance() || $user->canManageStaff();
    }

    public function view(User $user, User $staff): bool
    {
        return User::query()->whereKey($staff->id)->visibleTo($user)->exists();
    }

    public function create(User $user): bool
    {
        return $user->canManageStaff();
    }

    public function update(User $user, User $staff): bool
    {
        if (! $user->canManageStaff() || ! $this->view($user, $staff)) {
            return false;
        }

        return $user->isSuperAdmin() || ! $staff->isSuperAdmin();
    }

    public function delete(User $user, User $staff): bool
    {
        if ($user->is($staff)) {
            return false;
        }

        return $this->update($user, $staff);
    }
}
