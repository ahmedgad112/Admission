<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\LeaveRequestType;
use App\Enums\UserStatus;
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
     *     expected_days: int,
     *     present_days: int,
     *     absent_days: int,
     *     late_days: int,
     *     permission_days: int,
     *     attendance_rate: float,
     *     leave_days_used: int,
     *     remaining_leave_days: int,
     *     records: list<array<string, mixed>>,
     *     leaves: list<array<string, mixed>>
     * }
     */
    public function for(User $staff, string $month): array
    {
        $monthStart = $this->monthStart($month);
        $from = $monthStart->toDateString();
        $to = $monthStart->endOfMonth()->toDateString();
        $until = $this->until($monthStart);

        $records = Attendance::query()
            ->where('user_id', $staff->id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->orderByDesc('date')
            ->get();

        $presentDates = $this->presentDates($records);
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
            'expected_days' => $expectedDates->count(),
            'present_days' => $presentDates->count(),
            'absent_days' => $absentDates->count(),
            'late_days' => $records
                ->filter(fn (Attendance $attendance): bool => in_array($attendance->status, [
                    AttendanceStatus::Late,
                    AttendanceStatus::LateAndEarlyLeave,
                ], true))
                ->count(),
            'permission_days' => $permissionDays,
            'attendance_rate' => $this->rate($presentDates->count(), $absentDates->count()),
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

    /**
     * @return array{
     *     month: string,
     *     from: string,
     *     to: string,
     *     dates: list<string>,
     *     people: list<array<string, mixed>>
     * }
     */
    public function report(User $actor, string $month, ?int $branchId = null): array
    {
        $monthStart = $this->monthStart($month);
        $from = $monthStart->toDateString();
        $to = $monthStart->endOfMonth()->toDateString();
        $until = $this->until($monthStart);

        $staff = User::query()
            ->visibleTo($actor)
            ->withoutSuperAdmins()
            ->with(['branch:id,name', 'department:id,name'])
            ->where('status', UserStatus::Active)
            ->whereNotNull('branch_id')
            ->when($branchId !== null, fn ($query) => $query->where('branch_id', $branchId))
            ->orderBy('name')
            ->get(['id', 'name', 'branch_id', 'department_id']);

        $branchIds = $staff->pluck('branch_id')->unique()->filter()->values();

        $days = $branchIds->isEmpty()
            ? collect()
            : AttendanceDay::query()
                ->whereIn('branch_id', $branchIds)
                ->whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->with('staff:id')
                ->orderBy('date')
                ->get();

        $dates = $days
            ->map(fn (AttendanceDay $day): string => $day->date->toDateString())
            ->unique()
            ->sort()
            ->values();

        $records = $staff->isEmpty()
            ? collect()
            : Attendance::query()
                ->whereIn('user_id', $staff->modelKeys())
                ->whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->get();

        $leaves = $staff->isEmpty()
            ? collect()
            : LeaveRequest::query()
                ->whereIn('user_id', $staff->modelKeys())
                ->blocking()
                ->overlapping($from, $to)
                ->get();

        return [
            'month' => $monthStart->format('Y-m'),
            'from' => $from,
            'to' => $to,
            'dates' => array_values($dates->all()),
            'people' => array_values($staff->map(function (User $member) use ($dates, $days, $records, $leaves, $from, $until): array {
                $memberDays = $days->where('branch_id', $member->branch_id);
                $expectedDates = $this->expectedDatesFromDays($member, $memberDays, $until);
                $memberRecords = $records->where('user_id', $member->id);
                $presentDates = $this->presentDates($memberRecords);
                $memberLeaves = $leaves->where('user_id', $member->id);
                $leaveMarks = $this->leaveMarks($memberLeaves, $from, $until);
                $absentDates = $expectedDates
                    ->diff($presentDates)
                    ->diff($leaveMarks->keys())
                    ->values();
                $recordsByDate = $memberRecords->keyBy(fn (Attendance $attendance): string => $attendance->date->toDateString());

                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'branch' => $member->branch,
                    'department' => $member->department,
                    'expected_days' => $expectedDates->count(),
                    'present_days' => $presentDates->count(),
                    'absent_days' => $absentDates->count(),
                    'late_days' => $memberRecords
                        ->filter(fn (Attendance $attendance): bool => in_array($attendance->status, [
                            AttendanceStatus::Late,
                            AttendanceStatus::LateAndEarlyLeave,
                        ], true))
                        ->count(),
                    'permission_days' => $leaveMarks->filter(fn (string $mark): bool => $mark === 'permission')->count(),
                    'leave_days' => $leaveMarks->filter(fn (string $mark): bool => $mark === 'leave')->count(),
                    'attendance_rate' => $this->rate($presentDates->count(), $absentDates->count()),
                    'marks' => $dates->mapWithKeys(fn (string $date): array => [
                        $date => $this->markForDate(
                            $date,
                            $until,
                            $expectedDates,
                            $recordsByDate->get($date),
                            $leaveMarks->get($date),
                        ),
                    ])->all(),
                ];
            })->all()),
        ];
    }

    public function resolveMonth(?string $month): string
    {
        if (is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month) === 1) {
            return $month;
        }

        return now()->format('Y-m');
    }

    public function rate(int $presentDays, int $absentDays): float
    {
        $counted = $presentDays + $absentDays;

        if ($counted === 0) {
            return 0.0;
        }

        return round(($presentDays / $counted) * 100, 1);
    }

    private function monthStart(string $month): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat('Y-m', $month)?->startOfMonth()
            ?? now()->startOfMonth()->toImmutable();
    }

    private function until(CarbonImmutable $monthStart): string
    {
        return $monthStart->endOfMonth()->min(now()->endOfDay())->toDateString();
    }

    /**
     * @param  Collection<int, Attendance>  $records
     * @return Collection<int, string>
     */
    private function presentDates(Collection $records): Collection
    {
        return $records
            ->filter(fn (Attendance $attendance): bool => $attendance->check_in !== null
                && $attendance->status !== AttendanceStatus::Absent)
            ->map(fn (Attendance $attendance): string => $attendance->date->toDateString())
            ->unique()
            ->values();
    }

    /**
     * @return Collection<int, string>
     */
    private function expectedDates(User $staff, string $from, string $until): Collection
    {
        if ($staff->branch_id === null) {
            return collect();
        }

        $days = AttendanceDay::query()
            ->where('branch_id', $staff->branch_id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $until)
            ->with('staff:id')
            ->get();

        return $this->expectedDatesFromDays($staff, $days, $until);
    }

    /**
     * @param  Collection<int, AttendanceDay>  $days
     * @return Collection<int, string>
     */
    private function expectedDatesFromDays(User $staff, Collection $days, string $until): Collection
    {
        return $days
            ->filter(fn (AttendanceDay $day): bool => $day->date->toDateString() <= $until
                && (! $day->hasScheduledStaff() || $day->isStaffScheduled($staff)))
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
        return $this->leaveMarks($leaves, $from, $until)->keys()->values();
    }

    /**
     * @param  Collection<int, LeaveRequest>  $leaves
     * @return Collection<string, string>
     */
    private function leaveMarks(Collection $leaves, string $from, string $until): Collection
    {
        $marks = collect();

        foreach ($leaves as $leave) {
            $cursor = $leave->start_date->toImmutable()->startOfDay();
            $end = $leave->end_date->toImmutable()->startOfDay();
            $mark = $leave->type === LeaveRequestType::Permission ? 'permission' : 'leave';

            while ($cursor->lte($end)) {
                $day = $cursor->toDateString();

                if ($day >= $from && $day <= $until) {
                    $marks[$day] = $mark;
                }

                $cursor = $cursor->addDay();
            }
        }

        return $marks;
    }

    /**
     * @param  Collection<int, string>  $expectedDates
     */
    private function markForDate(
        string $date,
        string $until,
        Collection $expectedDates,
        ?Attendance $attendance,
        ?string $leaveMark,
    ): string {
        if ($date > $until) {
            return 'upcoming';
        }

        $presence = $this->presenceMark($attendance);

        if ($presence !== null) {
            return $presence;
        }

        if ($leaveMark !== null) {
            return $leaveMark;
        }

        if ($expectedDates->contains($date)) {
            return 'absent';
        }

        return 'off';
    }

    private function presenceMark(?Attendance $attendance): ?string
    {
        if ($attendance === null || $attendance->check_in === null || $attendance->status === AttendanceStatus::Absent) {
            return null;
        }

        if (in_array($attendance->status, [
            AttendanceStatus::Late,
            AttendanceStatus::LateAndEarlyLeave,
        ], true)) {
            return 'late';
        }

        return 'present';
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
