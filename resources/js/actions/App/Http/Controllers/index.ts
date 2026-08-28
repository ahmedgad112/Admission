import QrSessionController from './QrSessionController'
import AttendanceController from './AttendanceController'
import TaskController from './TaskController'
import LocaleController from './LocaleController'
import DashboardController from './DashboardController'
import AttendanceDayController from './AttendanceDayController'
import BranchController from './BranchController'
import ShiftController from './ShiftController'
import DepartmentController from './DepartmentController'
import RolePermissionController from './RolePermissionController'
import ActivityLogController from './ActivityLogController'
import StaffController from './StaffController'
import ImpersonationController from './ImpersonationController'
import LeaveRequestController from './LeaveRequestController'
import Settings from './Settings'
const Controllers = {
    QrSessionController: Object.assign(QrSessionController, QrSessionController),
AttendanceController: Object.assign(AttendanceController, AttendanceController),
TaskController: Object.assign(TaskController, TaskController),
LocaleController: Object.assign(LocaleController, LocaleController),
DashboardController: Object.assign(DashboardController, DashboardController),
AttendanceDayController: Object.assign(AttendanceDayController, AttendanceDayController),
BranchController: Object.assign(BranchController, BranchController),
ShiftController: Object.assign(ShiftController, ShiftController),
DepartmentController: Object.assign(DepartmentController, DepartmentController),
RolePermissionController: Object.assign(RolePermissionController, RolePermissionController),
ActivityLogController: Object.assign(ActivityLogController, ActivityLogController),
StaffController: Object.assign(StaffController, StaffController),
ImpersonationController: Object.assign(ImpersonationController, ImpersonationController),
LeaveRequestController: Object.assign(LeaveRequestController, LeaveRequestController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers