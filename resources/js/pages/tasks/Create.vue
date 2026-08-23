<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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

const form = useForm({
    title: '',
    description: '',
    assigned_to: '',
    department_id: '',
    priority: 'medium',
    status: 'todo',
    due_date: '',
});

function submit(): void {
    form.transform((data) => ({
        ...data,
        assigned_to: data.assigned_to || null,
        department_id: data.department_id || null,
        due_date: data.due_date || null,
    })).post('/tasks');
}
</script>

<template>
    <Head :title="trans('tasks.create')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('tasks.eyebrow')"
            :title="trans('tasks.create')"
            :description="trans('tasks.create_description')"
        />
        <Card class="mx-auto w-full max-w-2xl shadow-sm">
            <CardContent class="space-y-4 pt-6">
                <div class="space-y-2">
                    <Label for="title">Title</Label>
                    <Input id="title" v-model="form.title" />
                    <p v-if="form.errors.title" class="text-sm text-destructive">
                        {{ form.errors.title }}
                    </p>
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
                        <Label for="due_date">{{ trans('tasks.due_date') }}</Label>
                        <Input id="due_date" v-model="form.due_date" type="date" />
                    </div>
                </div>
                <Button class="rounded-full" :disabled="form.processing" @click="submit">{{ trans('tasks.save') }}</Button>
            </CardContent>
        </Card>
    </div>
</template>
