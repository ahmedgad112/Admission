<?php

namespace App\Enums;

enum Permission: string
{
    case ViewDashboard = 'view_dashboard';
    case ScanAttendance = 'scan_attendance';
    case ViewStaff = 'view_staff';
    case ManageStaff = 'manage_staff';
    case ManagePermissions = 'manage_permissions';
    case ManageShifts = 'manage_shifts';
    case ViewRoster = 'view_roster';
    case ManageRoster = 'manage_roster';
    case ManageBranches = 'manage_branches';
    case ManageKiosk = 'manage_kiosk';
    case ViewAttendance = 'view_attendance';
    case ViewTeamAttendance = 'view_team_attendance';
    case ViewActivityLog = 'view_activity_log';
    case ViewTasks = 'view_tasks';
    case ManageTasks = 'manage_tasks';
    case ViewLeaveRequests = 'view_leave_requests';
    case ReviewLeaveRequests = 'review_leave_requests';

    public function label(): string
    {
        return __("permissions.{$this->value}");
    }

    public function description(): string
    {
        return __("permissions.{$this->value}_help");
    }

    public function group(): string
    {
        return match ($this) {
            self::ViewDashboard, self::ScanAttendance => 'workspace',
            self::ViewStaff, self::ManageStaff, self::ManagePermissions => 'people',
            self::ManageShifts, self::ViewRoster, self::ManageRoster, self::ManageBranches, self::ManageKiosk => 'operations',
            self::ViewAttendance, self::ViewTeamAttendance, self::ViewActivityLog => 'records',
            self::ViewTasks, self::ManageTasks, self::ViewLeaveRequests, self::ReviewLeaveRequests => 'work',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array{value: string, label: string, description: string, group: string}
     */
    public function toOption(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'description' => $this->description(),
            'group' => $this->group(),
        ];
    }
}
