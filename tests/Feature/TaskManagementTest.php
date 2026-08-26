<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

test('task assignees are stored on the pivot instead of assigned_to', function () {
    expect(Schema::hasTable('task_user'))->toBeTrue()
        ->and(Schema::hasColumn('tasks', 'assigned_to'))->toBeFalse();
});

test('managers can create and assign tasks to multiple staff', function () {
    $department = Department::factory()->create();
    $manager = User::factory()->manager()->create([
        'department_id' => $department->id,
    ]);
    $employee = User::factory()->employee()->create([
        'department_id' => $department->id,
    ]);
    $teammate = User::factory()->employee()->create([
        'department_id' => $department->id,
    ]);

    $this->actingAs($manager)
        ->post(route('tasks.store'), [
            'title' => 'Prepare client demo',
            'description' => 'Collect screenshots and metrics.',
            'assignee_ids' => [$employee->id, $teammate->id],
            'department_id' => $department->id,
            'priority' => TaskPriority::High->value,
            'due_date' => now()->addDay()->toDateString(),
        ])
        ->assertRedirect();

    $task = Task::query()->first();

    expect($task)->not->toBeNull()
        ->and($task->title)->toBe('Prepare client demo')
        ->and($task->assignees()->pluck('users.id')->sort()->values()->all())->toBe(
            collect([$employee->id, $teammate->id])->sort()->values()->all(),
        )
        ->and($task->created_by)->toBe($manager->id)
        ->and($task->activities()->where('action', 'created')->exists())->toBeTrue();
});

test('employees cannot create tasks', function () {
    $employee = User::factory()->employee()->create();

    $this->actingAs($employee)
        ->post(route('tasks.store'), [
            'title' => 'Unauthorized task',
            'priority' => TaskPriority::Low->value,
        ])
        ->assertForbidden();
});

test('employees can comment on assigned tasks and cannot view foreign tasks', function () {
    $employee = User::factory()->employee()->create();
    $other = User::factory()->employee()->create();
    $assigned = Task::factory()->assignedTo($employee)->create();
    $foreign = Task::factory()->assignedTo($other)->create(['department_id' => null]);

    $this->actingAs($employee)
        ->post(route('tasks.comments.store', $assigned), [
            'body' => 'Working on this now.',
        ])
        ->assertRedirect(route('tasks.show', $assigned));

    $this->actingAs($employee)
        ->get(route('tasks.show', $foreign))
        ->assertForbidden();

    expect($assigned->comments()->where('body', 'Working on this now.')->exists())->toBeTrue();
});

test('managers can change the staff linked to a task', function () {
    $department = Department::factory()->create();
    $manager = User::factory()->manager()->create([
        'department_id' => $department->id,
    ]);
    $employee = User::factory()->employee()->create([
        'department_id' => $department->id,
    ]);
    $teammate = User::factory()->employee()->create([
        'department_id' => $department->id,
    ]);
    $task = Task::factory()->assignedTo($employee)->create([
        'created_by' => $manager->id,
        'department_id' => $department->id,
    ]);

    $this->actingAs($manager)
        ->put(route('tasks.update', $task), [
            'title' => $task->title,
            'description' => $task->description,
            'assignee_ids' => [$teammate->id],
            'department_id' => $department->id,
            'priority' => $task->priority->value,
            'status' => $task->status->value,
            'due_date' => $task->due_date?->toDateString(),
        ])
        ->assertRedirect(route('tasks.show', $task));

    expect($task->assignees()->pluck('users.id')->all())->toBe([$teammate->id]);
});

test('department-wide tasks stay visible to department employees', function () {
    $department = Department::factory()->create();
    $employee = User::factory()->employee()->create([
        'department_id' => $department->id,
    ]);
    $task = Task::factory()->create([
        'department_id' => $department->id,
    ]);

    $this->actingAs($employee)
        ->get(route('tasks.show', $task))
        ->assertOk();
});

test('assignees can transition task status', function () {
    $employee = User::factory()->employee()->create();
    $task = Task::factory()->assignedTo($employee)->create([
        'status' => TaskStatus::Todo,
    ]);

    $this->actingAs($employee)
        ->post(route('tasks.transition', $task), [
            'status' => TaskStatus::InProgress->value,
        ])
        ->assertRedirect(route('tasks.show', $task));

    expect($task->refresh()->status)->toBe(TaskStatus::InProgress);
});

test('task attachments are stored with sanitized file names', function () {
    Storage::fake('local');

    $employee = User::factory()->employee()->create();
    $task = Task::factory()->assignedTo($employee)->create();
    $file = UploadedFile::fake()->create('Quarterly Report.PDF', 120, 'application/pdf');

    $this->actingAs($employee)
        ->post(route('tasks.attachments.store', $task), [
            'file' => $file,
        ])
        ->assertRedirect(route('tasks.show', $task));

    $attachment = $task->attachments()->first();

    expect($attachment)->not->toBeNull()
        ->and($attachment->original_name)->toBe('Quarterly Report.PDF')
        ->and($attachment->path)->not->toContain('Quarterly Report.PDF');

    Storage::disk('local')->assertExists($attachment->path);
});

test('assignees can download task attachments', function () {
    Storage::fake('local');

    $employee = User::factory()->employee()->create();
    $task = Task::factory()->assignedTo($employee)->create();
    $file = UploadedFile::fake()->create('Quarterly Report.PDF', 120, 'application/pdf');

    $this->actingAs($employee)
        ->post(route('tasks.attachments.store', $task), [
            'file' => $file,
        ])
        ->assertRedirect(route('tasks.show', $task));

    $attachment = $task->attachments()->first();

    expect($attachment)->not->toBeNull();

    $this->actingAs($employee)
        ->get(route('tasks.attachments.download', [$task, $attachment]))
        ->assertOk()
        ->assertDownload('Quarterly Report.PDF');
});
