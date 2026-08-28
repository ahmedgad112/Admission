<?php

namespace App\Enums;

/**
 * Seed defaults for system roles. Runtime permissions live on App\Models\Role.
 */
enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case BranchAdmin = 'branch_admin';
    case Manager = 'manager';
    case Employee = 'employee';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => __('roles.super_admin'),
            self::BranchAdmin => __('roles.branch_admin'),
            self::Manager => __('roles.manager'),
            self::Employee => __('roles.employee'),
        };
    }

    /**
     * @return list<self>
     */
    public static function assignable(): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $role): bool => $role !== self::SuperAdmin,
        ));
    }

    /**
     * @return list<Permission>
     */
    public function defaultPermissions(): array
    {
        return match ($this) {
            self::SuperAdmin => Permission::cases(),
            self::BranchAdmin => [
                Permission::ViewDashboard,
                Permission::ScanAttendance,
                Permission::ViewStaff,
                Permission::ManageStaff,
                Permission::ManageShifts,
                Permission::ViewRoster,
                Permission::ManageRoster,
                Permission::ManageBranches,
                Permission::ManageKiosk,
                Permission::ViewAttendance,
                Permission::ViewTeamAttendance,
                Permission::ViewActivityLog,
                Permission::ViewTasks,
                Permission::ManageTasks,
                Permission::ViewLeaveRequests,
                Permission::ReviewLeaveRequests,
            ],
            self::Manager => [
                Permission::ViewDashboard,
                Permission::ScanAttendance,
                Permission::ViewStaff,
                Permission::ViewRoster,
                Permission::ViewAttendance,
                Permission::ViewTeamAttendance,
                Permission::ViewTasks,
                Permission::ManageTasks,
                Permission::ViewLeaveRequests,
                Permission::ReviewLeaveRequests,
            ],
            self::Employee => [
                Permission::ViewDashboard,
                Permission::ScanAttendance,
                Permission::ViewRoster,
                Permission::ViewAttendance,
                Permission::ViewTasks,
                Permission::ViewLeaveRequests,
            ],
        };
    }

    public function defaultHomePage(): HomePage
    {
        return HomePage::Dashboard;
    }

    /**
     * @return list<string>
     */
    public function defaultPermissionValues(): array
    {
        return array_map(
            fn (Permission $permission): string => $permission->value,
            $this->defaultPermissions(),
        );
    }
}
