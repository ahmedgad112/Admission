<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Services\AttendanceSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(),
            'value' => (string) fake()->numberBetween(20, 180),
        ];
    }

    public function qrTtl(int $seconds): static
    {
        return $this->state(fn (): array => [
            'key' => AttendanceSettings::QR_TTL_KEY,
            'value' => (string) $seconds,
        ]);
    }
}
