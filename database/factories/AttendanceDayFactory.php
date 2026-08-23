<?php

namespace Database\Factories;

use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceDay>
 */
class AttendanceDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'date' => now()->toDateString(),
            'check_in_starts_at' => '08:00:00',
            'check_in_ends_at' => '11:00:00',
            'check_out_starts_at' => '16:00:00',
            'check_out_ends_at' => '19:00:00',
            'check_in_is_open' => true,
            'check_out_is_open' => true,
            'created_by' => User::factory()->superAdmin(),
        ];
    }

    public function openAllDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'check_in_starts_at' => '00:00:00',
            'check_in_ends_at' => '23:59:59',
            'check_out_starts_at' => '00:00:00',
            'check_out_ends_at' => '23:59:59',
        ]);
    }
}
