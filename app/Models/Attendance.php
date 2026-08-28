<?php

namespace App\Models;

use App\Concerns\LogsActivity;
use App\Enums\AttendanceStatus;
use Database\Factories\AttendanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $branch_id
 * @property Carbon $date
 * @property Carbon|null $check_in
 * @property Carbon|null $check_out
 * @property float|null $check_in_lat
 * @property float|null $check_in_long
 * @property float|null $check_out_lat
 * @property float|null $check_out_long
 * @property AttendanceStatus $status
 * @property int $late_minutes
 * @property int $early_leave_minutes
 * @property string $work_hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id',
    'branch_id',
    'date',
    'check_in',
    'check_out',
    'check_in_lat',
    'check_in_long',
    'check_out_lat',
    'check_out_long',
    'status',
    'late_minutes',
    'early_leave_minutes',
    'work_hours',
])]
class Attendance extends Model
{
    /** @use HasFactory<AttendanceFactory> */
    use HasFactory, LogsActivity;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in' => 'datetime',
            'check_out' => 'datetime',
            'check_in_lat' => 'float',
            'check_in_long' => 'float',
            'check_out_lat' => 'float',
            'check_out_long' => 'float',
            'status' => AttendanceStatus::class,
            'late_minutes' => 'integer',
            'early_leave_minutes' => 'integer',
            'work_hours' => 'decimal:2',
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

    protected function activityName(): string
    {
        $date = $this->date?->toDateString() ?? '#'.$this->getKey();
        $userName = $this->relationLoaded('user') ? $this->user?->name : null;

        return $userName !== null ? "{$userName} · {$date}" : $date;
    }
}
