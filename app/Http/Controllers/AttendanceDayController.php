<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Http\Requests\Attendance\StoreAttendanceDayRequest;
use App\Http\Requests\Attendance\UpdateAttendanceDayRequest;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Services\AttendanceSpreadsheet;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceDayController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function __construct(public AttendanceSpreadsheet $spreadsheet) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', AttendanceDay::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $days = AttendanceDay::query()
            ->with(['branch:id,name', 'creator:id,name'])
            ->when(! $user->isSuperAdmin(), fn ($query) => $query->where('branch_id', $user->branch_id))
            ->latest('date')
            ->paginate(12)
            ->through(fn (AttendanceDay $day) => [
                ...$day->toWindowArray(),
                'branch' => $day->branch,
                'present_count' => Attendance::query()
                    ->where('branch_id', $day->branch_id)
                    ->whereDate('date', $day->date)
                    ->whereNotNull('check_in')
                    ->count(),
            ])
            ->withQueryString();

        return Inertia::render('attendance/days/Index', [
            'days' => $days,
            'canCreate' => $user->can('create', AttendanceDay::class),
        ]);
    }

    public function show(Request $request, AttendanceDay $attendanceDay): Response
    {
        $this->authorize('view', $attendanceDay);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $attendanceDay->load([
            'branch:id,name',
            'creator:id,name',
        ]);

        $attendances = Attendance::query()
            ->with(['user:id,name,department_id', 'user.department:id,name'])
            ->where('branch_id', $attendanceDay->branch_id)
            ->whereDate('date', $attendanceDay->date)
            ->orderBy('check_in')
            ->get();

        return Inertia::render('attendance/days/Show', [
            'day' => [
                ...$attendanceDay->toWindowArray(),
                'branch' => $attendanceDay->branch,
                'creator' => $attendanceDay->creator,
                'attendances' => $attendances->map(fn (Attendance $record): array => [
                    'id' => $record->id,
                    'user_id' => $record->user_id,
                    'name' => $record->user?->name,
                    'department' => $record->user?->department,
                    'check_in' => $record->check_in?->format('H:i'),
                    'check_out' => $record->check_out?->format('H:i'),
                    'status' => $record->status?->value,
                    'work_hours' => $record->work_hours,
                ])->values()->all(),
            ],
            'canUpdate' => $user->can('update', $attendanceDay),
        ]);
    }

    public function export(AttendanceDay $attendanceDay): StreamedResponse
    {
        $this->authorize('view', $attendanceDay);

        return $this->spreadsheet->downloadForDay($attendanceDay);
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
            ...$request->safe()->all(),
            'created_by' => $user->id,
        ]);

        ActivityLogger::record('roster_synced', $day, [
            'name' => $day->date->toDateString(),
        ]);

        return $this->flashRedirect($request, __('flash.roster.created'), route('attendance.days.index'), [
            'day' => $day,
        ]);
    }

    public function edit(Request $request, AttendanceDay $attendanceDay): Response
    {
        $this->authorize('update', $attendanceDay);

        return Inertia::render('attendance/days/Edit', [
            'day' => [
                'id' => $attendanceDay->id,
                'branch_id' => $attendanceDay->branch_id,
                'date' => $attendanceDay->date->toDateString(),
            ],
            ...$this->formOptions($request),
        ]);
    }

    public function update(UpdateAttendanceDayRequest $request, AttendanceDay $attendanceDay): JsonResponse|RedirectResponse
    {
        $attendanceDay->update($request->safe()->all());

        ActivityLogger::record('roster_synced', $attendanceDay, [
            'name' => $attendanceDay->date->toDateString(),
        ]);

        return $this->flashRedirect($request, __('flash.roster.updated'), route('attendance.days.index'), [
            'day' => $attendanceDay,
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
            'defaultBranchId' => $user->branch_id,
        ];
    }
}
