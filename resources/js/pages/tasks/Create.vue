<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import TaskForm from '@/components/TaskForm.vue';
import { trans } from '@/composables/useTrans';

type Option = { id: number; name: string };

defineProps<{
    employees: Option[];
    departments: Option[];
    priorities: string[];
    statuses: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.tasks', href: '/tasks' },
            { title: 'common.create', href: '/tasks/create' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('tasks.create')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('tasks.eyebrow')"
            :title="trans('tasks.create')"
            :description="trans('tasks.create_description')"
        />
        <TaskForm
            :employees="employees"
            :departments="departments"
            :priorities="priorities"
            :statuses="statuses"
        />
    </div>
</template>
