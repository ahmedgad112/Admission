<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Requests\Attendance\StoreAttendanceDayRequest;
use App\Http\Requests\Attendance\UpdateAttendanceDayRequest;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceDayController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', AttendanceDay::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $days = AttendanceDay::query()
            ->with(['branch:id,name', 'creator:id,name', 'staff:id,name'])
            ->when(! $user->isSuperAdmin(), fn ($query) => $query->where('branch_id', $user->branch_id))
            ->latest('date')
            ->paginate(12)
            ->through(fn (AttendanceDay $day) => [
                ...$day->toWindowArray(),
                'branch' => $day->branch,
                'staff' => $day->staff,
            ])
            ->withQueryString();

        return Inertia::render('attendance/days/Index', [
            'days' => $days,
            'canCreate' => $user->can('create', AttendanceDay::class),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', AttendanceDay::class);

        return Inertia::render('attendance/days/Create', $this->formOptions($request));
    }

    public function store(StoreAttendanceDayRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $day = AttendanceDay::query()->create([
            ...AttendanceDay::defaultSessionHours(),
            ...$request->safe()->except('staff_ids'),
            'created_by' => $user->id,
        ]);
        $day->staff()->sync($request->validated('staff_ids'));

        return $this->flashRedirect($request, __('flash.roster.created'), route('attendance.days.index'), [
            'day' => $day->load('staff:id,name'),
        ]);
    }

    public function edit(Request $request, AttendanceDay $attendanceDay): Response
    {
        $this->authorize('update', $attendanceDay);

        $attendanceDay->load('staff:id');

        return Inertia::render('attendance/days/Edit', [
            'day' => [
                'id' => $attendanceDay->id,
                'branch_id' => $attendanceDay->branch_id,
                'date' => $attendanceDay->date->toDateString(),
                'staff_ids' => $attendanceDay->staff->pluck('id')->all(),
            ],
            ...$this->formOptions($request),
        ]);
    }

    public function update(UpdateAttendanceDayRequest $request, AttendanceDay $attendanceDay): JsonResponse|RedirectResponse
    {
        $attendanceDay->update($request->safe()->except('staff_ids'));
        $attendanceDay->staff()->sync($request->validated('staff_ids'));

        return $this->flashRedirect($request, __('flash.roster.updated'), route('attendance.days.index'), [
            'day' => $attendanceDay->load('staff:id,name'),
        ]);
    }

    public function destroy(Request $request, AttendanceDay $attendanceDay): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $attendanceDay);

        $attendanceDay->delete();

        return $this->flashRedirect($request, __('flash.roster.deleted'), route('attendance.days.index'));
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(Request $request): array
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        return [
            'branches' => $user->isSuperAdmin()
                ? Branch::query()->orderBy('name')->get(['id', 'name'])
                : Branch::query()->whereKey($user->branch_id)->get(['id', 'name']),
            'staff' => User::query()
                ->with('department:id,name')
                ->where('status', UserStatus::Active)
                ->whereIn('role', [
                    UserRole::BranchAdmin,
                    UserRole::Manager,
                    UserRole::Employee,
                ])
                ->when(! $user->isSuperAdmin(), fn ($query) => $query->where('branch_id', $user->branch_id))
                ->orderBy('name')
                ->get(['id', 'name', 'branch_id', 'department_id', 'role']),
            'defaultBranchId' => $user->branch_id,
        ];
    }
}
