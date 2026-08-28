<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Option = { id: number; name: string };
type Department = {
    id: number;
    name: string;
    branch_id: number;
    manager_id: number | null;
};

const props = defineProps<{
    branches: Option[];
    managers: Option[];
    defaultBranchId?: number | null;
    department?: Department;
}>();

const form = useForm({
    name: props.department?.name ?? '',
    branch_id: props.department?.branch_id ?? props.defaultBranchId ?? props.branches[0]?.id ?? '',
    manager_id: props.department?.manager_id ?? '',
});

function submit(): void {
    form.transform((data) => ({
        ...data,
        manager_id: data.manager_id || null,
    }));

    if (props.department) {
        form.put(`/departments/${props.department.id}`);

        return;
    }

    form.post('/departments');
}
</script>

<template>
    <Card class="mx-auto w-full max-w-2xl shadow-sm">
        <CardContent class="space-y-4 pt-6">
            <div class="space-y-2">
                <Label for="name">{{ trans('departments.name') }}</Label>
                <Input id="name" v-model="form.name" />
                <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
            </div>
            <div class="space-y-2">
                <Label for="branch_id">{{ trans('common.branch') }}</Label>
                <select id="branch_id" v-model="form.branch_id" class="field-control" :disabled="Boolean(department)">
                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                        {{ branch.name }}
                    </option>
                </select>
                <p v-if="form.errors.branch_id" class="text-sm text-destructive">{{ form.errors.branch_id }}</p>
            </div>
            <div class="space-y-2">
                <Label for="manager_id">{{ trans('departments.manager') }}</Label>
                <select id="manager_id" v-model="form.manager_id" class="field-control">
                    <option value="">{{ trans('common.none') }}</option>
                    <option v-for="manager in managers" :key="manager.id" :value="manager.id">
                        {{ manager.name }}
                    </option>
                </select>
                <p class="text-sm text-muted-foreground">{{ trans('departments.manager_help') }}</p>
                <p v-if="form.errors.manager_id" class="text-sm text-destructive">{{ form.errors.manager_id }}</p>
            </div>
            <Button class="rounded-full" :disabled="form.processing" @click="submit">
                {{ department ? trans('departments.update') : trans('departments.save') }}
            </Button>
        </CardContent>
    </Card>
</template>
