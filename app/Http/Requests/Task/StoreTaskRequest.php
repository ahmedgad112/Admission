<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    use ValidatesTaskAssignees;

    public function authorize(): bool
    {
        return $this->user()?->can('create', Task::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'assignee_ids' => ['nullable', 'array'],
            'assignee_ids.*' => ['integer', 'distinct', $this->visibleAssigneeRule()],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'status' => ['nullable', Rule::enum(TaskStatus::class)],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
