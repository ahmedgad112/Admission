<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public static function enabled(): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        return ! app()->runningInConsole();
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public static function record(
        string $event,
        ?Model $subject = null,
        array $properties = [],
        ?User $causer = null,
    ): void {
        if (! self::enabled()) {
            return;
        }

        $causer ??= auth()->user();

        ActivityLog::query()->create([
            'causer_id' => $causer?->id,
            'event' => $event,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
        ]);
    }
}
