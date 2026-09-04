<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Person = {
    id: number;
    name: string;
    branch?: { id: number; name: string } | null;
    department?: { id: number; name: string } | null;
    expected_days: number;
    present_days: number;
    absent_days: number;
    late_days: number;
    permission_days: number;
    leave_days: number;
    attendance_rate: number;
};

type SegmentKey = 'present' | 'late' | 'absent' | 'leave' | 'permission';

const props = defineProps<{
    month: string;
    dates: string[];
    people: Person[];
    filters: { month: string; branch_id: number | null };
    branches: { id: number; name: string }[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.attendance', href: '/attendance' },
            { title: 'nav.reports', href: '/attendance/reports' },
        ],
    },
});

const segments: Array<{
    key: SegmentKey;
    label: string;
    barClass: string;
}> = [
    {
        key: 'present',
        label: 'status.present',
        barClass: 'bg-emerald-500 dark:bg-emerald-400',
    },
    {
        key: 'late',
        label: 'status.late',
        barClass: 'bg-amber-500 dark:bg-amber-400',
    },
    {
        key: 'absent',
        label: 'status.absent',
        barClass: 'bg-rose-500 dark:bg-rose-400',
    },
    {
        key: 'leave',
        label: 'staff.leave_days',
        barClass: 'bg-sky-500 dark:bg-sky-400',
    },
    {
        key: 'permission',
        label: 'staff.permission_days',
        barClass: 'bg-violet-500 dark:bg-violet-400',
    },
];

const averageRate = computed(() => {
    if (props.people.length === 0) {
        return 0;
    }

    const total = props.people.reduce(
        (sum, person) => sum + person.attendance_rate,
        0,
    );

    return Math.round((total / props.people.length) * 10) / 10;
});

const chartPeople = computed(() =>
    props.people.map((person) => {
        const present = Math.max(person.present_days - person.late_days, 0);
        const parts = {
            present,
            late: person.late_days,
            absent: person.absent_days,
            leave: person.leave_days,
            permission: person.permission_days,
        };
        const total = Object.values(parts).reduce(
            (sum, value) => sum + value,
            0,
        );

        return {
            ...person,
            parts,
            total,
        };
    }),
);

function filter(key: 'month' | 'branch_id', value: string): void {
    router.get(
        '/attendance/reports',
        {
            month:
                key === 'month'
                    ? value || undefined
                    : props.filters.month || undefined,
            branch_id:
                key === 'branch_id'
                    ? value || undefined
                    : props.filters.branch_id || undefined,
        },
        { preserveState: true, replace: true },
    );
}

function rateClass(rate: number): string {
    if (rate >= 90) {
        return 'bg-emerald-500 dark:bg-emerald-400';
    }

    if (rate >= 75) {
        return 'bg-amber-500 dark:bg-amber-400';
    }

    return 'bg-rose-500 dark:bg-rose-400';
}

function rateTextClass(rate: number): string {
    if (rate >= 90) {
        return 'text-emerald-700 dark:text-emerald-300';
    }

    if (rate >= 75) {
        return 'text-amber-700 dark:text-amber-300';
    }

    return 'text-rose-700 dark:text-rose-300';
}

function barHeight(rate: number): string {
    return `${Math.max(rate, 0)}%`;
}

function segmentHeight(value: number, total: number): string {
    if (total <= 0) {
        return '0%';
    }

    return `${(value / total) * 100}%`;
}

function firstName(name: string): string {
    return name.trim().split(/\s+/)[0] ?? name;
}
</script>

