<?php

namespace App\Models;

use App\Concerns\LogsActivity;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property int $branch_id
 * @property int|null $manager_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'branch_id', 'manager_id'])]
class Department extends Model
{
    /** @use HasFactory<DepartmentFactory> */
    use HasFactory, LogsActivity;

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
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @param  Builder<Department>  $query
     */
    public function scopeVisibleTo($query, User $actor): void
    {
        if ($actor->isSuperAdmin()) {
            return;
        }

        $query->where('branch_id', $actor->branch_id);
    }

    /**
     * @param  Builder<Department>  $query
     */
    public function scopeExportableTo($query, User $actor, ?int $branchId = null): void
    {
        $query->visibleTo($actor)
            ->when($branchId !== null, fn ($builder) => $builder->where('branch_id', $branchId))
            ->when(
                $actor->limitsRecordsToTeam(),
                fn ($builder) => $builder->whereIn('id', $actor->visibleTeamDepartmentIds()),
            );
    }

    /**
     * @return list<array{id: int, name: string}>
     */
    public static function exportOptionsFor(User $actor, ?int $branchId = null): array
    {
        return static::query()
            ->exportableTo($actor, $branchId)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (self $department): array => [
                'id' => $department->id,
                'name' => $department->name,
            ])
            ->values()
            ->all();
    }

    public static function findExportableTo(User $actor, int $id, ?int $branchId = null): ?self
    {
        return static::query()
            ->exportableTo($actor, $branchId)
            ->find($id);
    }
}
