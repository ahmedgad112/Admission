<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'created_by' => User::factory()->manager(),
            'department_id' => null,
            'priority' => TaskPriority::Medium,
            'status' => TaskStatus::Todo,
            'due_date' => now()->addDays(3)->toDateString(),
            'completed_at' => null,
        ];
    }

    public function assignedTo(User ...$users): static
    {
        return $this->afterCreating(function (Task $task) use ($users): void {
            $task->assignees()->sync(array_map(
                fn (User $user): int => $user->id,
                $users,
            ));
        });
    }
}
