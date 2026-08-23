<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('managers can create and assign tasks', function () {
    $manager = User::factory()->manager()->create();
    $employee = User::factory()->employee()->create([
        'department_id' => Department::factory()->create()->id,
    ]);

    $this->actingAs($manager)
        ->post(route('tasks.store'), [
            'title' => 'Prepare client demo',
            'description' => 'Collect screenshots and metrics.',
            'assigned_to' => $employee->id,
            'department_id' => $employee->department_id,
            'priority' => TaskPriority::High->value,
            'due_date' => now()->addDay()->toDateString(),
        ])
        ->assertRedirect();

    $task = Task::query()->first();

    expect($task)->not->toBeNull()
        ->and($task->title)->toBe('Prepare client demo')
        ->and($task->assigned_to)->toBe($employee->id)
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
    $assigned = Task::factory()->create(['assigned_to' => $employee->id]);
    $foreign = Task::factory()->create(['assigned_to' => $other->id, 'department_id' => null]);

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

test('assignees can transition task status', function () {
    $employee = User::factory()->employee()->create();
    $task = Task::factory()->create([
        'assigned_to' => $employee->id,
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
    $task = Task::factory()->create(['assigned_to' => $employee->id]);
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
    $task = Task::factory()->create(['assigned_to' => $employee->id]);
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
