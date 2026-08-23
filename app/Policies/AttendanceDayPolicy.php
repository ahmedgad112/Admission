<?php

namespace App\Policies;

use App\Models\AttendanceDay;
use App\Models\User;

class AttendanceDayPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageKiosk();
    }

    public function view(User $user, AttendanceDay $attendanceDay): bool
    {
        return $user->canManageKiosk() && $user->canAccessBranch($attendanceDay->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->canManageKiosk();
    }

    public function update(User $user, AttendanceDay $attendanceDay): bool
    {
        return $user->canManageKiosk() && $user->canAccessBranch($attendanceDay->branch_id);
    }

    public function delete(User $user, AttendanceDay $attendanceDay): bool
    {
        return $user->canManageKiosk() && $user->canAccessBranch($attendanceDay->branch_id);
    }
}
