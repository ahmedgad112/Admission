<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $branch = Branch::factory();

        return [
            'user_id' => User::factory()->state([
                'branch_id' => $branch,
            ]),
            'branch_id' => $branch,
            'date' => now()->toDateString(),
            'check_in' => now()->setTime(9, 5),
            'check_out' => null,
            'check_in_lat' => 30.0444000,
            'check_in_long' => 31.2357000,
            'status' => AttendanceStatus::Present,
            'late_minutes' => 0,
            'early_leave_minutes' => 0,
            'work_hours' => 0,
        ];
    }
}
