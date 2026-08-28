<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\Permission;
use App\Enums\UserStatus;
use App\Http\Requests\Staff\StoreStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use App\Support\RolePermissionCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', User::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $staff = User::query()
            ->visibleTo($user)
            ->with(['branch:id,name', 'department:id,name', 'shift:id,name', 'role'])
            ->when($request->string('search')->isNotEmpty(), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($builder) use ($search): void {
                    $builder->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->when($request->string('role')->isNotEmpty(), function ($query) use ($request): void {
                $query->whereHas(
                    'role',
                    fn ($builder) => $builder->where('slug', $request->string('role')->toString()),
                );
            })
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (User $member): array => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'role' => $member->role?->slug ?? 'employee',
                'role_label' => $member->role?->label() ?? '',
                'status' => $member->status->value,
                'branch' => $member->branch,
                'department' => $member->department,
                'shift' => $member->shift,
                'leave_days' => $member->leave_days,
            ]);

        return Inertia::render('staff/Index', [
            'staff' => $staff,
            'filters' => [
                'search' => $request->string('search')->toString(),
                'role' => $request->string('role')->toString(),
                'status' => $request->string('status')->toString(),
            ],
            'roleOptions' => Role::query()->ordered()->get()->map(fn (Role $role) => [
                'value' => $role->slug,
                'label' => $role->label(),
            ]),
            'canCreate' => $user->can('create', User::class),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('staff/Create', $this->formOptions($request));
    }

    public function store(StoreStaffRequest $request): JsonResponse|RedirectResponse
    {
        $staff = User::query()->create([
            ...$request->staffAttributes(),
            'password' => $request->validated('password'),
            'email_verified_at' => now(),
        ]);

        return $this->flashRedirect($request, __('flash.staff.created'), route('staff.index'), [
            'staff' => $staff,
        ]);
    }

    public function edit(Request $request, User $user): Response
    {
        $this->authorize('update', $user);

        return Inertia::render('staff/Edit', [
            'member' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role?->slug,
                'branch_id' => $user->branch_id,
                'department_id' => $user->department_id,
                'shift_id' => $user->shift_id,
                'status' => $user->status->value,
                'leave_days' => $user->leave_days,
                'permissions' => $user->grantedPermissionValues(),
            ],
            ...$this->formOptions($request),
        ]);
    }

    public function update(UpdateStaffRequest $request, User $user): JsonResponse|RedirectResponse
    {
        $payload = $request->staffAttributes(updating: true);

        if (filled($request->validated('password'))) {
            $payload['password'] = $request->validated('password');
        }

        $user->update($payload);

        return $this->flashRedirect($request, __('flash.staff.updated'), route('staff.index'), [
            'staff' => $user,
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return $this->flashRedirect($request, __('flash.staff.deleted'), route('staff.index'));
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(Request $request): array
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $roles = Role::query()
            ->ordered()
            ->when(! $user->isSuperAdmin(), fn ($query) => $query->where('slug', '!=', Role::SUPER_ADMIN))
            ->get();

        return [
            'branches' => $user->isSuperAdmin()
                ? Branch::query()->orderBy('name')->get(['id', 'name'])
                : Branch::query()->whereKey($user->branch_id)->get(['id', 'name']),
            'departments' => Department::query()
                ->when($user->limitsRecordsToBranch(), fn ($query) => $query->where('branch_id', $user->branch_id))
                ->when($user->limitsRecordsToTeam(), fn ($query) => $query->whereKey($user->department_id))
                ->orderBy('name')
                ->get(['id', 'name', 'branch_id']),
            'shifts' => Shift::query()->orderBy('name')->get(['id', 'name']),
            'roles' => $roles->map(fn (Role $role) => [
                'value' => $role->slug,
                'label' => $role->label(),
            ])->values()->all(),
            'permissionOptions' => array_map(
                fn (Permission $permission): array => $permission->toOption(),
                Permission::cases(),
            ),
            'rolePermissions' => app(RolePermissionCatalog::class)->permissionValuesByRole(),
            'grantablePermissions' => $user->grantablePermissionValues(),
            'statuses' => array_map(fn (UserStatus $status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ], UserStatus::cases()),
            'defaultBranchId' => $user->branch_id,
        ];
    }
}
