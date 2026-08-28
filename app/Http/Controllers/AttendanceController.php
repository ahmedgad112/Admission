<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\UserStatus;
use App\Exceptions\AttendanceException;
use App\Http\Requests\Attendance\CheckInRequest;
use App\Http\Requests\Attendance\CheckOutRequest;
use App\Http\Requests\Attendance\ClearAttendanceRecordsRequest;
use App\Http\Requests\Attendance\SyncAttendanceEntriesRequest;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\AttendanceSpreadsheet;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AttendanceController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function __construct(
        public AttendanceService $attendanceService,
        public AttendanceSpreadsheet $spreadsheet,
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Attendance::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $date = $this->requestedDate($request);
        $canRecord = $user->can('record', Attendance::class);

        $attendances = Attendance::query()
            ->with(['user:id,name,email', 'branch:id,name'])
            ->tap(fn ($query) => $user->constrainAttendanceVisibility($query))
            ->whereDate('date', $date)
            ->latest('date')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('attendance/Index', [
            'date' => $date,
            'canRecord' => $canRecord,
            'people' => $canRecord ? $this->peopleForDate($user, $date) : [],
            'attendances' => $attendances,
        ]);
    }

    public function syncEntries(SyncAttendanceEntriesRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $this->attendanceService->recordEntries($request->user(), $request->validated());
        } catch (AttendanceException $exception) {
            return $this->attendanceError($request, $exception);
        }

        return $this->flashRedirect(
            $request,
            __('flash.attendance.saved'),
            route('attendance.index', ['date' => $request->validated('date')]),
        );
    }

    public function clearRecords(ClearAttendanceRecordsRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        if ($request->filled('date')) {
            $from = $request->string('date')->toString();
            $to = $from;
        } else {
            [$from, $to] = $this->requestedDateRange($request);
        }

        Attendance::query()
            ->tap(fn ($query) => $user->constrainAttendanceVisibility($query))
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->each(fn (Attendance $attendance) => $attendance->delete());

        return $this->flashRedirect(
            $request,
            __('flash.attendance.cleared'),
            route('attendance.index', ['date' => $from]),
        );
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', Attendance::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        [$from, $to] = $this->requestedDateRange($request);

        return $this->spreadsheet->downloadFor($user, $from, $to);
    }

    public function scan(Request $request): Response
    {
        $this->authorize('create', Attendance::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $day = $user->branch_id
            ? AttendanceDay::forBranchOnDate($user->branch_id, now())
            : null;

        return Inertia::render('attendance/Scan', [
            'day' => $day instanceof AttendanceDay ? $day->toWindowArray() : null,
            'isScheduled' => $day instanceof AttendanceDay
                && (! $day->hasScheduledStaff() || $day->isStaffScheduled($user)),
        ]);
    }

    public function checkIn(CheckInRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        try {
            $attendance = $this->attendanceService->checkIn($user, $this->scanPayload($request));
        } catch (AttendanceException $exception) {
            return $this->attendanceError($request, $exception);
        }

        return $this->flashRedirect(
            $request,
            __('flash.attendance.checked_in'),
            route('attendance.scan'),
            ['attendance' => $attendance],
        );
    }

    public function checkOut(CheckOutRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        try {
            $attendance = $this->attendanceService->checkOut($user, $this->scanPayload($request));
        } catch (AttendanceException $exception) {
            return $this->attendanceError($request, $exception);
        }

        return $this->flashRedirect(
            $request,
            __('flash.attendance.checked_out'),
            route('attendance.scan'),
            ['attendance' => $attendance],
        );
    }

    /**
     * @return array{token: string, latitude: float, longitude: float, device_uuid: string}
     */
    private function scanPayload(CheckInRequest|CheckOutRequest $request): array
    {
        /** @var array{token: string, latitude: float|int|string, longitude: float|int|string, device_uuid: string} $validated */
        $validated = $request->validated();

        return [
            'token' => $validated['token'],
            'latitude' => (float) $validated['latitude'],
            'longitude' => (float) $validated['longitude'],
            'device_uuid' => $validated['device_uuid'],
        ];
    }

    private function requestedDate(Request $request): string
    {
        return $this->parseDate($request, 'date') ?? now()->toDateString();
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function requestedDateRange(Request $request): array
    {
        $from = $this->parseDate($request, 'from')
            ?? $this->parseDate($request, 'date')
            ?? now()->toDateString();
        $to = $this->parseDate($request, 'to') ?? $from;

        if ($to < $from) {
            [$from, $to] = [$to, $from];
        }

        $start = CarbonImmutable::parse($from);
        $end = CarbonImmutable::parse($to);

        if ($start->diffInDays($end) > 366) {
            $to = $start->addDays(366)->toDateString();
        }

        return [$from, $to];
    }

    private function parseDate(Request $request, string $key): ?string
    {
        if (! $request->filled($key)) {
            return null;
        }

        try {
            return CarbonImmutable::parse($request->string($key)->toString())->toDateString();
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function peopleForDate(User $user, string $date): array
    {
        $people = User::query()
            ->visibleTo($user)
            ->withoutSuperAdmins()
            ->where('status', UserStatus::Active)
            ->whereNotNull('branch_id')
            ->orderBy('name')
            ->get(['id', 'name', 'branch_id']);

        $records = Attendance::query()
            ->whereDate('date', $date)
            ->whereIn('user_id', $people->modelKeys())
            ->get()
            ->keyBy('user_id');

        return $people->map(function (User $member) use ($records): array {
            $record = $records->get($member->id);

            return [
                'id' => $member->id,
                'name' => $member->name,
                'check_in' => $record?->check_in?->format('H:i'),
                'check_out' => $record?->check_out?->format('H:i'),
                'work_hours' => $record?->work_hours,
                'status' => $record?->status?->value,
            ];
        })->all();
    }

    private function attendanceError(Request $request, AttendanceException $exception): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->status);
        }

        return back()->withErrors([
            'attendance' => $exception->getMessage(),
        ]);
    }
}
