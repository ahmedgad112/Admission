<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Option = {
    id: number;
    name: string;
    department?: { id: number; name: string } | null;
};
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

const props = defineProps<{
    employees: Option[];
    departments: Option[];
    priorities: string[];
    statuses: string[];
    task?: Task;
}>();

const search = ref('');

const form = useForm({
    title: props.task?.title ?? '',
    description: props.task?.description ?? '',
    assignee_ids: [
        ...(props.task?.assignees?.map((assignee) => assignee.id) ?? []),
    ],
    department_id: props.task?.department_id ?? '',
    priority: props.task?.priority ?? 'medium',
    status: props.task?.status ?? 'todo',
    due_date: props.task?.due_date ?? '',
});

const visibleEmployees = computed(() => {
    const query = search.value.trim().toLowerCase();

    if (query === '') {
        return props.employees;
    }

    return props.employees.filter((employee) =>
        employee.name.toLowerCase().includes(query),
    );
});

function isSelected(id: number): boolean {
    return form.assignee_ids.includes(id);
}

function toggleAssignee(id: number, checked: boolean | 'indeterminate'): void {
    const selected = new Set(form.assignee_ids);

    if (checked === true) {
        selected.add(id);
    } else {
        selected.delete(id);
    }

    form.assignee_ids = [...selected];
}

function selectVisible(): void {
    const selected = new Set(form.assignee_ids);
    visibleEmployees.value.forEach((employee) => selected.add(employee.id));
    form.assignee_ids = [...selected];
}

function clearAssignees(): void {
    form.assignee_ids = [];
}

function submit(): void {
    form.transform((data) => ({
        ...data,
        department_id: data.department_id || null,
        due_date: data.due_date || null,
    }));

    if (props.task) {
        form.put(`/tasks/${props.task.id}`);

        return;
    }

    form.post('/tasks');
}
</script>

<template>
    <Card class="mx-auto w-full max-w-2xl shadow-sm">
        <CardContent class="space-y-4 pt-6">
            <div class="space-y-2">
                <Label for="title">{{ trans('common.title') }}</Label>
                <Input id="title" v-model="form.title" />
                <p v-if="form.errors.title" class="text-sm text-destructive">
                    {{ form.errors.title }}
                </p>
            </div>
            <div class="space-y-2">
                <Label for="description">{{
                    trans('common.description')
                }}</Label>
                <textarea
                    id="description"
                    v-model="form.description"
                    class="field-control min-h-28 py-3"
                />
            </div>
            <div class="space-y-3">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <Label>{{ trans('tasks.assignees') }}</Label>
                        <p class="text-sm text-muted-foreground">
                            {{
                                trans('tasks.assignees_help', {
                                    selected: form.assignee_ids.length,
                                    total: employees.length,
                                })
                            }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            class="rounded-full"
                            @click="selectVisible"
                        >
                            {{ trans('roster.select_visible') }}
                        </Button>
                        <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            class="rounded-full"
                            @click="clearAssignees"
                        >
                            {{ trans('roster.clear') }}
                        </Button>
                    </div>
                </div>
                <Input
                    v-model="search"
                    type="search"
                    :placeholder="trans('tasks.search_assignees')"
                />
                <p
                    v-if="form.errors.assignee_ids"
                    class="text-sm text-destructive"
                >
                    {{ form.errors.assignee_ids }}
                </p>
                <div
                    class="max-h-64 space-y-1 overflow-y-auto rounded-2xl border p-2"
                >
                    <p
                        v-if="visibleEmployees.length === 0"
                        class="px-3 py-8 text-center text-sm text-muted-foreground"
                    >
                        {{ trans('tasks.no_assignees_match') }}
                    </p>
                    <label
                        v-for="employee in visibleEmployees"
                        :key="employee.id"
                        class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-muted/70"
                    >
                        <Checkbox
                            :model-value="isSelected(employee.id)"
                            @update:model-value="
                                toggleAssignee(employee.id, $event)
                            "
                        />
                        <span class="min-w-0 flex-1">
                            <span class="block font-medium">{{
                                employee.name
                            }}</span>
                            <span class="block text-xs text-muted-foreground">
                                {{
                                    employee.department?.name ??
                                    trans('common.no_department')
                                }}
                            </span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <Label for="department_id">{{
                        trans('common.department')
                    }}</Label>
                    <select
                        id="department_id"
                        v-model="form.department_id"
                        class="field-control"
                    >
                        <option value="">{{ trans('common.none') }}</option>
                        <option
                            v-for="department in departments"
                            :key="department.id"
                            :value="department.id"
                        >
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
                        <option
                            v-for="priority in priorities"
                            :key="priority"
                            :value="priority"
                        >
                            {{ trans(`priority.${priority}`) }}
                        </option>
                    </select>
                </div>
                <div v-if="task" class="space-y-2">
                    <Label for="status">{{ trans('common.status') }}</Label>
                    <select
                        id="status"
                        v-model="form.status"
                        class="field-control"
                    >
                        <option
                            v-for="status in statuses"
                            :key="status"
                            :value="status"
                        >
                            {{ trans(`status.${status}`) }}
                        </option>
                    </select>
                </div>
                <div class="space-y-2">
                    <Label for="due_date">{{ trans('tasks.due_date') }}</Label>
                    <Input id="due_date" v-model="form.due_date" type="date" />
                </div>
            </div>
            <Button
                class="rounded-full"
                :disabled="form.processing"
                @click="submit"
                >{{ trans('tasks.save') }}</Button
            >
        </CardContent>
    </Card>
</template>
