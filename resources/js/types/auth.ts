export type UserRole = string;

export type UserPermission =
    | 'view_dashboard'
    | 'scan_attendance'
    | 'view_staff'
    | 'manage_staff'
    | 'manage_permissions'
    | 'manage_shifts'
    | 'view_roster'
    | 'manage_roster'
    | 'manage_branches'
    | 'manage_kiosk'
    | 'view_attendance'
    | 'view_team_attendance'
    | 'view_activity_log'
    | 'view_tasks'
    | 'manage_tasks'
    | 'view_leave_requests'
    | 'review_leave_requests';

export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    role?: UserRole;
    role_label?: string;
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
    viewDashboard: boolean;
    scanAttendance: boolean;
    viewStaff: boolean;
    manageStaff: boolean;
    managePermissions: boolean;
    manageShifts: boolean;
    viewRoster: boolean;
    manageRoster: boolean;
    manageBranches: boolean;
    manageKiosk: boolean;
    viewAttendance: boolean;
    viewTeamAttendance: boolean;
    viewActivityLog: boolean;
    viewTasks: boolean;
    manageTasks: boolean;
    viewLeaveRequests: boolean;
    reviewLeaveRequests: boolean;
    impersonate: boolean;
    [key: string]: boolean;
};

export type ImpersonationState = {
    active: boolean;
    impersonator: { id: number; name: string } | null;
};
