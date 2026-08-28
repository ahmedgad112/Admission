<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Department::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $departments = Department::query()
            ->with(['branch:id,name', 'manager:id,name'])
            ->withCount('users')
            ->tap(fn ($query) => $query->visibleTo($user))
            ->orderBy('name')
            ->paginate(12)
            ->through(fn (Department $department): array => $this->departmentAttributes($department))
            ->withQueryString();

        return Inertia::render('departments/Index', [
            'departments' => $departments,
            'canCreate' => $user->can('create', Department::class),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', Department::class);

        return Inertia::render('departments/Create', $this->formOptions($request));
    }

    public function store(StoreDepartmentRequest $request): JsonResponse|RedirectResponse
    {
        $department = Department::query()->create($request->validated());

        return $this->flashRedirect($request, __('flash.department.created'), route('departments.index'), [
            'department' => $department,
        ]);
    }

    public function edit(Request $request, Department $department): Response
    {
        $this->authorize('update', $department);

        return Inertia::render('departments/Edit', [
            'department' => $this->departmentAttributes($department->load(['branch:id,name', 'manager:id,name'])),
            ...$this->formOptions($request, $department),
        ]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse|RedirectResponse
    {
        $department->update($request->safe()->only(['name', 'manager_id']));

        return $this->flashRedirect($request, __('flash.department.updated'), route('departments.index'), [
            'department' => $department,
        ]);
    }

    public function destroy(Request $request, Department $department): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $department);

        $department->delete();

        return $this->flashRedirect($request, __('flash.department.deleted'), route('departments.index'));
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(Request $request, ?Department $department = null): array
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $branchId = $department?->branch_id ?? $user->branch_id;

        return [
            'branches' => $user->isSuperAdmin()
                ? Branch::query()->orderBy('name')->get(['id', 'name'])
                : Branch::query()->whereKey($user->branch_id)->get(['id', 'name']),
            'managers' => User::query()
                ->withoutSuperAdmins()
                ->when($branchId !== null, fn ($query) => $query->where('branch_id', $branchId))
                ->orderBy('name')
                ->get(['id', 'name']),
            'defaultBranchId' => $user->branch_id,
        ];
    }

    /**
     * @return array{id: int, name: string, branch_id: int, branch?: array{id: int, name: string}|null, manager_id: int|null, manager?: array{id: int, name: string}|null, staff_count: int}
     */
    private function departmentAttributes(Department $department): array
    {
        return [
            'id' => $department->id,
            'name' => $department->name,
            'branch_id' => $department->branch_id,
            'branch' => $department->relationLoaded('branch') ? $department->branch : null,
            'manager_id' => $department->manager_id,
            'manager' => $department->relationLoaded('manager') ? $department->manager : null,
            'staff_count' => (int) ($department->users_count ?? $department->users()->count()),
        ];
    }
}
