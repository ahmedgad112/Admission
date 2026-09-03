<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type BranchOption = { id: number; name: string };
type Day = {
    id: number;
    branch_id: number;
    date: string;
};

const props = defineProps<{
    branches: BranchOption[];
    defaultBranchId?: number | null;
    day?: Day;
}>();

function localIsoDate(): string {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

const form = useForm({
    branch_id:
        props.day?.branch_id ??
        props.defaultBranchId ??
        props.branches[0]?.id ??
        '',
    date: props.day?.date ?? localIsoDate(),
});

const canSubmit = computed(() => Boolean(form.branch_id && form.date));

function submit(): void {
    if (props.day) {
        form.put(`/attendance/days/${props.day.id}`);

        return;
    }

    form.post('/attendance/days');
}
</script>

<template>
    <Card class="mx-auto w-full max-w-3xl shadow-sm">
        <CardContent class="space-y-6 pt-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <Label for="branch_id">{{ trans('common.branch') }}</Label>
                    <select
                        id="branch_id"
                        v-model="form.branch_id"
                        class="field-control"
                    >
                        <option
                            v-for="branch in branches"
                            :key="branch.id"
                            :value="branch.id"
                        >
                            {{ branch.name }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.branch_id"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.branch_id }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="date">{{ trans('common.date') }}</Label>
                    <Input id="date" v-model="form.date" type="date" />
                    <p v-if="form.errors.date" class="text-sm text-destructive">
                        {{ form.errors.date }}
                    </p>
                </div>
            </div>

            <Button
                class="rounded-full"
                :disabled="form.processing || !canSubmit"
                @click="submit"
            >
                {{ day ? trans('roster.update') : trans('roster.save') }}
            </Button>
        </CardContent>
    </Card>
</template>
