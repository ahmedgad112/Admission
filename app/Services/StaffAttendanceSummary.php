<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\LeaveRequestType;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class StaffAttendanceSummary
{
    /**
     * @return array{
     *     month: string,
     *     from: string,
     *     to: string,
     *     present_days: int,
     *     absent_days: int,
     *     late_days: int,
     *     permission_days: int,
     *     leave_days_used: int,
     *     remaining_leave_days: int,
     *     records: list<array<string, mixed>>,
     *     leaves: list<array<string, mixed>>
     * }
     */
    public function for(User $staff, string $month): array
    {
        $monthStart = CarbonImmutable::createFromFormat('Y-m', $month)?->startOfMonth()
            ?? now()->startOfMonth()->toImmutable();
        $from = $monthStart->toDateString();
        $to = $monthStart->endOfMonth()->toDateString();
        $until = $monthStart->endOfMonth()->min(now()->endOfDay())->toDateString();

        $records = Attendance::query()
            ->where('user_id', $staff->id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->orderByDesc('date')
            ->get();

        $presentDates = $records
            ->filter(fn (Attendance $attendance): bool => $attendance->check_in !== null
                && $attendance->status !== AttendanceStatus::Absent)
            ->map(fn (Attendance $attendance): string => $attendance->date->toDateString())
            ->unique()
            ->values();

        $leaves = LeaveRequest::query()
            ->where('user_id', $staff->id)
            ->blocking()
            ->overlapping($from, $to)
            ->orderByDesc('start_date')
            ->get();

        $leaveDates = $this->leaveDates($leaves, $from, $until);
        $expectedDates = $this->expectedDates($staff, $from, $until);
        $absentDates = $expectedDates
            ->diff($presentDates)
            ->diff($leaveDates)
            ->values();

        $permissionDays = (int) $leaves
            ->where('type', LeaveRequestType::Permission)
            ->sum(fn (LeaveRequest $leave): int => $this->overlapDays(
                $leave->start_date->toDateString(),
                $leave->end_date->toDateString(),
                $from,
                $to,
            ));

        return [
            'month' => $monthStart->format('Y-m'),
            'from' => $from,
            'to' => $to,
            'present_days' => $presentDates->count(),
            'absent_days' => $absentDates->count(),
            'late_days' => $records
                ->filter(fn (Attendance $attendance): bool => in_array($attendance->status, [
                    AttendanceStatus::Late,
                    AttendanceStatus::LateAndEarlyLeave,
                ], true))
                ->count(),
            'permission_days' => $permissionDays,
            'leave_days_used' => $staff->usedLeaveDays($monthStart->year),
            'remaining_leave_days' => $staff->remainingLeaveDays($monthStart->year),
            'records' => array_values($records->map(fn (Attendance $attendance): array => [
                'id' => $attendance->id,
                'date' => $attendance->date->toDateString(),
                'check_in' => $attendance->check_in?->format('H:i'),
                'check_out' => $attendance->check_out?->format('H:i'),
                'status' => $attendance->status->value,
                'work_hours' => $attendance->work_hours,
                'late_minutes' => $attendance->late_minutes,
            ])->all()),
            'leaves' => array_values($leaves->map(fn (LeaveRequest $leave): array => [
                'id' => $leave->id,
                'start_date' => $leave->start_date->toDateString(),
                'end_date' => $leave->end_date->toDateString(),
                'type' => $leave->type->value,
                'status' => $leave->status->value,
                'reason' => $leave->reason,
                'days' => $leave->durationInDays(),
            ])->all()),
        ];
    }

    public function resolveMonth(?string $month): string
    {
        if (is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month) === 1) {
            return $month;
        }

        return now()->format('Y-m');
    }

    /**
     * @return Collection<int, string>
     */
    private function expectedDates(User $staff, string $from, string $until): Collection
    {
        if ($staff->branch_id === null) {
            return collect();
        }

        return AttendanceDay::query()
            ->where('branch_id', $staff->branch_id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $until)
            ->with('staff:id')
            ->get()
            ->filter(fn (AttendanceDay $day): bool => ! $day->hasScheduledStaff() || $day->isStaffScheduled($staff))
            ->map(fn (AttendanceDay $day): string => $day->date->toDateString())
            ->unique()
            ->values();
    }

    /**
     * @param  Collection<int, LeaveRequest>  $leaves
     * @return Collection<int, string>
     */
    private function leaveDates(Collection $leaves, string $from, string $until): Collection
    {
        $dates = collect();

        foreach ($leaves as $leave) {
            $cursor = $leave->start_date->toImmutable()->startOfDay();
            $end = $leave->end_date->toImmutable()->startOfDay();

            while ($cursor->lte($end)) {
                $day = $cursor->toDateString();

                if ($day >= $from && $day <= $until) {
                    $dates->push($day);
                }

                $cursor = $cursor->addDay();
            }
        }

        return $dates->unique()->values();
    }

    private function overlapDays(string $start, string $end, string $from, string $to): int
    {
        $overlapStart = max($start, $from);
        $overlapEnd = min($end, $to);

        if ($overlapStart > $overlapEnd) {
            return 0;
        }

        return LeaveRequest::daysBetween($overlapStart, $overlapEnd);
    }
}
