<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return LeaveRequest::query()->whereKey($leaveRequest->id)->visibleTo($user)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function review(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->user_id === $user->id || ! $leaveRequest->isPending()) {
            return false;
        }

        return $user->canReviewLeaveRequests() && $this->view($user, $leaveRequest);
    }

    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        return $leaveRequest->isPending() && $leaveRequest->user_id === $user->id;
    }
}
