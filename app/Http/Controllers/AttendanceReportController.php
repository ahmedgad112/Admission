<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Services\StaffAttendanceSummary;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceReportController extends Controller
{
    public function __construct(public StaffAttendanceSummary $attendanceSummary) {}

    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        abort_unless($user !== null, 403);
        abort_unless($user->canViewAttendanceReports(), 403);

        $month = $this->attendanceSummary->resolveMonth($request->string('month')->toString());
        $branchId = $request->filled('branch_id') ? $request->integer('branch_id') : null;

        if ($branchId !== null) {
            abort_unless(
                $user->isSuperAdmin() || $user->branch_id === $branchId,
                403,
            );
        }

        return Inertia::render('attendance/Reports', [
            ...$this->attendanceSummary->report($user, $month, $branchId),
            'filters' => [
                'month' => $month,
                'branch_id' => $branchId,
            ],
            'branches' => $user->isSuperAdmin()
                ? Branch::query()->orderBy('name')->get(['id', 'name'])
                : [],
        ]);
    }
}
