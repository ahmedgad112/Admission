<?php

namespace Database\Factories;

use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->addDay()->toDateString();

        return [
            'user_id' => User::factory()->employee(),
            'branch_id' => null,
            'department_id' => null,
            'start_date' => $startDate,
            'end_date' => $startDate,
            'type' => LeaveRequestType::Permission,
            'reason' => fake()->sentence(),
            'status' => LeaveRequestStatus::Pending,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_note' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LeaveRequestStatus::Approved,
            'reviewed_by' => User::factory()->manager(),
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LeaveRequestStatus::Rejected,
            'reviewed_by' => User::factory()->manager(),
            'reviewed_at' => now(),
            'review_note' => 'Coverage is already thin that day.',
        ]);
    }
}
