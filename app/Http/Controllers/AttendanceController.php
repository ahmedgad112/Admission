<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\UserStatus;
use App\Exceptions\AttendanceException;
use App\Http\Requests\Attendance\CheckInRequest;
use App\Http\Requests\Attendance\CheckOutRequest;
use App\Http\Requests\Attendance\ClearAttendanceRecordsRequest;
use App\Http\Requests\Attendance\OpenAttendanceRequest;
use App\Http\Requests\Attendance\SyncAttendanceEntriesRequest;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\AttendanceSpreadsheet;
use App\Support\AttendanceToken;
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
        [$from, $to] = $canRecord
            ? [$date, $date]
            : $this->requestedHistoryRange($request);

        $attendances = Attendance::query()
            ->with(['user:id,name,email', 'branch:id,name'])
            ->tap(fn ($query) => $user->constrainAttendanceVisibility($query))
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->orderByDesc('date')
            ->orderBy('id')
            ->paginate($canRecord ? 15 : 31)
            ->withQueryString();

        return Inertia::render('attendance/Index', [
            'date' => $date,
            'from' => $from,
            'to' => $to,
            'canRecord' => $canRecord,
            'people' => $canRecord ? $this->peopleForDate($user, $date) : [],
            'attendances' => $attendances,
        ]);
    }

    public function syncEntries(SyncAttendanceEntriesRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $this->attendanceService->recordEntries($request->user(), $request->payload());
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
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
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
            'recorded' => $this->pulledRecordedType($request),
            'token' => $this->queryToken($request),
        ]);
    }

    public function open(Request $request): Response|RedirectResponse
    {
        $token = $this->queryToken($request);

        if ($request->user() !== null) {
            return redirect()->route('attendance.scan', array_filter([
                'token' => $token,
            ]));
        }

        return Inertia::render('attendance/Open', [
            'token' => $token,
            'recorded' => $this->pulledRecordedType($request),
        ]);
    }

    public function recordOpen(OpenAttendanceRequest $request): JsonResponse|RedirectResponse
    {
        $payload = $this->openScanPayload($request);
        $user = $request->user() ?? $this->attendanceService->userForDevice($payload['device_uuid']);

        if ($user === null || ! $user->can('create', Attendance::class)) {
            $request->session()->put(
                'url.intended',
                route('attendance.open', ['token' => $payload['token']], false),
            );

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => __('attendance.error.login_required'),
                ], 401);
            }

            return redirect()->route('login');
        }

        try {
            $attendance = $this->attendanceService->recordFromKiosk($user, $payload);
        } catch (AttendanceException $exception) {
            return $this->attendanceError($request, $exception);
        }

        $checkedOut = $attendance->check_out !== null;

        return $this->scanSuccessRedirect(
            $request,
            $checkedOut ? 'check_out' : 'check_in',
            $attendance,
            route('attendance.open'),
        );
    }

    public function recordScan(CheckInRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        try {
            $attendance = $this->attendanceService->recordFromKiosk($user, $this->scanPayload($request));
        } catch (AttendanceException $exception) {
            return $this->attendanceError($request, $exception);
        }

        $checkedOut = $attendance->check_out !== null;

        return $this->scanSuccessRedirect(
            $request,
            $checkedOut ? 'check_out' : 'check_in',
            $attendance,
        );
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

        return $this->scanSuccessRedirect($request, 'check_in', $attendance);
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

        return $this->scanSuccessRedirect($request, 'check_out', $attendance);
    }

    private function scanSuccessRedirect(
        Request $request,
        string $type,
        Attendance $attendance,
        ?string $redirectTo = null,
    ): JsonResponse|RedirectResponse {
        $message = $type === 'check_out'
            ? __('flash.attendance.checked_out')
            : __('flash.attendance.checked_in');

        $response = $this->flashRedirect(
            $request,
            $message,
            $redirectTo ?? route('attendance.scan'),
            ['attendance' => $attendance],
        );

        if ($response instanceof RedirectResponse) {
            return $response->with('attendance_recorded', $type);
        }

        return $response;
    }

    private function queryToken(Request $request): ?string
    {
        $raw = $request->route('token');

        if (! is_string($raw) || $raw === '') {
            $raw = $request->string('token')->toString();
        }

        $token = AttendanceToken::fromScannedValue($raw);

        return $token !== '' ? $token : null;
    }

    /**
     * @return array{token: string, latitude: float, longitude: float, device_uuid: string}
     */
    private function openScanPayload(OpenAttendanceRequest $request): array
    {
        /** @var array{token: string, latitude?: float|int|string|null, longitude?: float|int|string|null, device_uuid: string} $validated */
        $validated = $request->validated();

        return [
            'token' => $validated['token'],
            'latitude' => (float) ($validated['latitude'] ?? 0),
            'longitude' => (float) ($validated['longitude'] ?? 0),
            'device_uuid' => $validated['device_uuid'],
        ];
    }

    private function pulledRecordedType(Request $request): ?string
    {
        $recorded = $request->session()->pull('attendance_recorded');

        return in_array($recorded, ['check_in', 'check_out'], true) ? $recorded : null;
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

        return $this->normalizeDateRange($from, $to);
    }

    /**
     * Default history window for self-service attendance tables: current month through today.
     *
     * @return array{0: string, 1: string}
     */
    private function requestedHistoryRange(Request $request): array
    {
        $from = $this->parseDate($request, 'from')
            ?? now()->startOfMonth()->toDateString();
        $to = $this->parseDate($request, 'to')
            ?? now()->toDateString();

        return $this->normalizeDateRange($from, $to);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function normalizeDateRange(string $from, string $to): array
    {
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
            ->with('department:id,name')
            ->get(['id', 'name', 'branch_id', 'department_id']);

        $records = Attendance::query()
            ->whereDate('date', $date)
            ->whereIn('user_id', $people->modelKeys())
            ->whereNotNull('check_in')
            ->get()
            ->keyBy('user_id');

        return array_values($people
            ->filter(fn (User $member): bool => $records->has($member->id))
            ->map(function (User $member) use ($records): array {
                $record = $records->get($member->id);

                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'department' => $member->department,
                    'check_in' => $record?->check_in?->format('H:i'),
                    'check_out' => $record?->check_out?->format('H:i'),
                    'work_hours' => $record?->work_hours,
                    'status' => $record?->status?->value,
                ];
            })->all());
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
