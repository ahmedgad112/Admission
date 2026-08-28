<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\QrSessionController;
use App\Http\Controllers\TaskController;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('dashboard', function (Request $request, AttendanceService $attendanceService) {
        $user = $request->user();
        abort_unless($user !== null, 403);

        return response()->json([
            'metrics' => $attendanceService->dashboardMetrics($user),
        ]);
    })->name('api.dashboard');

    Route::get('qr-sessions/current', [QrSessionController::class, 'current'])
        ->middleware('permission:manage_kiosk')
        ->name('api.qr-sessions.current');
    Route::post('qr-sessions/open', [QrSessionController::class, 'open'])
        ->middleware('permission:manage_kiosk')
        ->name('api.qr-sessions.open');
    Route::post('qr-sessions/close', [QrSessionController::class, 'close'])
        ->middleware('permission:manage_kiosk')
        ->name('api.qr-sessions.close');
    Route::get('kiosk/pending', [QrSessionController::class, 'pending'])
        ->middleware('permission:manage_kiosk')
        ->name('api.kiosk.pending');

    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->middleware(['permission:scan_attendance', 'throttle:qr-scan'])
        ->name('api.attendance.check-in');

    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])
        ->middleware(['permission:scan_attendance', 'throttle:qr-scan'])
        ->name('api.attendance.check-out');

    Route::get('tasks', [TaskController::class, 'index'])->name('api.tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('api.tasks.store');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');
    Route::post('tasks/{task}/transition', [TaskController::class, 'transition'])->name('api.tasks.transition');
    Route::post('tasks/{task}/comments', [TaskController::class, 'comment'])->name('api.tasks.comments.store');
    Route::post('tasks/{task}/attachments', [TaskController::class, 'attach'])->name('api.tasks.attachments.store');
});
