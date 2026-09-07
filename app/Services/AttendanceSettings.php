<?php

namespace App\Services;

use App\Models\QrSession;
use App\Models\Setting;

class AttendanceSettings
{
    public const QR_TTL_KEY = 'attendance.qr_ttl_seconds';

    public function qrTtlSeconds(): int
    {
        return $this->clamp($this->storedOrDefault());
    }

    public function updateQrTtlSeconds(int $seconds): int
    {
        $seconds = $this->clamp($seconds);

        Setting::query()->updateOrCreate(
            ['key' => self::QR_TTL_KEY],
            ['value' => (string) $seconds],
        );

        QrSession::query()
            ->where('expires_at', '>', now())
            ->update(['expires_at' => now()]);

        return $seconds;
    }

    public function minTtl(): int
    {
        return max(10, (int) config('attendance.qr_ttl_min', 10));
    }

    public function maxTtl(): int
    {
        return max($this->minTtl(), (int) config('attendance.qr_ttl_max', 600));
    }

    /**
     * @return list<int>
     */
    public function presets(): array
    {
        /** @var mixed $configured */
        $configured = config('attendance.qr_ttl_presets', [20, 30, 45, 60, 90, 120, 180, 300]);

        if (! is_array($configured)) {
            return [];
        }

        $presets = [];

        foreach ($configured as $preset) {
            if (! is_numeric($preset)) {
                continue;
            }

            $seconds = $this->clamp((int) $preset);

            if (! in_array($seconds, $presets, true)) {
                $presets[] = $seconds;
            }
        }

        sort($presets);

        return $presets;
    }

    private function storedOrDefault(): int
    {
        $stored = Setting::query()->where('key', self::QR_TTL_KEY)->value('value');

        if (is_numeric($stored)) {
            return (int) $stored;
        }

        return (int) config('attendance.qr_ttl_seconds', 60);
    }

    private function clamp(int $seconds): int
    {
        return max($this->minTtl(), min($this->maxTtl(), $seconds));
    }
}
