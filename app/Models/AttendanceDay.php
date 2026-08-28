<?php

namespace App\Models;

use App\Concerns\LogsActivity;
use App\Enums\QrSessionType;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Database\Factories\AttendanceDayFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $branch_id
 * @property Carbon $date
 * @property string $check_in_starts_at
 * @property string $check_in_ends_at
 * @property string $check_out_starts_at
 * @property string $check_out_ends_at
 * @property bool $check_in_is_open
 * @property bool $check_out_is_open
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'branch_id',
    'date',
    'check_in_starts_at',
    'check_in_ends_at',
    'check_out_starts_at',
    'check_out_ends_at',
    'check_in_is_open',
    'check_out_is_open',
    'created_by',
])]
class AttendanceDay extends Model
{
    /** @use HasFactory<AttendanceDayFactory> */
    use HasFactory, LogsActivity;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in_is_open' => 'boolean',
            'check_out_is_open' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendance_day_user')->withTimestamps();
    }

    /**
     * @return array{check_in_starts_at: string, check_in_ends_at: string, check_out_starts_at: string, check_out_ends_at: string}
     */
    public static function defaultSessionHours(): array
    {
        return [
            'check_in_starts_at' => '00:00',
            'check_in_ends_at' => '23:59',
            'check_out_starts_at' => '00:00',
            'check_out_ends_at' => '23:59',
        ];
    }

    public function hasScheduledStaff(): bool
    {
        if ($this->relationLoaded('staff')) {
            return $this->staff->isNotEmpty();
        }

        return $this->staff()->exists();
    }

    public function isStaffScheduled(User $user): bool
    {
        if ($this->relationLoaded('staff')) {
            return $this->staff->contains('id', $user->id);
        }

        return $this->staff()->where('users.id', $user->id)->exists();
    }

    public static function forBranchOnDate(int $branchId, CarbonInterface|string $date): ?self
    {
        $day = $date instanceof CarbonInterface ? $date->toDateString() : $date;

        return self::query()
            ->where('branch_id', $branchId)
            ->whereDate('date', $day)
            ->first();
    }

    public function isCheckInOpen(?CarbonInterface $at = null): bool
    {
        return $this->isWindowOpen($this->check_in_starts_at, $this->check_in_ends_at, $at);
    }

    public function isCheckOutOpen(?CarbonInterface $at = null): bool
    {
        return $this->isWindowOpen($this->check_out_starts_at, $this->check_out_ends_at, $at);
    }

    public function isSessionOpen(QrSessionType $type): bool
    {
        return $type === QrSessionType::CheckIn
            ? $this->check_in_is_open
            : $this->check_out_is_open;
    }

    public function isOpenFor(QrSessionType $type): bool
    {
        return $this->isSessionOpen($type);
    }

    public function setSessionOpen(QrSessionType $type, bool $open): void
    {
        $column = $type === QrSessionType::CheckIn
            ? 'check_in_is_open'
            : 'check_out_is_open';

        $this->forceFill([$column => $open])->save();
    }

    public function windowState(QrSessionType $type, ?CarbonInterface $at = null): string
    {
        $moment = $at ?? now();
        [$start, $end] = $this->window($type);

        if ($moment->lt($start)) {
            return 'upcoming';
        }

        if ($moment->gt($end)) {
            return 'closed';
        }

        return 'open';
    }

    /**
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    public function window(QrSessionType $type): array
    {
        $startTime = $type === QrSessionType::CheckIn
            ? $this->check_in_starts_at
            : $this->check_out_starts_at;
        $endTime = $type === QrSessionType::CheckIn
            ? $this->check_in_ends_at
            : $this->check_out_ends_at;

        return $this->bounds($startTime, $endTime);
    }

    /**
     * @return array{id: int, branch_id: int, date: string, check_in_starts_at: string, check_in_ends_at: string, check_out_starts_at: string, check_out_ends_at: string, check_in_is_open: bool, check_out_is_open: bool}
     */
    public function toWindowArray(): array
    {
        return [
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'date' => $this->date->toDateString(),
            'check_in_starts_at' => substr($this->check_in_starts_at, 0, 5),
            'check_in_ends_at' => substr($this->check_in_ends_at, 0, 5),
            'check_out_starts_at' => substr($this->check_out_starts_at, 0, 5),
            'check_out_ends_at' => substr($this->check_out_ends_at, 0, 5),
            'check_in_is_open' => $this->check_in_is_open,
            'check_out_is_open' => $this->check_out_is_open,
        ];
    }

    private function isWindowOpen(string $startsAt, string $endsAt, ?CarbonInterface $at = null): bool
    {
        $moment = $at ?? now();
        [$start, $end] = $this->bounds($startsAt, $endsAt);

        return $moment->betweenIncluded($start, $end);
    }

    /**
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    private function bounds(string $startsAt, string $endsAt): array
    {
        $date = $this->date->toDateString();
        $start = CarbonImmutable::parse($date.' '.$startsAt);
        $end = CarbonImmutable::parse($date.' '.$endsAt);

        if ($end->lessThanOrEqualTo($start)) {
            $end = $end->addDay();
        }

        return [$start, $end];
    }

    protected function activityName(): string
    {
        return $this->date?->toDateString() ?? '#'.$this->getKey();
    }
}
