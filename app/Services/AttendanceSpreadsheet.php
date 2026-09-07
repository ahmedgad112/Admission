<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\UserStatus;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Department;
use App\Models\User;
use App\Support\SimpleXlsx;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceSpreadsheet
{
    public function __construct(public SimpleXlsx $xlsx) {}

    public function downloadFor(User $actor, string $from, string $to, ?Department $department = null): StreamedResponse
    {
        return $this->xlsx->download(
            $this->filename($from, $to, $department),
            'Attendance',
            $this->headers(),
            $this->rows($actor, $from, $to, $department),
        );
    }

    public function downloadForDay(AttendanceDay $day, User $actor, ?Department $department = null): StreamedResponse
    {
        $day->loadMissing('branch:id,name');
        $date = $day->date->toDateString();

        $people = $this->people($actor, $day->branch_id, $department);

        $records = Attendance::query()
            ->where('branch_id', $day->branch_id)
            ->whereDate('date', $date)
            ->whereIn('user_id', $people->modelKeys())
            ->whereNotNull('check_in')
            ->get()
            ->keyBy('user_id');

        $rows = $people
            ->filter(fn (User $member): bool => $records->has($member->id))
            ->map(fn (User $member): array => $this->row(
                $date,
                $member->name,
                $member->branch->name,
                $records->get($member->id),
            ))->all();

        return $this->xlsx->download(
            $this->filenameForDay($day, $date, $department),
            'Attendance',
            $this->headers(),
            array_values($rows),
        );
    }

    /**
     * @return list<list<string|int|float|null>>
     */
    public function rows(User $actor, string $from, string $to, ?Department $department = null): array
    {
        if ($actor->can('record', Attendance::class)) {
            return $this->teamRows($actor, $from, $to, $department);
        }

        return $this->personalRows($actor, $from, $to);
    }

    /**
     * @return list<list<string|int|float|null>>
     */
    private function teamRows(User $actor, string $from, string $to, ?Department $department = null): array
    {
        $people = $this->people($actor, department: $department);

        if ($from === $to) {
            $records = Attendance::query()
                ->whereDate('date', $from)
                ->whereIn('user_id', $people->modelKeys())
                ->whereNotNull('check_in')
                ->get()
                ->keyBy('user_id');

            return array_values($people
                ->filter(fn (User $member): bool => $records->has($member->id))
                ->map(function (User $member) use ($from, $records): array {
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

    /**
     * @return Collection<int, User>
     */
    private function people(User $actor, ?int $branchId = null, ?Department $department = null): Collection
    {
        return User::query()
            ->visibleTo($actor)
            ->withoutSuperAdmins()
            ->with('branch:id,name')
            ->where('status', UserStatus::Active)
            ->when(
                $branchId !== null,
                fn ($query) => $query->where('branch_id', $branchId),
                fn ($query) => $query->whereNotNull('branch_id'),
            )
            ->when($department !== null, fn ($query) => $query->where('department_id', $department->id))
            ->orderBy('name')
            ->get(['id', 'name', 'branch_id']);
    }

    private function filename(string $from, string $to, ?Department $department = null): string
    {
        $parts = ['attendance'];
        $departmentPart = $this->departmentFilenamePart($department);

        if ($departmentPart !== null) {
            $parts[] = $departmentPart;
        }

        if ($from === $to) {
            $parts[] = $from;

            return implode('-', $parts).'.xlsx';
        }

        $parts[] = $from;
        $parts[] = 'to';
        $parts[] = $to;

        return implode('-', $parts).'.xlsx';
    }

    private function filenameForDay(AttendanceDay $day, string $date, ?Department $department = null): string
    {
        $parts = ['attendance'];
        $branchSlug = Str::slug((string) $day->branch?->name);

        if ($branchSlug !== '') {
            $parts[] = $branchSlug;
        }

        $departmentPart = $this->departmentFilenamePart($department);

        if ($departmentPart !== null) {
            $parts[] = $departmentPart;
        }

        $parts[] = $date;

        return implode('-', $parts).'.xlsx';
    }

    private function departmentFilenamePart(?Department $department): ?string
    {
        if ($department === null) {
            return null;
        }

        $slug = Str::slug($department->name);

        return $slug !== '' ? $slug : 'dept-'.$department->id;
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
