<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Download, Trash2 } from '@lucide/vue';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import { attendanceTone } from '@/lib/status';

type PersonRow = {
    id: number;
    name: string;
    check_in: string | null;
    check_out: string | null;
    work_hours: string | number | null;
    status: string | null;
};

type AttendanceRow = {
    id: number;
    date: string;
    check_in: string | null;
    check_out: string | null;
    status: string;
    late_minutes: number;
    early_leave_minutes: number;
    work_hours: string | number;
    user?: { id: number; name: string };
    branch?: { id: number; name: string };
};

const props = defineProps<{
    date: string;
    from: string;
    to: string;
    canRecord: boolean;
    people: PersonRow[];
    attendances: {
        data: AttendanceRow[];
    };
}>();

const form = useForm({
    date: props.date,
    entries: props.people.map((person) => ({
        user_id: person.id,
        check_in: person.check_in ?? '',
        check_out: person.check_out ?? '',
    })),
});

const firstError = computed(() => Object.values(form.errors)[0] ?? '');
const exportFrom = ref(props.from);
const exportTo = ref(props.to);
const historyFrom = ref(props.from);
const historyTo = ref(props.to);
const exportUrl = computed(() => `/attendance/export?from=${exportFrom.value}&to=${exportTo.value}`);
const filledCount = computed(
    () => form.entries.filter((entry) => entry.check_in || entry.check_out).length,
);

watch(
    () => [props.from, props.to] as const,
    ([from, to]) => {
        exportFrom.value = from;
        exportTo.value = to;
        historyFrom.value = from;
        historyTo.value = to;
    },
);

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.attendance', href: '/attendance' },
        ],
    },
});

function changeDate(value: string): void {
    router.get('/attendance', { date: value || undefined }, { replace: true });
}

function applyHistoryRange(): void {
    router.get(
        '/attendance',
        {
            from: historyFrom.value || undefined,
            to: historyTo.value || undefined,
        },
        { replace: true },
    );
}

function saveTimes(): void {
    form.date = props.date;
    form.transform((data) => ({
        date: props.date,
        entries: data.entries.map((entry) => ({
            user_id: entry.user_id,
            check_in: entry.check_in || null,
            check_out: entry.check_out || null,
        })),
    }));
    form.put('/attendance/entries', { preserveScroll: true });
}

function clearDay(): void {
    if (!confirm(trans('attendance.clear_confirm_day', { date: dayLabel(props.date) }))) {
        return;
    }

    router.delete('/attendance/records', {
        data: { date: props.date },
        preserveScroll: true,
    });
}

function clearRange(): void {
    if (
        !confirm(
            trans('attendance.clear_confirm_range', {
                from: dayLabel(exportFrom.value),
                to: dayLabel(exportTo.value),
            }),
        )
    ) {
        return;
    }

    router.delete('/attendance/records', {
        data: {
            from: exportFrom.value,
            to: exportTo.value,
        },
        preserveScroll: true,
    });
}

function dayLabel(value: string): string {
    return value.slice(0, 10);
}

function clock(value: string | null): string {
    if (!value) {
        return '—';
    }

    const parsed = new Date(value);

    if (Number.isNaN(parsed.getTime())) {
        return value;
    }

    return parsed.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}
</script>

