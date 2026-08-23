<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Present = 'present';
    case Late = 'late';
    case EarlyLeave = 'early_leave';
    case LateAndEarlyLeave = 'late_and_early_leave';
    case Incomplete = 'incomplete';
    case Absent = 'absent';
}
