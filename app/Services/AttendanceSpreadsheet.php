<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\UserStatus;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\User;
use App\Support\SimpleXlsx;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceSpreadsheet
{
    public function __construct(public SimpleXlsx $xlsx) {}

    public function downloadFor(User $actor, string $from, string $to): StreamedResponse
    {
        return $this->xlsx->download(
            $this->filename($from, $to),
            'Attendance',
            $this->headers(),
            $this->rows($actor, $from, $to),
        );
    }

    public function downloadForDay(AttendanceDay $day): StreamedResponse
    {
        $day->loadMissing('branch:id,name');
        $date = $day->date->toDateString();
        $branchName = $day->branch?->name;
        $slug = Str::slug((string) $branchName);

        $filename = $slug === ''
            ? 'attendance-'.$date.'.xlsx'
            : 'attendance-'.$slug.'-'.$date.'.xlsx';

        $rows = Attendance::query()
            ->with(['user:id,name', 'branch:id,name'])
            ->where('branch_id', $day->branch_id)
            ->whereDate('date', $date)
            ->orderBy('check_in')
            ->get()
            ->map(fn (Attendance $attendance): array => $this->row(
                $date,
                $attendance->user->name,
                $attendance->branch->name,
                $attendance,
            ))
            ->all();

        return $this->xlsx->download(
            $filename,
            'Attendance',
            $this->headers(),
            array_values($rows),
        );
    }

    /**
     * @return list<list<string|int|float|null>>
     */
    public function rows(User $actor, string $from, string $to): array
    {
        if ($actor->can('record', Attendance::class)) {
            return $this->teamRows($actor, $from, $to);
        }

        return $this->personalRows($actor, $from, $to);
    }

    /**
     * @return list<list<string|int|float|null>>
     */
    private function teamRows(User $actor, string $from, string $to): array
    {
        $people = User::query()
            ->visibleTo($actor)
            ->withoutSuperAdmins()
            ->with('branch:id,name')
            ->where('status', UserStatus::Active)
            ->whereNotNull('branch_id')
            ->orderBy('name')
            ->get(['id', 'name', 'branch_id']);

        if ($from === $to) {
            $records = Attendance::query()
                ->whereDate('date', $from)
                ->whereIn('user_id', $people->modelKeys())
                ->get()
                ->keyBy('user_id');

            return array_values($people->map(function (User $member) use ($from, $records): array {
                return $this->row(
                    $from,
                    $member->name,
                    $member->branch?->name,
                    $records->get($member->id),
                );
            })->all());
        }

        return array_values(Attendance::query()
            ->with(['user:id,name', 'branch:id,name'])
            ->whereIn('user_id', $people->modelKeys())
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->orderBy('date')
            ->get()
            ->sortBy(fn (Attendance $attendance): string => $attendance->date->toDateString().'|'.$attendance->user->name)
            ->values()
            ->map(fn (Attendance $attendance): array => $this->row(
                $attendance->date->toDateString(),
                $attendance->user->name,
                $attendance->branch->name,
                $attendance,
            ))
            ->all());
    }

    /**
     * @return list<list<string|int|float|null>>
     */
    private function personalRows(User $actor, string $from, string $to): array
    {
        return array_values(Attendance::query()
            ->with(['user:id,name', 'branch:id,name'])
            ->where('user_id', $actor->id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->orderBy('date')
            ->get()
            ->map(fn (Attendance $attendance): array => $this->row(
                $attendance->date->toDateString(),
                $attendance->user->name,
                $attendance->branch->name,
                $attendance,
            ))
            ->all());
    }

    private function filename(string $from, string $to): string
    {
        if ($from === $to) {
            return 'attendance-'.$from.'.xlsx';
        }

        return 'attendance-'.$from.'-to-'.$to.'.xlsx';
    }

    /**
     * @return list<string>
     */
    private function headers(): array
    {
        return [
            'Date',
            'Employee',
            'Branch',
            'Check in',
            'Check out',
            'Hours',
            'Status',
            'Late minutes',
            'Early leave minutes',
        ];
    }

    /**
     * @return list<string|int|float|null>
     */
    private function row(string $date, ?string $name, ?string $branch, ?Attendance $attendance): array
    {
        return [
            $date,
            $name,
            $branch,
            $attendance?->check_in?->format('H:i'),
            $attendance?->check_out?->format('H:i'),
            $attendance?->work_hours,
            $attendance?->status instanceof AttendanceStatus
                ? Str::of($attendance->status->value)->replace('_', ' ')->title()->toString()
                : null,
            $attendance?->late_minutes,
            $attendance?->early_leave_minutes,
        ];
    }
}