<template>
    <Head :title="trans('attendance.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('attendance.eyebrow')"
            :title="trans('attendance.title')"
            :description="
                canRecord
                    ? trans('attendance.description_manager')
                    : trans('attendance.description_self')
            "
        />

        <Card class="shadow-sm">
            <CardContent class="flex flex-col gap-4 pt-5 sm:gap-5 sm:pt-6 lg:flex-row lg:items-end lg:justify-between">
                <div v-if="canRecord" class="w-full space-y-2 lg:w-auto">
                    <p class="text-xs font-semibold tracking-[0.16em] text-muted-foreground uppercase">
                        {{ trans('attendance.timesheet') }}
                    </p>
                    <div class="space-y-1">
                        <Label for="date">{{ trans('common.day') }}</Label>
                        <Input
                            id="date"
                            type="date"
                            class="w-full min-w-0 sm:w-44"
                            :model-value="date"
                            @update:model-value="changeDate(String($event))"
                        />
                    </div>
                </div>
                <div v-else class="w-full space-y-2 lg:w-auto">
                    <p class="text-xs font-semibold tracking-[0.16em] text-muted-foreground uppercase">
                        {{ trans('attendance.history_range') }}
                    </p>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
                        <div class="space-y-1">
                            <Label for="history-from">{{ trans('common.from') }}</Label>
                            <Input id="history-from" v-model="historyFrom" type="date" class="w-full min-w-0 sm:w-44" />
                        </div>
                        <div class="space-y-1">
                            <Label for="history-to">{{ trans('common.to') }}</Label>
                            <Input id="history-to" v-model="historyTo" type="date" class="w-full min-w-0 sm:w-44" />
                        </div>
                        <Button class="w-full rounded-full sm:col-span-2 sm:w-auto" @click="applyHistoryRange">
                            {{ trans('attendance.show_table') }}
                        </Button>
                    </div>
                </div>
                <div class="w-full space-y-2 lg:w-auto">
                    <p class="text-xs font-semibold tracking-[0.16em] text-muted-foreground uppercase">
                        {{ trans('attendance.export') }}
                    </p>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
                        <div class="space-y-1">
                            <Label for="from">{{ trans('common.from') }}</Label>
                            <Input id="from" v-model="exportFrom" type="date" class="w-full min-w-0 sm:w-44" />
                        </div>
                        <div class="space-y-1">
                            <Label for="to">{{ trans('common.to') }}</Label>
                            <Input id="to" v-model="exportTo" type="date" class="w-full min-w-0 sm:w-44" />
                        </div>
                        <Button variant="outline" class="w-full rounded-full sm:col-span-2 sm:w-auto" as-child>
                            <a :href="exportUrl">
                                <Download class="size-4" />
                                {{ trans('attendance.download') }}
                            </a>
                        </Button>
                        <Button
                            v-if="canRecord"
                            variant="destructive"
                            class="w-full rounded-full sm:col-span-2 sm:w-auto"
                            @click="clearRange"
                        >
                            <Trash2 class="size-4" />
                            {{ trans('attendance.clear_range') }}
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>

        <Card v-if="canRecord" class="shadow-sm">
            <CardHeader class="border-b">
                <CardTitle>{{ trans('attendance.daily_times') }}</CardTitle>
                <p class="text-sm text-muted-foreground">
                    {{ trans('attendance.people_day', { count: people.length, date: dayLabel(date) }) }}
                </p>
            </CardHeader>
            <CardContent class="pt-4 sm:pt-6">
                <p v-if="firstError" class="pb-4 text-sm text-destructive">{{ firstError }}</p>
                <div
                    v-if="people.length === 0"
                    class="rounded-2xl border border-dashed p-6 text-center text-sm text-muted-foreground sm:p-8"
                >
                    {{ trans('attendance.no_staff_day') }}
                </div>
                <div v-else class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="(entry, index) in form.entries"
                        :key="entry.user_id"
                        class="rounded-2xl border bg-muted/20 p-4"
                    >
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <p class="min-w-0 text-sm font-medium leading-5">{{ people[index]?.name }}</p>
                            <StatusBadge
                                v-if="people[index]?.status"
                                :value="people[index].status"
                                :tone="attendanceTone(people[index].status)"
                            />
                            <span v-else class="text-xs text-muted-foreground">—</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="space-y-1">
                                <Label :for="`in-${entry.user_id}`" class="text-xs">{{ trans('common.in') }}</Label>
                                <Input
                                    :id="`in-${entry.user_id}`"
                                    v-model="entry.check_in"
                                    type="time"
                                    class="tabular-nums"
                                />
                            </div>
                            <div class="space-y-1">
                                <Label :for="`out-${entry.user_id}`" class="text-xs">{{ trans('common.out') }}</Label>
                                <Input
                                    :id="`out-${entry.user_id}`"
                                    v-model="entry.check_out"
                                    type="time"
                                    class="tabular-nums"
                                />
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-muted-foreground">
                            {{ trans('attendance.hours_label', { hours: people[index]?.work_hours ?? '—' }) }}
                        </p>
                    </div>
                </div>
            </CardContent>
            <CardFooter
                v-if="people.length > 0"
                class="flex-col-reverse gap-3 border-t bg-muted/30 sm:flex-row sm:justify-between"
            >
                <p class="text-center text-sm text-muted-foreground sm:text-start">
                    {{ trans('attendance.filled', { filled: filledCount, total: people.length }) }}
                </p>
                <div class="flex w-full flex-col-reverse gap-2 sm:w-auto sm:flex-row">
                    <Button
                        variant="destructive"
                        class="w-full rounded-full sm:w-auto"
                        @click="clearDay"
                    >
                        <Trash2 class="size-4" />
                        {{ trans('attendance.clear_all') }}
                    </Button>
                    <Button class="w-full rounded-full sm:w-auto" :disabled="form.processing" @click="saveTimes">
                        {{ trans('attendance.save_times') }}
                    </Button>
                    <Button variant="outline" class="w-full rounded-full sm:w-auto" as-child>
                        <a :href="`/attendance/export?date=${date}`">
                            <Download class="size-4" />
                            {{ trans('attendance.download') }}
                        </a>
                    </Button>
                </div>
            </CardFooter>
        </Card>

        <Card v-else class="shadow-sm">
            <CardHeader class="border-b">
                <CardTitle>{{ trans('attendance.your_records') }}</CardTitle>
                <p class="text-sm text-muted-foreground">
                    {{
                        trans('attendance.range_label', {
                            from: dayLabel(from),
                            to: dayLabel(to),
                        })
                    }}
                </p>
            </CardHeader>
            <CardContent class="pt-4 sm:pt-6">
                <div
                    v-if="attendances.data.length === 0"
                    class="rounded-2xl border border-dashed p-6 text-center text-sm text-muted-foreground sm:p-8"
                >
                    {{ trans('attendance.empty_range') }}
                </div>
                <div v-else class="overflow-x-auto rounded-2xl border">
                    <table class="w-full min-w-[36rem] text-start text-sm">
                        <thead class="bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase">
                            <tr>
                                <th class="px-4 py-3 font-semibold">{{ trans('common.date') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ trans('common.branch') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ trans('common.in') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ trans('common.out') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ trans('common.hours') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ trans('common.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in attendances.data"
                                :key="row.id"
                                class="border-t"
                            >
                                <td class="px-4 py-3 font-medium tabular-nums">{{ dayLabel(row.date) }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ row.branch?.name ?? '—' }}</td>
                                <td class="px-4 py-3 tabular-nums">{{ clock(row.check_in) }}</td>
                                <td class="px-4 py-3 tabular-nums">{{ clock(row.check_out) }}</td>
                                <td class="px-4 py-3 tabular-nums">{{ row.work_hours ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <StatusBadge :value="row.status" :tone="attendanceTone(row.status)" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
