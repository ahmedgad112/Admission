<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceDayController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\QrSessionController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::post('locale', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->middleware('permission:view_dashboard')
        ->name('dashboard');

    Route::get('attendance', [AttendanceController::class, 'index'])
        ->middleware('permission:view_attendance')
        ->name('attendance.index');
    Route::get('attendance/export', [AttendanceController::class, 'export'])
        ->middleware('permission:view_attendance')
        ->name('attendance.export');
    Route::put('attendance/entries', [AttendanceController::class, 'syncEntries'])
        ->middleware('permission:view_team_attendance')
        ->name('attendance.entries.sync');
    Route::delete('attendance/records', [AttendanceController::class, 'clearRecords'])
        ->middleware('permission:view_team_attendance')
        ->name('attendance.records.clear');
    Route::get('attendance/scan', [AttendanceController::class, 'scan'])
        ->middleware('permission:scan_attendance')
        ->name('attendance.scan');
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->middleware(['permission:scan_attendance', 'throttle:qr-scan'])
        ->name('attendance.check-in');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])
        ->middleware(['permission:scan_attendance', 'throttle:qr-scan'])
        ->name('attendance.check-out');

    Route::get('attendance/days', [AttendanceDayController::class, 'index'])
        ->middleware('permission:view_roster,manage_roster')
        ->name('attendance.days.index');
    Route::get('attendance/days/create', [AttendanceDayController::class, 'create'])
        ->middleware('permission:manage_roster')
        ->name('attendance.days.create');
    Route::post('attendance/days', [AttendanceDayController::class, 'store'])
        ->middleware('permission:manage_roster')
        ->name('attendance.days.store');
    Route::get('attendance/days/{attendanceDay}/edit', [AttendanceDayController::class, 'edit'])
        ->middleware('permission:manage_roster')
        ->name('attendance.days.edit');
    Route::put('attendance/days/{attendanceDay}', [AttendanceDayController::class, 'update'])
        ->middleware('permission:manage_roster')
        ->name('attendance.days.update');
    Route::delete('attendance/days/{attendanceDay}', [AttendanceDayController::class, 'destroy'])
        ->middleware('permission:manage_roster')
        ->name('attendance.days.destroy');

    Route::get('attendance/kiosk', [QrSessionController::class, 'kiosk'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.kiosk');
    Route::get('attendance/qr-sessions/current', [QrSessionController::class, 'current'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.qr-sessions.current');
    Route::post('attendance/qr-sessions/open', [QrSessionController::class, 'open'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.qr-sessions.open');
    Route::post('attendance/qr-sessions/close', [QrSessionController::class, 'close'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.qr-sessions.close');
    Route::get('attendance/kiosk/pending', [QrSessionController::class, 'pending'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.kiosk.pending');

    Route::get('branches', [BranchController::class, 'index'])
        ->middleware('permission:manage_branches')
        ->name('branches.index');
    Route::get('branches/create', [BranchController::class, 'create'])->name('branches.create');
    Route::post('branches', [BranchController::class, 'store'])->name('branches.store');
    Route::get('branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    Route::put('branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');

    Route::get('shifts', [ShiftController::class, 'index'])
        ->middleware('permission:manage_shifts')
        ->name('shifts.index');
    Route::get('shifts/create', [ShiftController::class, 'create'])
        ->middleware('permission:manage_shifts')
        ->name('shifts.create');
    Route::post('shifts', [ShiftController::class, 'store'])
        ->middleware('permission:manage_shifts')
        ->name('shifts.store');
    Route::get('shifts/{shift}/edit', [ShiftController::class, 'edit'])
        ->middleware('permission:manage_shifts')
        ->name('shifts.edit');
    Route::put('shifts/{shift}', [ShiftController::class, 'update'])
        ->middleware('permission:manage_shifts')
        ->name('shifts.update');
    Route::delete('shifts/{shift}', [ShiftController::class, 'destroy'])
        ->middleware('permission:manage_shifts')
        ->name('shifts.destroy');

    Route::get('departments', [DepartmentController::class, 'index'])
        ->middleware('permission:manage_staff')
        ->name('departments.index');
    Route::get('departments/create', [DepartmentController::class, 'create'])
        ->middleware('permission:manage_staff')
        ->name('departments.create');
    Route::post('departments', [DepartmentController::class, 'store'])
        ->middleware('permission:manage_staff')
        ->name('departments.store');
    Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])
        ->middleware('permission:manage_staff')
        ->name('departments.edit');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])
        ->middleware('permission:manage_staff')
        ->name('departments.update');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])
        ->middleware('permission:manage_staff')
        ->name('departments.destroy');

    Route::get('permissions', [RolePermissionController::class, 'edit'])
        ->middleware('permission:manage_permissions')
        ->name('permissions.edit');
    Route::put('permissions', [RolePermissionController::class, 'update'])
        ->middleware('permission:manage_permissions')
        ->name('permissions.update');
    Route::post('permissions/roles', [RolePermissionController::class, 'store'])
        ->middleware('permission:manage_permissions')
        ->name('permissions.roles.store');
    Route::patch('permissions/roles/{role}', [RolePermissionController::class, 'updateRole'])
        ->middleware('permission:manage_permissions')
        ->name('permissions.roles.update');
    Route::delete('permissions/roles/{role}', [RolePermissionController::class, 'destroy'])
        ->middleware('permission:manage_permissions')
        ->name('permissions.roles.destroy');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('permission:view_activity_log')
        ->name('activity-logs.index');

    Route::get('staff', [StaffController::class, 'index'])
        ->middleware('permission:view_staff,manage_staff,view_team_attendance')
        ->name('staff.index');
    Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('staff/{user}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('staff/{user}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('staff/{user}/impersonate', [ImpersonationController::class, 'store'])
        ->name('staff.impersonate');
    Route::delete('impersonation', [ImpersonationController::class, 'destroy'])
        ->name('impersonation.destroy');

    Route::get('tasks', [TaskController::class, 'index'])
        ->middleware('permission:view_tasks,manage_tasks')
        ->name('tasks.index');
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('tasks/{task}/transition', [TaskController::class, 'transition'])->name('tasks.transition');
    Route::post('tasks/{task}/comments', [TaskController::class, 'comment'])->name('tasks.comments.store');
    Route::post('tasks/{task}/attachments', [TaskController::class, 'attach'])->name('tasks.attachments.store');
    Route::get('tasks/{task}/attachments/{attachment}', [TaskController::class, 'download'])->name('tasks.attachments.download');

    Route::get('leave-requests', [LeaveRequestController::class, 'index'])
        ->middleware('permission:view_leave_requests,review_leave_requests')
        ->name('leave-requests.index');
    Route::get('leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::post('leave-requests/{leaveRequest}/review', [LeaveRequestController::class, 'review'])->name('leave-requests.review');
    Route::post('leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
});

require __DIR__.'/settings.php';
