<?php

namespace App\Enums;

enum Permission: string
{
    case ManageKiosk = 'manage_kiosk';
    case ManageStaff = 'manage_staff';
    case ManageTasks = 'manage_tasks';
    case ViewTeamAttendance = 'view_team_attendance';
    case ReviewLeaveRequests = 'review_leave_requests';

    public function label(): string
    {
        return __("permissions.{$this->value}");
    }

    public function description(): string
    {
        return __("permissions.{$this->value}_help");
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
