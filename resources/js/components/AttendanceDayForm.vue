<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type BranchOption = { id: number; name: string };
type StaffOption = {
    id: number;
    name: string;
    branch_id: number | null;
    role: string;
    department?: { id: number; name: string } | null;
};
type Day = {
    id: number;
    branch_id: number;
    date: string;
    staff_ids: number[];
};

const props = defineProps<{
    branches: BranchOption[];
    staff: StaffOption[];
    defaultBranchId?: number | null;
    day?: Day;
}>();

const search = ref('');

const form = useForm({
    branch_id: props.day?.branch_id ?? props.defaultBranchId ?? props.branches[0]?.id ?? '',
    date: props.day?.date ?? new Date().toISOString().slice(0, 10),
    staff_ids: [...(props.day?.staff_ids ?? [])],
});

const branchStaff = computed(() =>
    props.staff.filter((member) => Number(member.branch_id) === Number(form.branch_id)),
);

const visibleStaff = computed(() => {
    const query = search.value.trim().toLowerCase();

    return branchStaff.value.filter((member) => {
        if (query === '') {
            return true;
        }

        return (
            member.name.toLowerCase().includes(query) ||
            (member.department?.name ?? '').toLowerCase().includes(query)
        );
    });
});

watch(
    () => form.branch_id,
    () => {
        const allowed = new Set(branchStaff.value.map((member) => member.id));
        form.staff_ids = form.staff_ids.filter((id) => allowed.has(id));
        search.value = '';
    },
);

function isSelected(id: number): boolean {
    return form.staff_ids.includes(id);
}

function toggleStaff(id: number, checked: boolean | 'indeterminate'): void {
    const selected = new Set(form.staff_ids);

    if (checked === true) {
        selected.add(id);
    } else {
        selected.delete(id);
    }

    form.staff_ids = [...selected];
}

function selectVisible(): void {
    const selected = new Set(form.staff_ids);
    visibleStaff.value.forEach((member) => selected.add(member.id));
    form.staff_ids = [...selected];
}

function clearStaff(): void {
    form.staff_ids = [];
}

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
                    <select id="branch_id" v-model="form.branch_id" class="field-control">
                        <option
                            v-for="branch in branches"
                            :key="branch.id"
                            :value="branch.id"
                        >
                            {{ branch.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.branch_id" class="text-sm text-destructive">
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

            <div class="space-y-3">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <Label>{{ trans('roster.staff_on_duty') }}</Label>
                        <p class="text-sm text-muted-foreground">
                            {{ trans('roster.selected_from', { selected: form.staff_ids.length, total: branchStaff.length }) }}
                            ·
                            <Link href="/staff" class="text-primary hover:underline">{{ trans('roster.manage_staff') }}</Link>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <Button type="button" variant="outline" size="sm" class="rounded-full" @click="selectVisible">
                            {{ trans('roster.select_visible') }}
                        </Button>
                        <Button type="button" variant="ghost" size="sm" class="rounded-full" @click="clearStaff">
                            {{ trans('roster.clear') }}
                        </Button>
                    </div>
                </div>
                <Input v-model="search" type="search" :placeholder="trans('roster.search')" />
                <p v-if="form.errors.staff_ids" class="text-sm text-destructive">
                    {{ form.errors.staff_ids }}
                </p>
                <div class="max-h-80 space-y-1 overflow-y-auto rounded-2xl border p-2">
                    <p
                        v-if="visibleStaff.length === 0"
                        class="px-3 py-8 text-center text-sm text-muted-foreground"
                    >
                        {{ trans('roster.no_match') }}
                    </p>
                    <label
                        v-for="member in visibleStaff"
                        :key="member.id"
                        class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-muted/70"
                    >
                        <Checkbox
                            :model-value="isSelected(member.id)"
                            @update:model-value="toggleStaff(member.id, $event)"
                        />
                        <span class="min-w-0 flex-1">
                            <span class="block font-medium">{{ member.name }}</span>
                            <span class="block text-xs text-muted-foreground">
                                {{ member.department?.name ?? trans('common.no_department') }}
                            </span>
                        </span>
                    </label>
                </div>
            </div>

            <Button class="rounded-full" :disabled="form.processing" @click="submit">
                {{ day ? trans('roster.update') : trans('roster.save') }}
            </Button>
        </CardContent>
    </Card>
</template>
