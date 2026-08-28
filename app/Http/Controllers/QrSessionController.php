<?php

namespace App\Http\Controllers;

use App\Enums\QrSessionType;
use App\Http\Requests\Attendance\GenerateQrSessionRequest;
use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Services\QrSessionService;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QrSessionController extends Controller
{
    public function __construct(public QrSessionService $qrSessions) {}

    public function kiosk(Request $request): Response
    {
        $this->authorize('manageKiosk', Attendance::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $branches = $user->isSuperAdmin()
            ? Branch::query()->orderBy('name')->get(['id', 'name'])
            : Branch::query()->whereKey($user->branch_id)->get(['id', 'name']);

        return Inertia::render('attendance/Kiosk', [
            'branches' => $branches,
            'defaultBranchId' => $user->branch_id,
            'todaySessions' => AttendanceDay::query()
                ->whereIn('branch_id', $branches->pluck('id'))
                ->whereDate('date', now()->toDateString())
                ->get()
                ->map->toWindowArray()
                ->values(),
            'qrTtlSeconds' => (int) config('attendance.qr_ttl_seconds', 20),
            'entryCodeLength' => (int) config('attendance.entry_code_length', 6),
        ]);
    }

    public function current(GenerateQrSessionRequest $request): JsonResponse
    {
        [$type, $branch, $day] = $this->resolvedDay($request);

        if (! $day instanceof AttendanceDay) {
            return response()->json([
                'message' => 'Create an attendance session for today before opening the kiosk.',
            ], 422);
        }

        if (! $day->isOpenFor($type)) {
            return response()->json([
                'message' => 'This session is closed.',
                'day' => $day->toWindowArray(),
            ], 422);
        }

        $session = $this->qrSessions->currentOrCreate($branch, $type);

        return response()->json([
            ...$this->qrSessions->payload($session),
            'day' => $day->toWindowArray(),
        ]);
    }

    public function open(GenerateQrSessionRequest $request): JsonResponse
    {
        [$type, $branch, $day] = $this->resolvedDay($request);

        if (! $day instanceof AttendanceDay) {
            return response()->json([
                'message' => 'Create an attendance session for today before opening the kiosk.',
            ], 422);
        }

        $this->authorize('update', $day);
        $day->setSessionOpen($type, true);
        $session = $this->qrSessions->currentOrCreate($branch, $type);

        ActivityLogger::record('session_opened', $day, [
            'name' => $day->date->toDateString(),
            'type' => $type->value,
            'branch' => $branch->name,
        ]);

        return response()->json([
            'message' => 'Session opened.',
            ...$this->qrSessions->payload($session),
            'day' => $day->fresh()?->toWindowArray(),
        ]);
    }

    public function close(GenerateQrSessionRequest $request): JsonResponse
    {
        [$type, $branch, $day] = $this->resolvedDay($request);

        if (! $day instanceof AttendanceDay) {
            return response()->json([
                'message' => 'Create an attendance session for today before opening the kiosk.',
            ], 422);
        }

        $this->authorize('update', $day);
        $day->setSessionOpen($type, false);
        $this->qrSessions->expireActive($branch, $type);

        ActivityLogger::record('session_closed', $day, [
            'name' => $day->date->toDateString(),
            'type' => $type->value,
            'branch' => $branch->name,
        ]);

        return response()->json([
            'message' => 'Session closed.',
            'day' => $day->fresh()?->toWindowArray(),
        ]);
    }

    /**
     * @return array{0: QrSessionType, 1: Branch, 2: AttendanceDay|null}
     */
    private function resolvedDay(GenerateQrSessionRequest $request): array
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $type = QrSessionType::from($request->validated('type'));
        $branch = $this->qrSessions->resolveAuthorizedBranch(
            $user,
            $request->validated('branch_id') !== null ? (int) $request->validated('branch_id') : null,
        );

        return [$type, $branch, AttendanceDay::forBranchOnDate($branch->id, now())];
    }
}
