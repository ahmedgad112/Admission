<?php

namespace App\Enums;

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
     * @return list<Permission>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::SuperAdmin => Permission::cases(),
            self::BranchAdmin => [
                Permission::ManageKiosk,
                Permission::ManageStaff,
                Permission::ManageTasks,
                Permission::ViewTeamAttendance,
                Permission::ReviewLeaveRequests,
            ],
            self::Manager => [
                Permission::ManageTasks,
                Permission::ViewTeamAttendance,
                Permission::ReviewLeaveRequests,
            ],
            self::Employee => [],
        };
    }

    /**
     * @return list<string>
     */
    public function permissionValues(): array
    {
        return array_map(
            fn (Permission $permission): string => $permission->value,
            $this->permissions(),
        );
    }

    public function canManageKiosk(): bool
    {
        return $this->hasPermission(Permission::ManageKiosk);
    }

    public function canManageStaff(): bool
    {
        return $this->hasPermission(Permission::ManageStaff);
    }

    public function canManageTasks(): bool
    {
        return $this->hasPermission(Permission::ManageTasks);
    }

    public function canViewTeamAttendance(): bool
    {
        return $this->hasPermission(Permission::ViewTeamAttendance);
    }

    public function canReviewLeaveRequests(): bool
    {
        return $this->hasPermission(Permission::ReviewLeaveRequests);
    }

    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, $this->permissions(), true);
    }
}
