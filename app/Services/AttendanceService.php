<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\QrSessionType;
use App\Enums\TaskStatus;
use App\Exceptions\AttendanceException;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\QrSession;
use App\Models\Shift;
use App\Models\Task;
use App\Models\User;
use App\Support\ActivityLogger;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class AttendanceService
{
    public function __construct(public QrSessionService $qrSessions) {}

    /**
     * Great-circle distance in meters using the Haversine formula.
     */
    public function distanceInMeters(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo): float
    {
        $earthRadius = 6371000.0;
        $deltaLatitude = deg2rad($latitudeTo - $latitudeFrom);
        $deltaLongitude = deg2rad($longitudeTo - $longitudeFrom);

        $a = sin($deltaLatitude / 2) ** 2
            + cos(deg2rad($latitudeFrom))
            * cos(deg2rad($latitudeTo))
            * sin($deltaLongitude / 2) ** 2;

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    public function isWithinGeofence(Branch $branch, float $latitude, float $longitude): bool
    {
        return $this->distanceInMeters(
            (float) $branch->latitude,
            (float) $branch->longitude,
            $latitude,
            $longitude,
        ) <= $branch->radius_meters;
    }

    /**
     * @return array{start: CarbonImmutable, end: CarbonImmutable}
     */
    public function shiftWindow(Shift $shift, CarbonInterface $moment): array
    {
        $start = CarbonImmutable::parse($moment->toDateString().' '.$shift->start_time);
        $end = CarbonImmutable::parse($moment->toDateString().' '.$shift->end_time);

        if ($end->lessThanOrEqualTo($start)) {
            $end = $end->addDay();
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * @return array{late_minutes: int, status: AttendanceStatus}
     */
    public function evaluateCheckIn(User $user, CarbonInterface $checkIn): array
    {
        if (! $user->shift instanceof Shift) {
            return [
                'late_minutes' => 0,
                'status' => AttendanceStatus::Present,
            ];
        }

        $window = $this->shiftWindow($user->shift, $checkIn);
        $graceEndsAt = $window['start']->addMinutes($user->shift->grace_period_minutes);

        if ($checkIn->greaterThan($graceEndsAt)) {
            return [
                'late_minutes' => (int) round($window['start']->diffInMinutes($checkIn)),
                'status' => AttendanceStatus::Late,
            ];
        }

        return [
            'late_minutes' => 0,
            'status' => AttendanceStatus::Present,
        ];
    }

    /**
     * @return array{early_leave_minutes: int, work_hours: float, status: AttendanceStatus}
     */
    public function evaluateCheckOut(Attendance $attendance, User $user, CarbonInterface $checkOut): array
    {
        $checkIn = $attendance->check_in ?? $checkOut;
        $workHours = round($checkIn->diffInMinutes($checkOut) / 60, 2);
        $earlyLeaveMinutes = 0;

        if ($user->shift instanceof Shift) {
            $window = $this->shiftWindow($user->shift, $checkIn);
            if ($checkOut->lessThan($window['end'])) {
                $earlyLeaveMinutes = (int) round($checkOut->diffInMinutes($window['end']));
            }
        }

        $wasLate = $attendance->status === AttendanceStatus::Late || $attendance->late_minutes > 0;

        $status = match (true) {
            $wasLate && $earlyLeaveMinutes > 0 => AttendanceStatus::LateAndEarlyLeave,
            $wasLate => AttendanceStatus::Late,
            $earlyLeaveMinutes > 0 => AttendanceStatus::EarlyLeave,
            default => AttendanceStatus::Present,
        };

        return [
            'early_leave_minutes' => $earlyLeaveMinutes,
            'work_hours' => $workHours,
            'status' => $status,
        ];
    }

    /**
     * Record check-in or check-out from the scanned kiosk code.
     *
     * @param  array{token: string, latitude: float, longitude: float, device_uuid: string}  $payload
     */
    public function recordFromKiosk(User $user, array $payload): Attendance
    {
        $session = $this->qrSessions->findValid($payload['token']);

        if (! $session instanceof QrSession) {
            throw new AttendanceException(__('attendance.error.invalid_qr'));
        }

        return $session->type === QrSessionType::CheckIn
            ? $this->checkIn($user, $payload)
            : $this->checkOut($user, $payload);
    }

    /**
     * @param  array{token: string, latitude: float, longitude: float, device_uuid: string}  $payload
     */
    public function checkIn(User $user, array $payload): Attendance
    {
        $session = $this->validatedSession($user, $payload['token'], QrSessionType::CheckIn);
        $this->assertOpenDay($session->branch_id, QrSessionType::CheckIn, $user);
        $this->assertDevice($user, $payload['device_uuid']);

        $today = now()->toDateString();
        $existing = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing instanceof Attendance && $existing->check_in !== null) {
            throw new AttendanceException(__('attendance.error.already_checked_in'));
        }

        $evaluation = $this->evaluateCheckIn($user->loadMissing('shift'), now());

        try {
            return DB::transaction(function () use ($user, $session, $payload, $today, $existing, $evaluation): Attendance {
                $attendance = $existing ?? new Attendance([
                    'user_id' => $user->id,
                    'date' => $today,
                ]);

                $attendance->fill([
                    'branch_id' => $session->branch_id,
                    'check_in' => now(),
                    'check_in_lat' => $payload['latitude'],
                    'check_in_long' => $payload['longitude'],
                    'status' => $evaluation['status'],
                    'late_minutes' => $evaluation['late_minutes'],
                ]);
                $attendance->save();

                ActivityLogger::record('checked_in', $attendance, [
                    'name' => $user->name,
                    'date' => $today,
                    'status' => $attendance->status->value,
                ], $user);

                return $attendance->refresh();
            });
        } catch (Throwable $exception) {
            if ($this->isUniqueConstraintViolation($exception)) {
                throw new AttendanceException(__('attendance.error.already_checked_in'));
            }

            throw $exception;
        }
    }

    /**
     * @param  array{token: string, latitude: float, longitude: float, device_uuid: string}  $payload
     */
    public function checkOut(User $user, array $payload): Attendance
    {
        $session = $this->validatedSession($user, $payload['token'], QrSessionType::CheckOut);
        $this->assertOpenDay($session->branch_id, QrSessionType::CheckOut, $user);
        $this->assertDevice($user, $payload['device_uuid']);

        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if (! $attendance instanceof Attendance || $attendance->check_in === null) {
            throw new AttendanceException(__('attendance.error.must_check_in'));
        }

        if ($attendance->check_out !== null) {
            throw new AttendanceException(__('attendance.error.already_checked_out'));
        }

        $evaluation = $this->evaluateCheckOut($attendance, $user->loadMissing('shift'), now());

        $attendance->fill([
            'check_out' => now(),
            'check_out_lat' => $payload['latitude'],
            'check_out_long' => $payload['longitude'],
            'early_leave_minutes' => $evaluation['early_leave_minutes'],
            'work_hours' => $evaluation['work_hours'],
            'status' => $evaluation['status'],
        ]);
        $attendance->save();

        ActivityLogger::record('checked_out', $attendance, [
            'name' => $user->name,
            'date' => $attendance->date->toDateString(),
            'status' => $attendance->status->value,
        ], $user);

        return $attendance->refresh();
    }

    /**
     * @param  array{date: string, entries: list<array{user_id: int, check_in?: string|null, check_out?: string|null}>}  $payload
     * @return list<Attendance>
     */
    public function recordEntries(User $actor, array $payload): array
    {
        $date = CarbonImmutable::parse($payload['date'])->toDateString();

        return DB::transaction(function () use ($actor, $payload, $date): array {
            $saved = [];

            foreach ($payload['entries'] as $entry) {
                if (blank($entry['check_in'] ?? null)) {
                    continue;
                }

                $member = User::query()
                    ->visibleTo($actor)
                    ->with('shift')
                    ->whereKey($entry['user_id'])
                    ->first();

                if (! $member instanceof User || $member->branch_id === null) {
                    throw new AttendanceException(__('attendance.error.cannot_record'));
                }

                $checkIn = CarbonImmutable::parse($date.' '.$entry['check_in']);
                $checkOut = filled($entry['check_out'] ?? null)
                    ? CarbonImmutable::parse($date.' '.$entry['check_out'])
                    : null;

                if ($checkOut instanceof CarbonImmutable && $checkOut->lessThanOrEqualTo($checkIn)) {
                    $checkOut = $checkOut->addDay();
                }

                $attendance = Attendance::query()
                    ->where('user_id', $member->id)
                    ->whereDate('date', $date)
                    ->first() ?? new Attendance([
                        'user_id' => $member->id,
                        'date' => $date,
                    ]);

                $evaluation = $this->evaluateCheckIn($member, $checkIn);

                $attendance->fill([
                    'branch_id' => $member->branch_id,
                    'check_in' => $checkIn,
                    'status' => $evaluation['status'],
                    'late_minutes' => $evaluation['late_minutes'],
                    'check_out' => null,
                    'early_leave_minutes' => 0,
                    'work_hours' => 0,
                ]);

                if ($checkOut instanceof CarbonImmutable) {
                    $checkOutEvaluation = $this->evaluateCheckOut($attendance, $member, $checkOut);
                    $attendance->fill([
                        'check_out' => $checkOut,
                        'early_leave_minutes' => $checkOutEvaluation['early_leave_minutes'],
                        'work_hours' => $checkOutEvaluation['work_hours'],
                        'status' => $checkOutEvaluation['status'],
                    ]);
                }

                $attendance->save();
                $saved[] = $attendance->refresh();
            }

            return $saved;
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function dashboardMetrics(User $user): array
    {
        $today = now()->toDateString();
        $from = now()->startOfMonth();

        $attendanceQuery = Attendance::query()->whereDate('date', '>=', $from->toDateString());
        $userQuery = User::query()->where('status', 'active')->visibleTo($user);
        $user->constrainAttendanceVisibility($attendanceQuery);

        $headcount = (clone $userQuery)->count();
        $presentToday = (clone $attendanceQuery)->whereDate('date', $today)->whereNotNull('check_in')->count();
        $lateToday = (clone $attendanceQuery)
            ->whereDate('date', $today)
            ->whereIn('status', [
                AttendanceStatus::Late->value,
                AttendanceStatus::LateAndEarlyLeave->value,
            ])
            ->count();
        $lateThisMonth = (clone $attendanceQuery)
            ->whereIn('status', [
                AttendanceStatus::Late->value,
                AttendanceStatus::LateAndEarlyLeave->value,
            ])
            ->count();
        $averageWorkHours = (float) (clone $attendanceQuery)
            ->whereNotNull('check_out')
            ->avg('work_hours');

        $taskQuery = Task::query()->visibleTo($user);
        $totalTasks = (clone $taskQuery)->count();
        $completedTasks = (clone $taskQuery)->where('status', TaskStatus::Completed)->count();

        return [
            'headcount' => $headcount,
            'present_today' => $presentToday,
            'late_today' => $lateToday,
            'attendance_rate' => $headcount > 0 ? round(($presentToday / $headcount) * 100, 1) : 0,
            'late_this_month' => $lateThisMonth,
            'average_work_hours' => round($averageWorkHours, 2),
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'task_completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0,
            'today_attendance' => Attendance::query()
                ->with(['user:id,name', 'branch:id,name'])
                ->whereDate('date', $today)
                ->tap(fn ($query) => $user->constrainAttendanceVisibility($query))
                ->latest('check_in')
                ->limit(8)
                ->get(),
        ];
    }

    private function validatedSession(User $user, string $token, QrSessionType $type): QrSession
    {
        $session = $this->qrSessions->findValid($token, $type);

        if (! $session instanceof QrSession) {
            throw new AttendanceException(__('attendance.error.invalid_qr'));
        }

        if (! $user->isSuperAdmin() && $user->branch_id !== $session->branch_id) {
            throw new AttendanceException(__('attendance.error.wrong_branch'), 403);
        }

        return $session;
    }

    private function assertOpenDay(int $branchId, QrSessionType $type, User $user): void
    {
        $day = AttendanceDay::forBranchOnDate($branchId, now());

        if (! $day instanceof AttendanceDay) {
            throw new AttendanceException(__('attendance.error.no_session'));
        }

        if (! $day->isOpenFor($type)) {
            $label = $type === QrSessionType::CheckIn
                ? __('attendance.session.check_in')
                : __('attendance.session.check_out');

            throw new AttendanceException(__('attendance.error.session_closed', ['label' => $label]));
        }
    }

    private function assertDevice(User $user, string $deviceUuid): void
    {
        if ($user->device_uuid === null) {
            $user->forceFill(['device_uuid' => $deviceUuid])->save();

            return;
        }

        if (! hash_equals($user->device_uuid, $deviceUuid)) {
            throw new AttendanceException(__('attendance.error.device'), 403);
        }
    }

    private function isUniqueConstraintViolation(Throwable $exception): bool
    {
        return str_contains($exception->getMessage(), 'UNIQUE constraint failed')
            || str_contains($exception->getMessage(), 'Duplicate entry');
    }
}
