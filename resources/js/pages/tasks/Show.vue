<script setup lang="ts">
import { Head, Link, router, useForm, usePoll } from '@inertiajs/vue3';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { trans } from '@/composables/useTrans';
import { taskPriorityTone, taskStatusTone } from '@/lib/status';

type Person = { id: number; name: string };
type Task = {
    id: number;
    title: string;
    description: string | null;
    priority: string;
    status: string;
    due_date: string | null;
    assignees?: Person[];
    creator?: Person | null;
    department?: { id: number; name: string } | null;
    comments: Array<{
        id: number;
        body: string;
        created_at: string;
        user?: Person;
    }>;
    attachments: Array<{ id: number; original_name: string; size: number }>;
    activities: Array<{
        id: number;
        action: string;
        created_at: string;
        properties?: Record<string, string>;
        user?: Person | null;
    }>;
};

const props = defineProps<{
    task: Task;
    canUpdate: boolean;
    canDelete: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.tasks', href: '/tasks' },
            { title: 'common.details', href: '/tasks' },
        ],
    },
});

usePoll(8000);

const commentForm = useForm({
    body: '',
});

const attachmentForm = useForm({
    file: null as File | null,
});

function addComment(): void {
    commentForm.post(`/tasks/${props.task.id}/comments`, {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
}

function upload(): void {
    attachmentForm.post(`/tasks/${props.task.id}/attachments`, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => attachmentForm.reset(),
    });
}

function transition(status: string): void {
    router.post(
        `/tasks/${props.task.id}/transition`,
        { status },
        { preserveScroll: true },
    );
}

function destroy(): void {
    router.delete(`/tasks/${props.task.id}`);
}
</script>

<template>
    <Head :title="task.title" />

    <div class="page-shell">
        <div
            class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between"
        >
            <div>
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-primary uppercase"
                >
                    {{ trans('tasks.title') }}
                </p>
                <h1
                    class="mt-1 text-2xl font-semibold tracking-tight md:text-3xl"
                >
                    {{ task.title }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    {{
                        task.assignees?.length
                            ? task.assignees
                                  .map((assignee) => assignee.name)
                                  .join(' · ')
                            : trans('tasks.department_assignment')
                    }}
                    ·
                    {{ task.department?.name ?? trans('common.no_department') }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <StatusBadge
                    :value="task.priority"
                    :tone="taskPriorityTone(task.priority)"
                />
                <StatusBadge
                    :value="task.status"
                    :tone="taskStatusTone(task.status)"
                />
                <Button
                    v-if="canUpdate"
                    variant="outline"
                    class="rounded-full"
                    as-child
                >
                    <Link :href="`/tasks/${task.id}/edit`">{{
                        trans('common.edit')
                    }}</Link>
                </Button>
                <Button
                    v-if="canDelete"
                    variant="destructive"
                    class="rounded-full"
                    @click="destroy"
                >
                    {{ trans('common.delete') }}
                </Button>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <div class="space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>{{ trans('common.details') }}</CardTitle>
                        <CardDescription>
                            {{
                                task.due_date
                                    ? trans('tasks.due', {
                                          date: task.due_date,
                                      })
                                    : trans('tasks.due_anytime')
                            }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <p class="text-sm whitespace-pre-wrap">
                            {{
                                task.description ||
                                trans('tasks.no_description')
                            }}
                        </p>
                        <div v-if="canUpdate" class="flex flex-wrap gap-2">
                            <Button
                                size="sm"
                                variant="outline"
                                class="rounded-full"
                                @click="transition('todo')"
                                >{{ trans('status.todo') }}</Button
                            >
                            <Button
                                size="sm"
                                variant="outline"
                                class="rounded-full"
                                @click="transition('in_progress')"
                                >{{ trans('status.in_progress') }}</Button
                            >
                            <Button
                                size="sm"
                                variant="outline"
                                class="rounded-full"
                                @click="transition('review')"
                                >{{ trans('status.review') }}</Button
                            >
                            <Button
                                size="sm"
                                class="rounded-full"
                                @click="transition('completed')"
                                >{{ trans('status.completed') }}</Button
                            >
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{{ trans('tasks.comments') }}</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div
                            v-for="comment in task.comments"
                            :key="comment.id"
                            class="rounded-2xl border bg-muted/40 p-3"
                        >
                            <div class="mb-1 text-sm font-medium">
                                {{ comment.user?.name }}
                            </div>
                            <p class="text-sm">{{ comment.body }}</p>
                        </div>
                        <div class="space-y-2">
                            <textarea
                                v-model="commentForm.body"
                                class="field-control min-h-24 py-3"
                                :placeholder="
                                    trans('tasks.comment_placeholder')
                                "
                            />
                            <Button
                                size="sm"
                                :disabled="commentForm.processing"
                                @click="addComment"
                            >
                                {{ trans('tasks.add_comment') }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>{{ trans('tasks.attachments') }}</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <a
                            v-for="attachment in task.attachments"
                            :key="attachment.id"
                            :href="`/tasks/${task.id}/attachments/${attachment.id}`"
                            class="block text-sm text-primary underline"
                        >
                            {{ attachment.original_name }}
                        </a>
                        <Input
                            type="file"
                            @change="
                                attachmentForm.file =
                                    ($event.target as HTMLInputElement)
                                        .files?.[0] ?? null
                            "
                        />
                        <Button
                            size="sm"
                            variant="outline"
                            :disabled="attachmentForm.processing"
                            @click="upload"
                        >
                            {{ trans('tasks.upload') }}
                        </Button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{{ trans('tasks.activity') }}</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm">
                        <div
                            v-for="activity in task.activities"
                            :key="activity.id"
                        >
                            <div class="font-medium">{{ activity.action }}</div>
                            <div class="text-muted-foreground">
                                {{
                                    activity.user?.name ?? trans('tasks.system')
                                }}
                                ·
                                {{
                                    new Date(
                                        activity.created_at,
                                    ).toLocaleString()
                                }}
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
