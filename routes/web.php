<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceDayController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\QrSessionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::post('locale', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/export', [AttendanceController::class, 'export'])
        ->name('attendance.export');
    Route::put('attendance/entries', [AttendanceController::class, 'syncEntries'])
        ->name('attendance.entries.sync');
    Route::get('attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->middleware('throttle:qr-scan')
        ->name('attendance.check-in');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])
        ->middleware('throttle:qr-scan')
        ->name('attendance.check-out');

    Route::get('attendance/days', [AttendanceDayController::class, 'index'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.days.index');
    Route::get('attendance/days/create', [AttendanceDayController::class, 'create'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.days.create');
    Route::post('attendance/days', [AttendanceDayController::class, 'store'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.days.store');
    Route::get('attendance/days/{attendanceDay}/edit', [AttendanceDayController::class, 'edit'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.days.edit');
    Route::put('attendance/days/{attendanceDay}', [AttendanceDayController::class, 'update'])
        ->middleware('permission:manage_kiosk')
        ->name('attendance.days.update');
    Route::delete('attendance/days/{attendanceDay}', [AttendanceDayController::class, 'destroy'])
        ->middleware('permission:manage_kiosk')
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

    Route::get('branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('branches/create', [BranchController::class, 'create'])->name('branches.create');
    Route::post('branches', [BranchController::class, 'store'])->name('branches.store');
    Route::get('branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    Route::put('branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');

    Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
    Route::put('shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::delete('shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');

    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('staff/{user}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('staff/{user}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');

    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
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

    Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::post('leave-requests/{leaveRequest}/review', [LeaveRequestController::class, 'review'])->name('leave-requests.review');
    Route::post('leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
});

require __DIR__.'/settings.php';
