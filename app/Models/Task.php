<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Carbon\CarbonImmutable;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $created_by
 * @property int|null $department_id
 * @property TaskPriority $priority
 * @property TaskStatus $status
 * @property Carbon|null $due_date
 * @property CarbonImmutable|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'title',
    'description',
    'created_by',
    'department_id',
    'priority',
    'status',
    'due_date',
    'completed_at',
])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
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
    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return HasMany<TaskComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * @return HasMany<TaskAttachment, $this>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    /**
     * @return HasMany<TaskActivity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(TaskActivity::class);
    }

    public function isAssignedTo(User $user): bool
    {
        if ($this->relationLoaded('assignees')) {
            return $this->assignees->contains('id', $user->id);
        }

        return $this->assignees()->where('users.id', $user->id)->exists();
    }

    /**
     * @param  Builder<Task>  $query
     */
    public function scopeVisibleTo(Builder $query, User $user): void
    {
        match ($user->recordScope()) {
            'all' => null,
            'branch' => $query->where(function (Builder $builder) use ($user): void {
                $builder->whereHas('assignees', fn (Builder $assignee) => $assignee->where('branch_id', $user->branch_id))
                    ->orWhereHas('creator', fn (Builder $creator) => $creator->where('branch_id', $user->branch_id))
                    ->orWhereHas('department', fn (Builder $department) => $department->where('branch_id', $user->branch_id));
            }),
            'team' => $query->where(function (Builder $builder) use ($user): void {
                $builder->where('department_id', $user->department_id)
                    ->orWhere('created_by', $user->id)
                    ->orWhereHas('assignees', fn (Builder $assignee) => $assignee
                        ->where('users.id', $user->id)
                        ->orWhere('department_id', $user->department_id));
            }),
            default => $query->where(function (Builder $builder) use ($user): void {
                $builder->whereHas('assignees', fn (Builder $assignee) => $assignee->where('users.id', $user->id))
                    ->orWhere(function (Builder $departmentTask) use ($user): void {
                        $departmentTask->whereDoesntHave('assignees')
                            ->where('department_id', $user->department_id);
                    });
            }),
        };
    }
}
