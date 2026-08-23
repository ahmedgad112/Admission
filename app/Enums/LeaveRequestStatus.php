<?php

namespace App\Enums;

enum LeaveRequestStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('status.pending'),
            self::Approved => __('status.approved'),
            self::Rejected => __('status.rejected'),
            self::Cancelled => __('status.cancelled'),
        };
    }

    public function isOpen(): bool
    {
        return $this === self::Pending;
    }
}
