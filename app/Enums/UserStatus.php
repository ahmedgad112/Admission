<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Active => __('status.active'),
            self::Inactive => __('status.inactive'),
            self::Suspended => __('status.suspended'),
        };
    }
}
