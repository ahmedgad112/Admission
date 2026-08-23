<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageTasks();
    }

    public function view(User $user, Department $department): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->limitsRecordsToBranch()) {
            return $department->branch_id === $user->branch_id;
        }

        return $department->id === $user->department_id;
    }
}
