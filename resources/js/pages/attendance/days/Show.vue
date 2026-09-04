<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Download, Trash2 } from '@lucide/vue';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { trans } from '@/composables/useTrans';
import { attendanceTone } from '@/lib/status';

type AttendanceRow = {
    id: number;
    user_id: number | null;
    name: string | null;
    department?: { id: number; name: string } | null;
    check_in: string | null;
    check_out: string | null;
    status: string | null;
    work_hours: string | number | null;
};

type Day = {
    id: number;
    date: string;
    check_in_starts_at: string;
    check_in_ends_at: string;
    check_out_starts_at: string;
    check_out_ends_at: string;
    check_in_is_open: boolean;
    check_out_is_open: boolean;
    branch?: { id: number; name: string } | null;
    creator?: { id: number; name: string } | null;
    attendances: AttendanceRow[];
};

const props = defineProps<{
    day: Day;
    canUpdate: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.roster', href: '/attendance/days' },
            { title: 'common.view', href: '/attendance/days' },
        ],
    },
});

function destroy(): void {
    router.delete(`/attendance/days/${props.day.id}`);
}

function destroyAttendance(record: AttendanceRow): void {
    if (record.user_id === null) {
        return;
    }

    if (
        !confirm(
            trans('attendance.clear_confirm_person', {
                name: record.name ?? '',
                date: props.day.date,
            }),
        )
    ) {
        return;
    }

    router.delete('/attendance/records', {
        data: { date: props.day.date, user_id: record.user_id },
        preserveScroll: true,
    });
}

function windowLabel(start: string, end: string): string {
    return `${start} – ${end}`;
}
</script>

