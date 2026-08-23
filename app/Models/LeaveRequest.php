<?php

namespace App\Models;

use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use Database\Factories\LeaveRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $branch_id
 * @property int|null $department_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property LeaveRequestType $type
 * @property string $reason
 * @property LeaveRequestStatus $status
 * @property int|null $reviewed_by
 * @property Carbon|null $reviewed_at
 * @property string|null $review_note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id',
    'branch_id',
    'department_id',
    'start_date',
    'end_date',
    'type',
    'reason',
    'status',
    'reviewed_by',
    'reviewed_at',
    'review_note',
])]
class LeaveRequest extends Model
{
    /** @use HasFactory<LeaveRequestFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'type' => LeaveRequestType::class,
            'status' => LeaveRequestStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === LeaveRequestStatus::Pending;
    }

    public function durationInDays(): int
    {
        return self::daysBetween(
            $this->start_date->toDateString(),
            $this->end_date->toDateString(),
        );
    }

    public static function daysBetween(string $startDate, string $endDate): int
    {
        return (int) Carbon::parse($startDate)->startOfDay()
            ->diffInDays(Carbon::parse($endDate)->startOfDay()) + 1;
    }

    /**
     * @param  Builder<LeaveRequest>  $query
     */
    public function scopeOverlapping(Builder $query, string $startDate, string $endDate): void
    {
        $query->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate);
    }

    /**
     * @param  Builder<LeaveRequest>  $query
     */
    public function scopeBlocking(Builder $query): void
    {
        $query->whereIn('status', [
            LeaveRequestStatus::Pending,
            LeaveRequestStatus::Approved,
        ]);
    }

    /**
     * @param  Builder<LeaveRequest>  $query
     */
    public function scopeVisibleTo(Builder $query, User $actor): void
    {
        match ($actor->recordScope()) {
            'all' => null,
            'branch' => $query->where(function (Builder $builder) use ($actor): void {
                $builder->where('user_id', $actor->id)
                    ->orWhere('branch_id', $actor->branch_id);
            }),
            'team' => $query->where(function (Builder $builder) use ($actor): void {
                $builder->where('user_id', $actor->id)
                    ->orWhere('department_id', $actor->department_id);
            }),
            default => $query->where('user_id', $actor->id),
        };
    }
}
