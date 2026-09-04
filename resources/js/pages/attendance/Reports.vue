<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Mark =
    | 'present'
    | 'late'
    | 'absent'
    | 'leave'
    | 'permission'
    | 'off'
    | 'upcoming';

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
    marks: Record<string, Mark>;
};

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

const marks: Mark[] = [
    'present',
    'late',
    'absent',
    'leave',
    'permission',
];

const weekdayFormatter = computed(
    () => new Intl.DateTimeFormat(undefined, { weekday: 'short' }),
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

function markLabel(mark: Mark): string {
    return trans(`reports.mark.${mark}`);
}

function markClass(mark: Mark): string {
    switch (mark) {
        case 'present':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300';
        case 'late':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300';
        case 'absent':
            return 'bg-rose-100 text-rose-800 dark:bg-rose-400/15 dark:text-rose-300';
        case 'leave':
            return 'bg-sky-100 text-sky-800 dark:bg-sky-400/15 dark:text-sky-300';
        case 'permission':
            return 'bg-violet-100 text-violet-800 dark:bg-violet-400/15 dark:text-violet-300';
        case 'upcoming':
            return 'text-muted-foreground/50';
        default:
            return 'text-muted-foreground/40';
    }
}

function rateClass(rate: number): string {
    if (rate >= 90) {
        return 'text-emerald-700 dark:text-emerald-300';
    }

    if (rate >= 75) {
        return 'text-amber-700 dark:text-amber-300';
    }

    return 'text-rose-700 dark:text-rose-300';
}

function weekday(date: string): string {
    return weekdayFormatter.value.format(new Date(`${date}T00:00:00`));
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

        <div class="flex flex-wrap gap-2 text-xs">
            <span
                v-for="mark in marks"
                :key="mark"
                :class="[
                    'inline-flex items-center rounded-full px-2.5 py-1 font-medium',
                    markClass(mark),
                ]"
            >
                {{ markLabel(mark) }}
            </span>
        </div>

        <div
            v-if="people.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('reports.empty') }}
        </div>
        <div
            v-else-if="dates.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('reports.no_days') }}
        </div>
        <div
            v-else
            class="overflow-auto rounded-2xl border bg-card shadow-sm"
        >
            <table class="min-w-max border-collapse text-center text-sm">
                <thead>
                    <tr class="border-b">
                        <th
                            class="sticky start-0 top-0 z-30 min-w-28 bg-card px-3 py-3 text-start text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                        >
                            {{ trans('common.date') }}
                        </th>
                        <th
                            v-for="person in people"
                            :key="person.id"
                            class="sticky top-0 z-20 min-w-24 bg-card px-2 py-3"
                        >
                            <Link
                                :href="`/staff/${person.id}`"
                                class="block max-w-24 truncate text-xs font-semibold hover:underline"
                            >
                                {{ person.name }}
                            </Link>
                            <p
                                :class="[
                                    'mt-1 text-lg font-semibold tabular-nums',
                                    rateClass(person.attendance_rate),
                                ]"
                            >
                                {{ person.attendance_rate }}%
                            </p>
                            <p class="text-[11px] text-muted-foreground">
                                {{ person.present_days }}/{{
                                    person.expected_days
                                }}
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="date in dates"
                        :key="date"
                        class="border-t"
                    >
                        <th
                            class="sticky start-0 z-10 bg-card px-3 py-2 text-start font-medium"
                        >
                            <span class="block tabular-nums">{{ date }}</span>
                            <span class="text-[11px] text-muted-foreground">
                                {{ weekday(date) }}
                            </span>
                        </th>
                        <td
                            v-for="person in people"
                            :key="`${person.id}-${date}`"
                            class="px-1 py-1"
                        >
                            <span
                                :class="[
                                    'inline-flex min-w-10 items-center justify-center rounded-md px-1.5 py-1 text-[11px] font-semibold',
                                    markClass(person.marks[date] ?? 'off'),
                                ]"
                            >
                                {{ markLabel(person.marks[date] ?? 'off') }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
