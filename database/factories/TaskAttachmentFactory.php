<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskAttachment>
 */
class TaskAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'original_name' => 'notes.txt',
            'disk' => 'local',
            'path' => 'task-attachments/example.txt',
            'mime_type' => 'text/plain',
            'size' => 128,
        ];
    }
}
