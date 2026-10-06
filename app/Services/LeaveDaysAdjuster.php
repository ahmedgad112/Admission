<?php

namespace App\Services;

use App\Models\AttendanceDayAdjustment;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class LeaveDaysAdjuster
{
    public const int MIN = -31;

    public const int MAX = 31;

    public function credit(User $staff, string $month): int
    {
        return (int) AttendanceDayAdjustment::query()
            ->where('user_id', $staff->id)
            ->where('month', $month)
            ->sum('days');
    }

    public function balanceAfter(int $current, string $direction, int $days): ?int
    {
        $next = $current + $this->signed($direction, $days);

        if ($next < self::MIN || $next > self::MAX) {
            return null;
        }

        return $next;
    }

    public function apply(User $staff, string $direction, int $days, ?string $note = null, ?string $month = null): void
    {
        $month ??= now()->format('Y-m');
        $next = $this->balanceAfter($this->credit($staff, $month), $direction, $days);

        if ($next === null) {
            throw new InvalidArgumentException('Attendance days are outside the allowed range.');
        }

        $signed = $this->signed($direction, $days);

        AttendanceDayAdjustment::query()->create([
            'user_id' => $staff->id,
            'adjusted_by' => auth()->id(),
            'month' => $month,
            'days' => $signed,
            'note' => is_string($note) && $note !== '' ? $note : null,
        ]);

        $changes = [
            'attendance_days' => $next,
            'month' => $month,
        ];

        if (is_string($note) && $note !== '') {
            $changes['note'] = $note;
        }

        ActivityLogger::record('attendance_days_adjusted', $staff, [
            'name' => $staff->name,
            'days' => ($signed > 0 ? '+' : '').$signed,
            'changes' => $changes,
        ]);
    }

    /**
     * @param  list<int>  $ids
     * @return Collection<int, User>
     */
    public function adjustable(User $actor, array $ids): Collection
    {
        if ($ids === []) {
            return new Collection;
        }

        return User::query()
            ->visibleTo($actor)
            ->with('role')
            ->whereIn('id', $ids)
            ->get()
            ->filter(fn (User $staff): bool => $actor->can('update', $staff))
            ->values();
    }

    private function signed(string $direction, int $days): int
    {
        return $direction === 'deduct' ? -$days : $days;
    }
}