<template>
    <Head :title="trans('reports.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('reports.eyebrow')"
            :title="trans('reports.title')"
            :description="trans('reports.description')"
        />

        <div class="flex flex-wrap items-end gap-4">
            <div class="space-y-1">
                <Label for="month">{{ trans('staff.month') }}</Label>
                <Input
                    id="month"
                    type="month"
                    class="w-44"
                    :model-value="filters.month"
                    @update:model-value="filter('month', String($event))"
                />
            </div>
            <div v-if="branches.length > 0" class="space-y-1">
                <Label for="branch">{{ trans('common.branch') }}</Label>
                <select
                    id="branch"
                    class="field-control w-52"
                    :value="filters.branch_id ?? ''"
                    @change="
                        filter(
                            'branch_id',
                            ($event.target as HTMLSelectElement).value,
                        )
                    "
                >
                    <option value="">
                        {{ trans('reports.all_branches') }}
                    </option>
                    <option
                        v-for="branch in branches"
                        :key="branch.id"
                        :value="branch.id"
                    >
                        {{ branch.name }}
                    </option>
                </select>
            </div>
        </div>

        <div
            v-if="people.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('reports.empty') }}
        </div>

        <template v-else>
            <dl class="grid gap-3 sm:grid-cols-3">
                <Card class="shadow-sm">
                    <CardHeader class="pb-2">
                        <CardDescription>{{
                            trans('reports.average_rate')
                        }}</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">
                            {{ averageRate }}%
                        </CardTitle>
                    </CardHeader>
                </Card>
                <Card class="shadow-sm">
                    <CardHeader class="pb-2">
                        <CardDescription>{{
                            trans('common.staff')
                        }}</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">
                            {{ people.length }}
                        </CardTitle>
                    </CardHeader>
                </Card>
                <Card class="shadow-sm">
                    <CardHeader class="pb-2">
                        <CardDescription>{{
                            trans('staff.month')
                        }}</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">
                            {{ filters.month }}
                        </CardTitle>
                    </CardHeader>
                </Card>
            </dl>

            <Card class="shadow-sm">
                <CardHeader>
                    <CardTitle>{{ trans('reports.rate_chart') }}</CardTitle>
                    <CardDescription>{{
                        trans('reports.rate_chart_help')
                    }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex h-72 gap-3">
                        <div
                            class="flex h-full shrink-0 flex-col text-xs text-muted-foreground tabular-nums"
                        >
                            <div class="h-6 shrink-0" />
                            <div
                                class="flex flex-1 flex-col justify-between py-0.5"
                            >
                                <span>100%</span>
                                <span>75%</span>
                                <span>50%</span>
                                <span>25%</span>
                                <span>0%</span>
                            </div>
                            <div class="h-6 shrink-0" />
                        </div>
                        <div class="min-w-0 flex-1 overflow-x-auto">
                            <div
                                class="flex h-full min-w-full gap-3 border-b"
                                :style="{
                                    minWidth: `${Math.max(chartPeople.length * 4.5, 20)}rem`,
                                }"
                            >
                                <Link
                                    v-for="person in chartPeople"
                                    :key="person.id"
                                    :href="`/staff/${person.id}`"
                                    class="group flex h-full min-w-16 flex-1 flex-col items-center gap-2 pt-1"
                                >
                                    <span
                                        :class="[
                                            'text-xs font-semibold tabular-nums',
                                            rateTextClass(
                                                person.attendance_rate,
                                            ),
                                        ]"
                                    >
                                        {{ person.attendance_rate }}%
                                    </span>
                                    <div
                                        class="flex w-10 max-w-full flex-1 items-end"
                                    >
                                        <div
                                            class="w-full rounded-t-lg transition-all duration-300 group-hover:brightness-110"
                                            :class="
                                                rateClass(
                                                    person.attendance_rate,
                                                )
                                            "
                                            :style="{
                                                height: barHeight(
                                                    person.attendance_rate,
                                                ),
                                            }"
                                        />
                                    </div>
                                    <span
                                        class="w-full truncate text-center text-[11px] font-medium text-muted-foreground group-hover:text-foreground"
                                        :title="person.name"
                                    >
                                        {{ firstName(person.name) }}
                                    </span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="shadow-sm">
                <CardHeader>
                    <CardTitle>{{
                        trans('reports.breakdown_chart')
                    }}</CardTitle>
                    <CardDescription>{{
                        trans('reports.breakdown_chart_help')
                    }}</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <p
                        v-if="dates.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        {{ trans('reports.no_days') }}
                    </p>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span
                            v-for="segment in segments"
                            :key="segment.key"
                            class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 font-medium"
                        >
                            <span
                                class="size-2.5 rounded-full"
                                :class="segment.barClass"
                            />
                            {{ trans(segment.label) }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <div
                            class="flex h-64 min-w-full items-end gap-3 border-b"
                            :style="{
                                minWidth: `${Math.max(chartPeople.length * 4.5, 20)}rem`,
                            }"
                        >
                            <Link
                                v-for="person in chartPeople"
                                :key="person.id"
                                :href="`/staff/${person.id}`"
                                class="group flex h-full min-w-16 flex-1 flex-col items-center justify-end gap-2"
                            >
                                <div
                                    class="flex w-10 max-w-full flex-1 flex-col-reverse overflow-hidden rounded-t-lg bg-muted/40"
                                >
                                    <div
                                        v-for="segment in segments"
                                        :key="segment.key"
                                        :class="segment.barClass"
                                        :style="{
                                            height: segmentHeight(
                                                person.parts[segment.key],
                                                person.total,
                                            ),
                                        }"
                                    />
                                </div>
                                <span
                                    class="w-full truncate text-center text-[11px] font-medium text-muted-foreground group-hover:text-foreground"
                                    :title="person.name"
                                >
                                    {{ firstName(person.name) }}
                                </span>
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </template>
    </div>
</template>
