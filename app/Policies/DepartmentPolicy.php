<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageStaff();
    }

    public function view(User $user, Department $department): bool
    {
        return $this->canAccess($user, $department);
    }

    public function create(User $user): bool
    {
        return $user->canManageStaff() && ($user->isSuperAdmin() || $user->branch_id !== null);
    }

    public function update(User $user, Department $department): bool
    {
        return $user->canManageStaff() && $this->canAccess($user, $department);
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->update($user, $department);
    }

    private function canAccess(User $user, Department $department): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->branch_id !== null && $department->branch_id === $user->branch_id;
    }
}
