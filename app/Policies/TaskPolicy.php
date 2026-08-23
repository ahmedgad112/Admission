<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return Task::query()->whereKey($task->id)->visibleTo($user)->exists();
    }

    public function create(User $user): bool
    {
        return $user->canManageTasks();
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->canManageTasks() && $this->view($user, $task)) {
            return true;
        }

        return $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->canManageTasks() && $this->view($user, $task);
    }

    public function comment(User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }
}
