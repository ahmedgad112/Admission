<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(public AttendanceService $attendanceService) {}

    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        abort_unless($user !== null, 403);
        abort_unless($user->canViewDashboard(), 403);

        return Inertia::render('Dashboard', [
            'metrics' => $this->attendanceService->dashboardMetrics($user),
        ]);
    }
}
