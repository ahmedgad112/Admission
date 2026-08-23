<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { CheckCircle2, Clock3, Timer, Workflow } from '@lucide/vue';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { trans } from '@/composables/useTrans';
import { attendanceTone } from '@/lib/status';

type AttendanceRow = {
    id: number;
    status: string;
    check_in: string | null;
    late_minutes: number;
    work_hours: string | number;
    user?: { id: number; name: string };
    branch?: { id: number; name: string };
};

defineProps<{
    metrics: {
        headcount: number;
        present_today: number;
        late_today: number;
        attendance_rate: number;
        late_this_month: number;
        average_work_hours: number;
        total_tasks: number;
        completed_tasks: number;
        task_completion_rate: number;
        today_attendance: AttendanceRow[];
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'nav.dashboard',
                href: '/dashboard',
            },
        ],
    },
});

const page = usePage();
</script>

<template>
    <Head :title="trans('nav.dashboard')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('dashboard.eyebrow')"
            :title="trans('dashboard.greeting', { name: page.props.auth.user.name.split(' ')[0] })"
            :description="trans('dashboard.description')"
        >
            <template #actions>
                <Link
                    href="/attendance/scan"
                    class="inline-flex h-10 items-center rounded-full bg-primary px-4 text-sm font-medium text-primary-foreground"
                >
                    {{ trans('dashboard.scan_now') }}
                </Link>
            </template>
        </PageHeader>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <Card class="overflow-hidden border-0 bg-gradient-to-br from-primary to-[hsl(174_48%_24%)] text-primary-foreground shadow-lg">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-white/75">{{ trans('dashboard.attendance_rate') }}</p>
                        <CheckCircle2 class="size-5 text-white/80" />
                    </div>
                    <CardTitle class="text-4xl text-white">
                        {{ metrics.attendance_rate }}%
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-sm text-white/70">
                    {{ trans('dashboard.present_of_alt', { present: metrics.present_today, total: metrics.headcount }) }}
                </CardContent>
            </Card>
            <Card class="shadow-sm">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">{{ trans('dashboard.late_today') }}</p>
                        <Clock3 class="size-5 text-amber-600" />
                    </div>
                    <CardTitle class="text-4xl">{{ metrics.late_today }}</CardTitle>
                </CardHeader>
                <CardContent class="text-sm text-muted-foreground">
                    {{ trans('dashboard.late_month', { count: metrics.late_this_month }) }}
                </CardContent>
            </Card>
            <Card class="shadow-sm">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">{{ trans('dashboard.average_hours') }}</p>
                        <Timer class="size-5 text-primary" />
                    </div>
                    <CardTitle class="text-4xl">{{ metrics.average_work_hours }}</CardTitle>
                </CardHeader>
                <CardContent class="text-sm text-muted-foreground">
                    {{ trans('dashboard.completed_shifts') }}
                </CardContent>
            </Card>
            <Card class="shadow-sm">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">{{ trans('dashboard.task_completion') }}</p>
                        <Workflow class="size-5 text-primary" />
                    </div>
                    <CardTitle class="text-4xl">{{ metrics.task_completion_rate }}%</CardTitle>
                </CardHeader>
                <CardContent class="text-sm text-muted-foreground">
                    {{ trans('dashboard.tasks_completed', { completed: metrics.completed_tasks, total: metrics.total_tasks }) }}
                </CardContent>
            </Card>
        </div>

        <Card class="shadow-sm">
            <CardHeader class="flex flex-row items-center justify-between">
                <div>
                    <CardTitle>{{ trans('dashboard.floor') }}</CardTitle>
                    <p class="text-sm text-muted-foreground">
                        {{ trans('dashboard.floor_hint') }}
                    </p>
                </div>
                <Link href="/attendance" class="text-sm font-medium text-primary">
                    {{ trans('dashboard.view_records') }}
                </Link>
            </CardHeader>
            <CardContent>
                <div
                    v-if="metrics.today_attendance.length === 0"
                    class="rounded-2xl border border-dashed p-8 text-center text-sm text-muted-foreground"
                >
                    {{ trans('dashboard.empty') }}
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-start text-muted-foreground">
                            <tr>
                                <th class="pb-3 font-medium">{{ trans('common.employee') }}</th>
                                <th class="pb-3 font-medium">{{ trans('common.branch') }}</th>
                                <th class="pb-3 font-medium">{{ trans('dashboard.check_in') }}</th>
                                <th class="pb-3 font-medium">{{ trans('common.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in metrics.today_attendance"
                                :key="row.id"
                                class="border-t border-border/70"
                            >
                                <td class="py-3.5 font-medium">{{ row.user?.name }}</td>
                                <td class="py-3.5">{{ row.branch?.name }}</td>
                                <td class="py-3.5">
                                    {{
                                        row.check_in
                                            ? new Date(row.check_in).toLocaleTimeString()
                                            : '—'
                                    }}
                                </td>
                                <td class="py-3.5">
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
