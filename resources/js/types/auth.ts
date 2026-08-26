export type UserRole = 'super_admin' | 'branch_admin' | 'manager' | 'employee';

export type UserPermission =
    | 'manage_kiosk'
    | 'manage_staff'
    | 'manage_tasks'
    | 'view_team_attendance'
    | 'review_leave_requests';

export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    role?: UserRole;
    permissions?: UserPermission[];
    branch_id?: number | null;
    department_id?: number | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type AppPermissions = {
    managePermissions: boolean;
    manageKiosk: boolean;
    manageStaff: boolean;
    manageTasks: boolean;
    viewTeamAttendance: boolean;
    reviewLeaveRequests: boolean;
};
