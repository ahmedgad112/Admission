<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { trans } from '@/composables/useTrans';
import { taskPriorityTone, taskStatusTone } from '@/lib/status';

type TaskRow = {
    id: number;
    title: string;
    priority: string;
    status: string;
    due_date: string | null;
    assignee?: { id: number; name: string } | null;
    department?: { id: number; name: string } | null;
};

defineProps<{
    tasks: { data: TaskRow[] };
    filters: { status: string; priority: string };
    canCreate: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.tasks', href: '/tasks' },
        ],
    },
});

function filter(key: 'status' | 'priority', value: string): void {
    router.get(
        '/tasks',
        { [key]: value || undefined },
        { preserveState: true, replace: true },
    );
}
</script>

<template>
    <Head :title="trans('tasks.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('tasks.eyebrow')"
            :title="trans('tasks.title')"
            :description="trans('tasks.description')"
        >
            <template #actions>
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/tasks/create">{{ trans('tasks.new') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <div class="flex flex-wrap gap-3">
            <select
                :value="filters.status"
                class="field-control max-w-48"
                @change="filter('status', ($event.target as HTMLSelectElement).value)"
            >
                <option value="">{{ trans('status.all') }}</option>
                <option value="todo">{{ trans('status.todo') }}</option>
                <option value="in_progress">{{ trans('status.in_progress') }}</option>
                <option value="review">{{ trans('status.review') }}</option>
                <option value="completed">{{ trans('status.completed') }}</option>
            </select>
            <select
                :value="filters.priority"
                class="field-control max-w-48"
                @change="filter('priority', ($event.target as HTMLSelectElement).value)"
            >
                <option value="">{{ trans('priority.all') }}</option>
                <option value="low">{{ trans('priority.low') }}</option>
                <option value="medium">{{ trans('priority.medium') }}</option>
                <option value="high">{{ trans('priority.high') }}</option>
                <option value="urgent">{{ trans('priority.urgent') }}</option>
            </select>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card
                v-for="task in tasks.data"
                :key="task.id"
                class="shadow-sm transition-transform hover:-translate-y-0.5"
            >
                <CardHeader>
                    <CardTitle class="text-lg">
                        <Link :href="`/tasks/${task.id}`" class="hover:text-primary">
                            {{ task.title }}
                        </Link>
                    </CardTitle>
                    <CardDescription>
                        {{ task.assignee?.name ?? task.department?.name ?? trans('tasks.unassigned') }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="flex items-center justify-between">
                    <div class="flex flex-wrap gap-2">
                        <StatusBadge :value="task.priority" :tone="taskPriorityTone(task.priority)" />
                        <StatusBadge :value="task.status" :tone="taskStatusTone(task.status)" />
                    </div>
                    <span class="text-xs text-muted-foreground">
                        {{ task.due_date ?? trans('tasks.no_due') }}
                    </span>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
