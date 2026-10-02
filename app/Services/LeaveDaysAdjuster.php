<?php

namespace App\Services;

use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class LeaveDaysAdjuster
{
    public const int MIN = 0;

    public const int MAX = 365;

    public function balanceAfter(int $current, string $direction, int $days): ?int
    {
        $next = $current + $this->signed($direction, $days);

        if ($next < self::MIN || $next > self::MAX) {
            return null;
        }

        return $next;
    }

    public function apply(User $staff, string $direction, int $days, ?string $note = null): void
    {
        $next = $this->balanceAfter($staff->leave_days, $direction, $days);

        if ($next === null) {
            throw new InvalidArgumentException('Leave days are outside the allowed range.');
        }

        $signed = $this->signed($direction, $days);

        User::withoutEvents(function () use ($staff, $next): void {
            $staff->update(['leave_days' => $next]);
        });

        $changes = ['leave_days' => $next];

        if (is_string($note) && $note !== '') {
            $changes['note'] = $note;
        }

        ActivityLogger::record('leave_days_adjusted', $staff, [
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
