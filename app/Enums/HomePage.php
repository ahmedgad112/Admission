<?php

namespace App\Enums;

use App\Models\User;

enum HomePage: string
{
    case Dashboard = 'dashboard';
    case Scan = 'attendance.scan';
    case Staff = 'staff.index';
    case Permissions = 'permissions.edit';
    case Shifts = 'shifts.index';
    case Roster = 'attendance.days.index';
    case Branches = 'branches.index';
    case Kiosk = 'attendance.kiosk';
    case Records = 'attendance.index';
    case Tasks = 'tasks.index';
    case Leave = 'leave-requests.index';
    case ActivityLog = 'activity-logs.index';

    public function routeName(): string
    {
        return $this->value;
    }

    public function label(): string
    {
        return match ($this) {
            self::Dashboard => __('nav.dashboard'),
            self::Scan => __('nav.scan'),
            self::Staff => __('nav.staff'),
            self::Permissions => __('nav.permissions'),
            self::Shifts => __('nav.shifts'),
            self::Roster => __('nav.roster'),
            self::Branches => __('nav.branches'),
            self::Kiosk => __('nav.kiosk'),
            self::Records => __('nav.records'),
            self::Tasks => __('nav.tasks'),
            self::Leave => __('nav.leave'),
            self::ActivityLog => __('nav.activity_log'),
        };
    }

    public function permission(): Permission
    {
        return match ($this) {
            self::Dashboard => Permission::ViewDashboard,
            self::Scan => Permission::ScanAttendance,
            self::Staff => Permission::ViewStaff,
            self::Permissions => Permission::ManagePermissions,
            self::Shifts => Permission::ManageShifts,
            self::Roster => Permission::ViewRoster,
            self::Branches => Permission::ManageBranches,
            self::Kiosk => Permission::ManageKiosk,
            self::Records => Permission::ViewAttendance,
            self::Tasks => Permission::ViewTasks,
            self::Leave => Permission::ViewLeaveRequests,
            self::ActivityLog => Permission::ViewActivityLog,
        };
    }

    public function isAllowedFor(User $user): bool
    {
        return match ($this) {
            self::Staff => $user->canViewStaff()
                || $user->canManageStaff()
                || $user->canViewTeamAttendance(),
            self::Scan => $user->canScanAttendance(),
            default => $user->hasPermission($this->permission()),
        };
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(fn (self $page): array => [
            'value' => $page->value,
            'label' => $page->label(),
        ], self::cases());
    }
}
