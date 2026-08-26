<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import TaskForm from '@/components/TaskForm.vue';
import { trans } from '@/composables/useTrans';

type Option = { id: number; name: string };
type Task = {
    id: number;
    title: string;
    description: string | null;
    department_id: number | null;
    priority: string;
    status: string;
    due_date: string | null;
    assignees?: Option[];
};

defineProps<{
    task: Task;
    employees: Option[];
    departments: Option[];
    priorities: string[];
    statuses: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.tasks', href: '/tasks' },
            { title: 'common.edit', href: '/tasks' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('common.edit')" />

    <div class="page-shell">
        <PageHeader :eyebrow="trans('tasks.eyebrow')" :title="trans('common.edit')" />
        <TaskForm
            :task="task"
            :employees="employees"
            :departments="departments"
            :priorities="priorities"
            :statuses="statuses"
        />
    </div>
</template>
