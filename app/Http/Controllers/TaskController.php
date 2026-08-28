<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\Task\StoreTaskAttachmentRequest;
use App\Http\Requests\Task\StoreTaskCommentRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\TransitionTaskStatusRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Department;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskComment;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TaskController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function index(Request $request): JsonResponse|Response
    {
        $this->authorize('viewAny', Task::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        /** @var LengthAwarePaginator<int, Task> $tasks */
        $tasks = Task::query()
            ->visibleTo($user)
            ->with(['assignees:id,name', 'department:id,name', 'creator:id,name'])
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->string('priority')->isNotEmpty(), fn ($query) => $query->where('priority', $request->string('priority')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($tasks);
        }

        return Inertia::render('tasks/Index', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $request->string('status')->toString(),
                'priority' => $request->string('priority')->toString(),
            ],
            'canCreate' => $user->can('create', Task::class),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', Task::class);

        return Inertia::render('tasks/Create', $this->formOptions($request));
    }

    public function store(StoreTaskRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $payload = $request->safe()->except('assignee_ids');

        $task = Task::query()->create([
            ...$payload,
            'created_by' => $user->id,
            'status' => $payload['status'] ?? TaskStatus::Todo->value,
        ]);

        $task->assignees()->sync($request->validated('assignee_ids') ?? []);

        ActivityLogger::record('assignees_synced', $task, [
            'name' => $task->title,
            'assignee_ids' => $request->validated('assignee_ids') ?? [],
        ]);

        $this->logActivity($task, $user, 'created', [
            'priority' => $task->priority->value,
            'status' => $task->status->value,
        ]);

        return $this->flashRedirect($request, __('flash.task.created'), route('tasks.show', $task), [
            'task' => $task,
        ]);
    }

    public function show(Request $request, Task $task): Response
    {
        $this->authorize('view', $task);

        $task->load([
            'assignees:id,name,email',
            'creator:id,name',
            'department:id,name',
            'comments.user:id,name',
            'attachments.user:id,name',
            'activities.user:id,name',
        ]);

        return Inertia::render('tasks/Show', [
            'task' => $task,
            'canUpdate' => $request->user()?->can('update', $task) ?? false,
            'canDelete' => $request->user()?->can('delete', $task) ?? false,
        ]);
    }

    public function edit(Request $request, Task $task): Response
    {
        $this->authorize('update', $task);

        $task->load('assignees:id,name');

        return Inertia::render('tasks/Edit', [
            'task' => $task,
            ...$this->formOptions($request),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $previousStatus = $task->status;
        $task->fill($request->safe()->except('assignee_ids'));

        if ($task->status === TaskStatus::Completed && $task->completed_at === null) {
            $task->completed_at = now();
        }

        if ($task->status !== TaskStatus::Completed) {
            $task->completed_at = null;
        }

        $task->save();
        $task->assignees()->sync($request->validated('assignee_ids') ?? []);

        ActivityLogger::record('assignees_synced', $task, [
            'name' => $task->title,
            'assignee_ids' => $request->validated('assignee_ids') ?? [],
        ]);

        if ($previousStatus !== $task->status) {
            $this->logActivity($task, $user, 'status_changed', [
                'from' => $previousStatus->value,
                'to' => $task->status->value,
            ]);
        } else {
            $this->logActivity($task, $user, 'updated');
        }

        return $this->flashRedirect($request, __('flash.task.updated'), route('tasks.show', $task), [
            'task' => $task,
        ]);
    }

    public function destroy(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return $this->flashRedirect($request, __('flash.task.deleted'), route('tasks.index'));
    }

    public function transition(TransitionTaskStatusRequest $request, Task $task): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $status = TaskStatus::from($request->validated('status'));
        $previous = $task->status;

        $task->status = $status;
        $task->completed_at = $status === TaskStatus::Completed ? now() : null;
        $task->save();

        $this->logActivity($task, $user, 'status_changed', [
            'from' => $previous->value,
            'to' => $status->value,
        ]);

        return $this->flashRedirect($request, __('flash.task.status'), route('tasks.show', $task), [
            'task' => $task->refresh(),
        ]);
    }

    public function comment(StoreTaskCommentRequest $request, Task $task): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $comment = TaskComment::query()->create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'body' => $request->validated('body'),
        ]);

        $this->logActivity($task, $user, 'commented');

        return $this->flashRedirect($request, __('flash.task.commented'), route('tasks.show', $task), [
            'comment' => $comment->load('user:id,name'),
        ]);
    }

    public function attach(StoreTaskAttachmentRequest $request, Task $task): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $file = $request->file('file');
        abort_unless($file !== null, 422);

        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $safeName.'-'.Str::random(8).($extension !== '' ? '.'.$extension : '');
        $path = $file->storeAs('task-attachments/'.$task->id, $filename, 'local');

        abort_unless(is_string($path), 500, 'Unable to store the attachment.');

        $attachment = TaskAttachment::query()->create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'disk' => 'local',
            'path' => $path,
            'mime_type' => $file->getClientMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ]);

        $this->logActivity($task, $user, 'attached', [
            'name' => $attachment->original_name,
        ]);

        return $this->flashRedirect($request, __('flash.task.attached'), route('tasks.show', $task), [
            'attachment' => $attachment,
        ]);
    }

    public function download(Task $task, TaskAttachment $attachment): BinaryFileResponse
    {
        $this->authorize('view', $task);
        abort_unless($attachment->task_id === $task->id, 404);

        $disk = Storage::disk($attachment->disk);
        abort_unless($disk->exists($attachment->path), 404);

        return response()->download(
            $disk->path($attachment->path),
            basename($attachment->original_name),
        );
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    private function logActivity(Task $task, User $user, string $action, array $properties = []): void
    {
        $task->activities()->create([
            'user_id' => $user->id,
            'action' => $action,
            'properties' => $properties,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(Request $request): array
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        return [
            'employees' => User::query()
                ->visibleTo($user)
                ->with('department:id,name')
                ->orderBy('name')
                ->get(['id', 'name', 'department_id']),
            'departments' => Department::query()
                ->when($user->limitsRecordsToBranch(), fn ($query) => $query->where('branch_id', $user->branch_id))
                ->when($user->limitsRecordsToTeam() || $user->recordScope() === 'self', fn ($query) => $query->whereKey($user->department_id))
                ->orderBy('name')
                ->get(['id', 'name']),
            'priorities' => array_map(fn (TaskPriority $priority) => $priority->value, TaskPriority::cases()),
            'statuses' => array_map(fn (TaskStatus $status) => $status->value, TaskStatus::cases()),
        ];
    }
}
