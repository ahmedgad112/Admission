<?php

namespace App\Models;

use App\Enums\LeaveRequestStatus;
use App\Enums\Permission;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property UserRole $role
 * @property int|null $branch_id
 * @property int|null $department_id
 * @property int|null $shift_id
 * @property UserStatus $status
 * @property int $leave_days
 * @property list<string> $permissions
 * @property string|null $device_uuid
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'email',
    'phone',
    'password',
    'role',
    'branch_id',
    'department_id',
    'shift_id',
    'status',
    'leave_days',
    'permissions',
    'device_uuid',
])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
            'leave_days' => 'integer',
            'permissions' => 'array',
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
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsTo<Shift, $this>
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * @return BelongsToMany<AttendanceDay, $this>
     */
    public function attendanceDays(): BelongsToMany
    {
        return $this->belongsToMany(AttendanceDay::class, 'attendance_day_user')->withTimestamps();
    }

    /**
     * @return HasMany<Attendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @return BelongsToMany<Task, $this>
     */
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    /**
     * @return HasMany<Task, $this>
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * @return HasMany<LeaveRequest, $this>
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function usedLeaveDays(?int $year = null): int
    {
        $year ??= now()->year;

        $requests = $this->relationLoaded('leaveRequests')
            ? $this->leaveRequests
                ->filter(fn (LeaveRequest $request): bool => $request->start_date->year === $year
                    && in_array($request->status, [
                        LeaveRequestStatus::Pending,
                        LeaveRequestStatus::Approved,
                    ], true))
            : $this->leaveRequests()
                ->blocking()
                ->whereYear('start_date', $year)
                ->get(['start_date', 'end_date']);

        return (int) $requests->sum(fn (LeaveRequest $request): int => $request->durationInDays());
    }

    public function remainingLeaveDays(?int $year = null): int
    {
        return max(0, $this->leave_days - $this->usedLeaveDays($year));
    }

    public function hasEnoughLeaveDays(int $days, ?int $year = null): bool
    {
        return $days <= $this->remainingLeaveDays($year);
    }

    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isBranchAdmin(): bool
    {
        return $this->role === UserRole::BranchAdmin;
    }

    public function isManager(): bool
    {
        return $this->role === UserRole::Manager;
    }

    public function isEmployee(): bool
    {
        return $this->role === UserRole::Employee;
    }

    public function canManagePermissions(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageKiosk(): bool
    {
        return $this->hasPermission(Permission::ManageKiosk);
    }

    public function canManageStaff(): bool
    {
        return $this->hasPermission(Permission::ManageStaff);
    }

    public function canManageTasks(): bool
    {
        return $this->hasPermission(Permission::ManageTasks);
    }

    public function canViewTeamAttendance(): bool
    {
        return $this->hasPermission(Permission::ViewTeamAttendance);
    }

    public function canReviewLeaveRequests(): bool
    {
        return $this->hasPermission(Permission::ReviewLeaveRequests);
    }

    public function hasPermission(Permission $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->role->hasPermission($permission)
            || in_array($permission->value, $this->grantedPermissionValues(), true);
    }

    /**
     * @return list<string>
     */
    public function grantedPermissionValues(): array
    {
        return array_values(array_filter(
            $this->permissions ?? [],
            fn (mixed $permission): bool => is_string($permission) && Permission::tryFrom($permission) !== null,
        ));
    }

    /**
     * @return list<Permission>
     */
    public function grantablePermissions(): array
    {
        if ($this->isSuperAdmin()) {
            return Permission::cases();
        }

        return array_values(array_filter(
            Permission::cases(),
            fn (Permission $permission): bool => $this->hasPermission($permission),
        ));
    }

    /**
     * @return list<string>
     */
    public function grantablePermissionValues(): array
    {
        return array_map(
            fn (Permission $permission): string => $permission->value,
            $this->grantablePermissions(),
        );
    }

    /**
     * @param  list<string>  $requested
     * @return list<string>
     */
    public static function extraPermissions(UserRole $role, array $requested, User $actor, ?self $staff = null): array
    {
        if ($role === UserRole::SuperAdmin) {
            return [];
        }

        $grantable = $actor->grantablePermissionValues();
        $existing = $staff?->grantedPermissionValues() ?? [];
        $preserved = array_values(array_diff($existing, $grantable));
        $incoming = array_values(array_intersect($requested, $grantable));

        return array_values(array_diff(
            array_unique([...$preserved, ...$incoming]),
            $role->permissionValues(),
        ));
    }

    /**
     * @return 'all'|'branch'|'team'|'self'
     */
    public function recordScope(): string
    {
        if ($this->isSuperAdmin()) {
            return 'all';
        }

        if (
            $this->canManageKiosk()
            || ($this->canManageStaff() && $this->department_id === null)
        ) {
            return 'branch';
        }

        if (
            $this->isManager()
            || $this->canManageStaff()
            || $this->canManageTasks()
            || $this->canViewTeamAttendance()
            || $this->canReviewLeaveRequests()
        ) {
            return 'team';
        }

        return 'self';
    }

    public function limitsRecordsToBranch(): bool
    {
        return $this->recordScope() === 'branch';
    }

    public function limitsRecordsToTeam(): bool
    {
        return $this->recordScope() === 'team';
    }

    /**
     * @param  Builder<Model>  $query
     */
    public function constrainAttendanceVisibility(Builder $query): void
    {
        match ($this->recordScope()) {
            'all' => null,
            'branch' => $query->where('branch_id', $this->branch_id),
            'team' => $query->whereHas(
                'user',
                fn (Builder $builder) => $builder->where('department_id', $this->department_id),
            ),
            default => $query->where('user_id', $this->id),
        };
    }

    public function canAccessBranch(?int $branchId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $branchId !== null && $this->branch_id === $branchId;
    }

    /**
     * @param  Builder<User>  $query
     */
    public function scopeVisibleTo(Builder $query, User $actor): void
    {
        match ($actor->recordScope()) {
            'all' => null,
            'branch' => $query->where('branch_id', $actor->branch_id),
            'team' => $query->where('department_id', $actor->department_id),
            default => $query->where('id', $actor->id),
        };
    }
}
