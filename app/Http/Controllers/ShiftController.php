<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Http\Requests\Shift\StoreShiftRequest;
use App\Http\Requests\Shift\UpdateShiftRequest;
use App\Models\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShiftController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Shift::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $shifts = Shift::query()
            ->withCount('users')
            ->orderBy('name')
            ->paginate(12)
            ->through(fn (Shift $shift) => $this->shiftAttributes($shift))
            ->withQueryString();

        return Inertia::render('shifts/Index', [
            'shifts' => $shifts,
            'canCreate' => $user->can('create', Shift::class),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Shift::class);

        return Inertia::render('shifts/Create');
    }

    public function store(StoreShiftRequest $request): JsonResponse|RedirectResponse
    {
        $shift = Shift::query()->create($request->safe()->only([
            'name',
            'start_time',
            'end_time',
            'grace_period_minutes',
        ]));

        return $this->flashRedirect($request, __('flash.shift.created'), route('shifts.index'), [
            'shift' => $shift,
        ]);
    }

    public function edit(Shift $shift): Response
    {
        $this->authorize('update', $shift);

        return Inertia::render('shifts/Edit', [
            'shift' => $this->shiftAttributes($shift),
        ]);
    }

    public function update(UpdateShiftRequest $request, Shift $shift): JsonResponse|RedirectResponse
    {
        $shift->update($request->safe()->only([
            'name',
            'start_time',
            'end_time',
            'grace_period_minutes',
        ]));

        return $this->flashRedirect($request, __('flash.shift.updated'), route('shifts.index'), [
            'shift' => $shift,
        ]);
    }

    public function destroy(Request $request, Shift $shift): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $shift);

        $shift->delete();

        return $this->flashRedirect($request, __('flash.shift.deleted'), route('shifts.index'));
    }

    /**
     * @return array{id: int, name: string, start_time: string, end_time: string, grace_period_minutes: int, staff_count: int}
     */
    private function shiftAttributes(Shift $shift): array
    {
        return [
            'id' => $shift->id,
            'name' => $shift->name,
            'start_time' => substr((string) $shift->start_time, 0, 5),
            'end_time' => substr((string) $shift->end_time, 0, 5),
            'grace_period_minutes' => $shift->grace_period_minutes,
            'staff_count' => (int) ($shift->users_count ?? $shift->users()->count()),
        ];
    }
}
