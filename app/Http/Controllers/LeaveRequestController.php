<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use App\Http\Requests\LeaveRequest\ReviewLeaveRequestRequest;
use App\Http\Requests\LeaveRequest\StoreLeaveRequestRequest;
use App\Models\LeaveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

class LeaveRequestController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', LeaveRequest::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        /** @var LengthAwarePaginator<int, LeaveRequest> $leaveRequests */
        $leaveRequests = LeaveRequest::query()
            ->visibleTo($user)
            ->with(['user:id,name', 'department:id,name', 'branch:id,name'])
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->string('type')->isNotEmpty(), fn ($query) => $query->where('type', $request->string('type')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('leave-requests/Index', [
            'leaveRequests' => $leaveRequests,
            'filters' => [
                'status' => $request->string('status')->toString(),
                'type' => $request->string('type')->toString(),
            ],
            'canCreate' => $user->can('create', LeaveRequest::class),
            'canReview' => $user->canReviewLeaveRequests(),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', LeaveRequest::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        return Inertia::render('leave-requests/Create', [
            'types' => array_map(fn (LeaveRequestType $type) => [
                'value' => $type->value,
                'label' => $type->label(),
            ], LeaveRequestType::cases()),
            'leaveBalance' => [
                'allocated' => $user->leave_days,
                'used' => $user->usedLeaveDays(),
                'remaining' => $user->remainingLeaveDays(),
            ],
        ]);
    }

    public function store(StoreLeaveRequestRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $leaveRequest = LeaveRequest::query()->create([
            ...$request->validated(),
            'user_id' => $user->id,
            'branch_id' => $user->branch_id,
            'department_id' => $user->department_id,
            'status' => LeaveRequestStatus::Pending,
        ]);

        return $this->flashRedirect($request, __('flash.leave.submitted'), route('leave-requests.show', $leaveRequest), [
            'leaveRequest' => $leaveRequest,
        ]);
    }

    public function show(Request $request, LeaveRequest $leaveRequest): Response
    {
        $this->authorize('view', $leaveRequest);

        $leaveRequest->load([
            'user:id,name,email',
            'branch:id,name',
            'department:id,name',
            'reviewer:id,name',
        ]);

        return Inertia::render('leave-requests/Show', [
            'leaveRequest' => $leaveRequest,
            'canReview' => $request->user()?->can('review', $leaveRequest) ?? false,
            'canCancel' => $request->user()?->can('cancel', $leaveRequest) ?? false,
        ]);
    }

    public function review(ReviewLeaveRequestRequest $request, LeaveRequest $leaveRequest): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $status = LeaveRequestStatus::from($request->validated('status'));

        $leaveRequest->fill([
            'status' => $status,
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'review_note' => $request->validated('review_note'),
        ])->save();

        $message = $status === LeaveRequestStatus::Approved
            ? __('flash.leave.approved')
            : __('flash.leave.rejected');

        return $this->flashRedirect($request, $message, route('leave-requests.show', $leaveRequest), [
            'leaveRequest' => $leaveRequest->refresh(),
        ]);
    }

    public function cancel(Request $request, LeaveRequest $leaveRequest): JsonResponse|RedirectResponse
    {
        $this->authorize('cancel', $leaveRequest);

        $leaveRequest->update([
            'status' => LeaveRequestStatus::Cancelled,
        ]);

        return $this->flashRedirect($request, __('flash.leave.cancelled'), route('leave-requests.show', $leaveRequest), [
            'leaveRequest' => $leaveRequest->refresh(),
        ]);
    }
}