<template>
    <Head :title="trans('roster.view_title', { date: day.date })" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('roster.eyebrow')"
            :title="trans('roster.view_title', { date: day.date })"
            :description="day.branch?.name ?? trans('roster.title')"
        >
            <template #actions>
                <Button variant="outline" class="rounded-full" as-child>
                    <a :href="`/attendance/days/${day.id}/export`">
                        <Download class="size-4" />
                        {{ trans('attendance.download') }}
                    </a>
                </Button>
                <Button variant="outline" class="rounded-full" as-child>
                    <Link href="/attendance/days">{{
                        trans('common.back')
                    }}</Link>
                </Button>
                <Button v-if="canUpdate" class="rounded-full" as-child>
                    <Link :href="`/attendance/days/${day.id}/edit`">{{
                        trans('common.edit')
                    }}</Link>
                </Button>
                <Button
                    v-if="canUpdate"
                    variant="destructive"
                    class="rounded-full"
                    @click="destroy"
                >
                    {{ trans('common.delete') }}
                </Button>
            </template>
        </PageHeader>

        <div class="grid gap-4 xl:grid-cols-[1.2fr_1fr]">
            <Card class="shadow-sm">
                <CardHeader class="border-b">
                    <CardTitle>{{
                        trans('roster.todays_attendance')
                    }}</CardTitle>
                    <CardDescription>
                        {{ trans('common.staff') }} ·
                        {{ day.attendances.length }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="pt-4 sm:pt-6">
                    <div
                        v-if="day.attendances.length === 0"
                        class="rounded-2xl border border-dashed p-6 text-center text-sm text-muted-foreground"
                    >
                        {{ trans('roster.empty_attendance') }}
                    </div>
                    <div v-else>
                        <div class="grid gap-3 md:hidden">
                            <div
                                v-for="record in day.attendances"
                                :key="record.user_id ?? record.id"
                                class="rounded-2xl border bg-muted/20 p-4"
                            >
                                <div
                                    class="mb-3 flex items-start justify-between gap-2"
                                >
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium">
                                            {{ record.name ?? '—' }}
                                        </p>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{
                                                record.department?.name ??
                                                trans('common.no_department')
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="flex shrink-0 items-center gap-1"
                                    >
                                        <StatusBadge
                                            v-if="record.status"
                                            :value="record.status"
                                            :tone="
                                                attendanceTone(record.status)
                                            "
                                        />
                                        <span
                                            v-else
                                            class="text-xs text-muted-foreground"
                                            >—</span
                                        >
                                        <Button
                                            v-if="canUpdate && record.user_id"
                                            variant="ghost"
                                            size="sm"
                                            class="size-8 rounded-full text-destructive hover:bg-destructive/10 hover:text-destructive"
                                            :aria-label="
                                                trans('attendance.clear_person')
                                            "
                                            @click="destroyAttendance(record)"
                                        >
                                            <Trash2 class="size-4" />
                                        </Button>
                                    </div>
                                </div>
                                <dl
                                    class="grid grid-cols-2 gap-x-3 gap-y-3 text-sm"
                                >
                                    <div>
                                        <dt
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ trans('common.in') }}
                                        </dt>
                                        <dd class="font-medium tabular-nums">
                                            {{ record.check_in ?? '—' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ trans('common.out') }}
                                        </dt>
                                        <dd class="font-medium tabular-nums">
                                            {{ record.check_out ?? '—' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        <div
                            class="hidden overflow-x-auto rounded-2xl border md:block"
                        >
                            <table
                                class="w-full min-w-[28rem] text-start text-sm"
                            >
                                <thead
                                    class="bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase"
                                >
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">
                                            {{ trans('common.name') }}
                                        </th>
                                        <th class="px-4 py-3 font-semibold">
                                            {{ trans('common.in') }}
                                        </th>
                                        <th class="px-4 py-3 font-semibold">
                                            {{ trans('common.out') }}
                                        </th>
                                        <th class="px-4 py-3 font-semibold">
                                            {{ trans('common.status') }}
                                        </th>
                                        <th
                                            v-if="canUpdate"
                                            class="px-4 py-3 font-semibold"
                                        >
                                            {{ trans('common.action') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="record in day.attendances"
                                        :key="record.user_id ?? record.id"
                                        class="border-t"
                                    >
                                        <td class="px-4 py-3 font-medium">
                                            {{ record.name ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 tabular-nums">
                                            {{ record.check_in ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 tabular-nums">
                                            {{ record.check_out ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <StatusBadge
                                                v-if="record.status"
                                                :value="record.status"
                                                :tone="
                                                    attendanceTone(
                                                        record.status,
                                                    )
                                                "
                                            />
                                            <span v-else>—</span>
                                        </td>
                                        <td v-if="canUpdate" class="px-4 py-3">
                                            <Button
                                                v-if="record.user_id"
                                                variant="ghost"
                                                size="sm"
                                                class="rounded-full text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                @click="
                                                    destroyAttendance(record)
                                                "
                                            >
                                                <Trash2 class="size-4" />
                                                {{ trans('common.delete') }}
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="shadow-sm">
                <CardHeader class="border-b">
                    <CardTitle>{{ trans('roster.windows') }}</CardTitle>
                    <CardDescription v-if="day.creator">
                        {{ day.creator.name }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-4 pt-4 sm:pt-6">
                    <div class="rounded-2xl border bg-muted/20 p-4">
                        <p
                            class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                        >
                            {{ trans('roster.check_in') }}
                        </p>
                        <p class="mt-1 text-sm font-medium tabular-nums">
                            {{
                                windowLabel(
                                    day.check_in_starts_at,
                                    day.check_in_ends_at,
                                )
                            }}
                        </p>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{
                                day.check_in_is_open
                                    ? trans('roster.session_open')
                                    : trans('roster.session_closed')
                            }}
                        </p>
                    </div>
                    <div class="rounded-2xl border bg-muted/20 p-4">
                        <p
                            class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                        >
                            {{ trans('roster.check_out') }}
                        </p>
                        <p class="mt-1 text-sm font-medium tabular-nums">
                            {{
                                windowLabel(
                                    day.check_out_starts_at,
                                    day.check_out_ends_at,
                                )
                            }}
                        </p>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{
                                day.check_out_is_open
                                    ? trans('roster.session_open')
                                    : trans('roster.session_closed')
                            }}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
