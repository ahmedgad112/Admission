<?php

namespace App\Enums;

enum LeaveRequestType: string
{
    case Permission = 'permission';
    case Sick = 'sick';
    case Personal = 'personal';

    public function label(): string
    {
        return match ($this) {
            self::Permission => __('leave.type.permission'),
            self::Sick => __('leave.type.sick'),
            self::Personal => __('leave.type.personal'),
        };
    }
}
