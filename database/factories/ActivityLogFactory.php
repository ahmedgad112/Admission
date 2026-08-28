<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'causer_id' => User::factory(),
            'event' => 'created',
            'subject_type' => null,
            'subject_id' => null,
            'properties' => ['name' => fake()->name()],
            'ip_address' => '127.0.0.1',
        ];
    }
}
