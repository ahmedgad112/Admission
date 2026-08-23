<?php

namespace App\Enums;

enum QrSessionType: string
{
    case CheckIn = 'check_in';
    case CheckOut = 'check_out';
}
