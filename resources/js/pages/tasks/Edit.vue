<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Option = { id: number; name: string };
type Task = {
    id: number;
    title: string;
    description: string | null;
    assigned_to: number | null;
    department_id: number | null;
    priority: string;
    status: string;
    due_date: string | null;
};

const props = defineProps<{
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

const form = useForm({
    title: props.task.title,
    description: props.task.description ?? '',
    assigned_to: props.task.assigned_to ?? '',
    department_id: props.task.department_id ?? '',
    priority: props.task.priority,
    status: props.task.status,
    due_date: props.task.due_date ?? '',
});

function submit(): void {
    form.transform((data) => ({
        ...data,
        assigned_to: data.assigned_to || null,
        department_id: data.department_id || null,
        due_date: data.due_date || null,
    })).put(`/tasks/${props.task.id}`);
}
</script>

<template>
    <Head :title="trans('common.edit')" />

    <div class="page-shell">
        <PageHeader :eyebrow="trans('tasks.eyebrow')" :title="trans('common.edit')" />
        <Card class="mx-auto w-full max-w-2xl shadow-sm">
            <CardContent class="space-y-4 pt-6">
                <div class="space-y-2">
                    <Label for="title">Title</Label>
                    <Input id="title" v-model="form.title" />
                </div>
                <div class="space-y-2">
                    <Label for="description">Description</Label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="field-control min-h-28 py-3"
                    />
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="assigned_to">{{ trans('tasks.assignee') }}</Label>
                        <select
                            id="assigned_to"
                            v-model="form.assigned_to"
                            class="field-control"
                        >
                            <option value="">{{ trans('tasks.department_wide') }}</option>
                            <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                {{ employee.name }}
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <Label for="department_id">{{ trans('common.department') }}</Label>
                        <select
                            id="department_id"
                            v-model="form.department_id"
                            class="field-control"
                        >
                            <option value="">{{ trans('common.none') }}</option>
                            <option v-for="department in departments" :key="department.id" :value="department.id">
                                {{ department.name }}
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <Label for="priority">{{ trans('tasks.priority') }}</Label>
                        <select
                            id="priority"
                            v-model="form.priority"
                            class="field-control"
                        >
                            <option v-for="priority in priorities" :key="priority" :value="priority">
                                {{ trans(`priority.${priority}`) }}
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <Label for="status">{{ trans('common.status') }}</Label>
                        <select
                            id="status"
                            v-model="form.status"
                            class="field-control"
                        >
                            <option v-for="status in statuses" :key="status" :value="status">
                                {{ trans(`status.${status}`) }}
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <Label for="due_date">{{ trans('tasks.due_date') }}</Label>
                        <Input id="due_date" v-model="form.due_date" type="date" />
                    </div>
                </div>
                <Button class="rounded-full" :disabled="form.processing" @click="submit">{{ trans('tasks.save') }}</Button>
            </CardContent>
        </Card>
    </div>
</template>
