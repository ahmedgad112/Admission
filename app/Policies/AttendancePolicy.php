<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewAttendance();
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->limitsRecordsToBranch()) {
            return $attendance->branch_id === $user->branch_id;
        }

        if ($user->limitsRecordsToTeam()) {
            return $attendance->user?->department_id === $user->department_id;
        }

        return $attendance->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        if (! $user->canScanAttendance()) {
            return false;
        }

        return $user->branch_id !== null || $user->isSuperAdmin();
    }

    public function record(User $user): bool
    {
        return $user->canViewTeamAttendance();
    }

    public function manageKiosk(User $user): bool
    {
        return $user->canManageKiosk();
    }
}
