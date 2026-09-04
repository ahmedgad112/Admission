<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import {
    attendanceTone,
    leaveRequestStatusTone,
    userRoleTone,
    userStatusTone,
} from '@/lib/status';

type Member = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    role: string | null;
    role_label: string | null;
    status: string;
    branch?: { id: number; name: string } | null;
    department?: { id: number; name: string } | null;
    shift?: { id: number; name: string } | null;
    leave_days: number;
};

type Summary = {
    month: string;
    present_days: number;
    absent_days: number;
    late_days: number;
    permission_days: number;
    leave_days_used: number;
    remaining_leave_days: number;
    records: Array<{
        id: number;
        date: string;
        check_in: string | null;
        check_out: string | null;
        status: string;
        work_hours: string | number | null;
        late_minutes: number;
    }>;
    leaves: Array<{
        id: number;
        start_date: string;
        end_date: string;
        type: string;
        status: string;
        reason: string;
        days: number;
    }>;
};

const props = defineProps<{
    member: Member;
    summary: Summary;
    canUpdate: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.staff', href: '/staff' },
            { title: 'staff.profile', href: '/staff' },
        ],
    },
});

function changeMonth(value: string): void {
    router.get(
        `/staff/${props.member.id}`,
        { month: value || undefined },
        { preserveState: true, replace: true },
    );
}

function leaveRange(leave: Summary['leaves'][number]): string {
    if (leave.start_date === leave.end_date) {
        return leave.start_date;
    }

    return `${leave.start_date} – ${leave.end_date}`;
}
</script>

<template>
    <Head :title="member.name" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('staff.profile')"
            :title="member.name"
            :description="trans('staff.profile_description')"
        >
            <template #actions>
                <Button variant="outline" class="rounded-full" as-child>
                    <Link href="/staff">{{ trans('common.back') }}</Link>
                </Button>
                <Button v-if="canUpdate" class="rounded-full" as-child>
                    <Link :href="`/staff/${member.id}/edit`">{{
                        trans('common.edit')
                    }}</Link>
                </Button>
            </template>
        </PageHeader>

        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <span
                    v-if="member.role"
                    :class="[
                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
                        userRoleTone(member.role),
                    ]"
                >
                    {{ member.role_label }}
                </span>
                <StatusBadge
                    :value="member.status"
                    :tone="userStatusTone(member.status)"
                />
            </div>
            <div class="space-y-1">
                <Label for="month">{{ trans('staff.month') }}</Label>
                <Input
                    id="month"
                    type="month"
                    class="w-44"
                    :model-value="summary.month"
                    @update:model-value="changeMonth(String($event))"
                />
            </div>
        </div>

        <dl class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <Card class="shadow-sm">
                <CardHeader class="pb-2">
                    <CardDescription>{{
                        trans('staff.present_days')
                    }}</CardDescription>
                    <CardTitle class="text-3xl tabular-nums">{{
                        summary.present_days
                    }}</CardTitle>
                </CardHeader>
            </Card>
            <Card class="shadow-sm">
                <CardHeader class="pb-2">
                    <CardDescription>{{
                        trans('staff.absent_days')
                    }}</CardDescription>
                    <CardTitle class="text-3xl tabular-nums">{{
                        summary.absent_days
                    }}</CardTitle>
                </CardHeader>
            </Card>
            <Card class="shadow-sm">
                <CardHeader class="pb-2">
                    <CardDescription>{{
                        trans('staff.permission_days')
                    }}</CardDescription>
                    <CardTitle class="text-3xl tabular-nums">{{
                        summary.permission_days
                    }}</CardTitle>
                </CardHeader>
            </Card>
            <Card class="shadow-sm">
                <CardHeader class="pb-2">
                    <CardDescription>{{
                        trans('staff.leave_days')
                    }}</CardDescription>
                    <CardTitle class="text-3xl tabular-nums">
                        {{ summary.remaining_leave_days }}
                        <span
                            class="text-sm font-normal text-muted-foreground"
                        >
                            / {{ member.leave_days }}
                        </span>
                    </CardTitle>
                </CardHeader>
            </Card>
        </dl>

        <Card class="shadow-sm">
            <CardHeader class="border-b">
                <CardTitle>{{ trans('staff.attendance_days') }}</CardTitle>
                <CardDescription>
                    {{ trans('staff.late_days') }}:
                    {{ summary.late_days }}
                </CardDescription>
            </CardHeader>
            <CardContent class="pt-4 sm:pt-6">
                <div
                    v-if="summary.records.length === 0"
                    class="rounded-2xl border border-dashed p-8 text-center text-sm text-muted-foreground"
                >
                    {{ trans('staff.no_attendance') }}
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="w-full min-w-[36rem] text-start text-sm">
                        <thead
                            class="bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase"
                        >
                            <tr>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.date') }}
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.in') }}
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.out') }}
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.hours') }}
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="record in summary.records"
                                :key="record.id"
                                class="border-t"
                            >
                                <td class="px-4 py-3 font-medium tabular-nums">
                                    {{ record.date }}
                                </td>
                                <td class="px-4 py-3 tabular-nums">
                                    {{ record.check_in ?? '—' }}
                                </td>
                                <td class="px-4 py-3 tabular-nums">
                                    {{ record.check_out ?? '—' }}
                                </td>
                                <td class="px-4 py-3 tabular-nums">
                                    {{ record.work_hours ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <StatusBadge
                                        :value="record.status"
                                        :tone="attendanceTone(record.status)"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>

        <Card class="shadow-sm">
            <CardHeader class="border-b">
                <CardTitle>{{ trans('staff.leave_history') }}</CardTitle>
            </CardHeader>
            <CardContent class="pt-4 sm:pt-6">
                <div
                    v-if="summary.leaves.length === 0"
                    class="rounded-2xl border border-dashed p-8 text-center text-sm text-muted-foreground"
                >
                    {{ trans('staff.no_leave') }}
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="w-full min-w-[36rem] text-start text-sm">
                        <thead
                            class="bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase"
                        >
                            <tr>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.date') }}
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.type') }}
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.status') }}
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    {{ trans('common.reason') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="leave in summary.leaves"
                                :key="leave.id"
                                class="border-t"
                            >
                                <td class="px-4 py-3 font-medium">
                                    {{ leaveRange(leave) }}
                                    <span
                                        class="block text-xs text-muted-foreground"
                                    >
                                        {{ leave.days }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    {{ trans(`leave.type.${leave.type}`) }}
                                </td>
                                <td class="px-4 py-3">
                                    <StatusBadge
                                        :value="leave.status"
                                        :tone="
                                            leaveRequestStatusTone(leave.status)
                                        "
                                    />
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">
                                    {{ leave.reason }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
