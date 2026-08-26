<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowUpRight,
    Building2,
    CalendarDays,
    CalendarOff,
    CheckCircle2,
    ClipboardList,
    Clock3,
    ScanLine,
    Sparkles,
    Timer,
    Users,
    Workflow,
} from '@lucide/vue';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { trans } from '@/composables/useTrans';
import { getInitials } from '@/composables/useInitials';
import { attendanceTone } from '@/lib/status';

type AttendanceRow = {
    id: number;
    status: string;
    check_in: string | null;
    late_minutes: number;
    work_hours: string | number;
    user?: { id: number; name: string; avatar?: string };
    branch?: { id: number; name: string };
};

const props = defineProps<{
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

function formatTime(checkIn: string | null): string {
    if (!checkIn) return '—';
    const parsed = new Date(checkIn);
    if (Number.isNaN(parsed.getTime())) return checkIn;
    return parsed.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}
</script>

<template>
    <Head :title="trans('nav.dashboard')" />

    <div class="page-shell space-y-6">
        <!-- Dashboard Page Header -->
        <PageHeader
            :eyebrow="trans('dashboard.eyebrow')"
            :title="trans('dashboard.greeting', { name: page.props.auth.user.name.split(' ')[0] })"
            :description="trans('dashboard.description')"
        >
            <template #actions>
                <Link
                    href="/attendance/scan"
                    class="inline-flex h-10 items-center gap-2 rounded-full bg-gradient-to-r from-primary to-[hsl(174_62%_26%)] px-5 text-sm font-semibold text-primary-foreground shadow-md transition-all duration-200 hover:shadow-lg hover:brightness-110 active:scale-95"
                >
                    <ScanLine class="size-4" />
                    <span>{{ trans('dashboard.scan_now') }}</span>
                </Link>
            </template>
        </PageHeader>

        <!-- Quick Actions Shortcuts Bar -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <Link
                href="/attendance/scan"
                class="group flex items-center gap-3 rounded-2xl border border-border/80 bg-card p-3.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-accent/40 hover:shadow-sm"
            >
                <div class="flex size-10 items-center justify-center rounded-xl bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground">
                    <ScanLine class="size-5" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-foreground group-hover:text-primary">
                        {{ trans('dashboard.quick_scan') }}
                    </p>
                    <p class="text-[11px] text-muted-foreground truncate">حضور وإنصراف</p>
                </div>
            </Link>

            <Link
                href="/attendance/days"
                class="group flex items-center gap-3 rounded-2xl border border-border/80 bg-card p-3.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-accent/40 hover:shadow-sm"
            >
                <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                    <CalendarDays class="size-5" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-foreground group-hover:text-emerald-600 dark:group-hover:text-emerald-400">
                        {{ trans('dashboard.create_roster') }}
                    </p>
                    <p class="text-[11px] text-muted-foreground truncate">{{ trans('dashboard.roster_hint') }}</p>
                </div>
            </Link>

            <Link
                href="/leave-requests"
                class="group flex items-center gap-3 rounded-2xl border border-border/80 bg-card p-3.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-accent/40 hover:shadow-sm"
            >
                <div class="flex size-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 transition-colors group-hover:bg-amber-600 group-hover:text-white">
                    <CalendarOff class="size-5" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-foreground group-hover:text-amber-600 dark:group-hover:text-amber-400">
                        {{ trans('dashboard.request_leave') }}
                    </p>
                    <p class="text-[11px] text-muted-foreground truncate">تقديم إجازة</p>
                </div>
            </Link>

            <Link
                href="/tasks"
                class="group flex items-center gap-3 rounded-2xl border border-border/80 bg-card p-3.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-accent/40 hover:shadow-sm"
            >
                <div class="flex size-10 items-center justify-center rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 transition-colors group-hover:bg-sky-600 group-hover:text-white">
                    <ClipboardList class="size-5" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-foreground group-hover:text-sky-600 dark:group-hover:text-sky-400">
                        {{ trans('dashboard.manage_tasks') }}
                    </p>
                    <p class="text-[11px] text-muted-foreground truncate">متابعة الشغل</p>
                </div>
            </Link>
        </div>

        <!-- Metrics Cards Grid -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <!-- Card 1: Attendance Rate (Primary Featured Gradient) -->
            <Card class="metric-card-hover relative overflow-hidden border-0 bg-gradient-to-br from-primary via-[hsl(174_62%_28%)] to-[hsl(192_48%_20%)] text-primary-foreground shadow-lg">
                <div class="pointer-events-none absolute -end-6 -bottom-6 size-32 rounded-full bg-white/5 blur-2xl"></div>
                <CardHeader class="pb-2">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-medium text-white/80 uppercase tracking-wider">{{ trans('dashboard.attendance_rate') }}</p>
                        <div class="flex size-9 items-center justify-center rounded-xl bg-white/10 backdrop-blur-md">
                            <CheckCircle2 class="size-5 text-white" />
                        </div>
                    </div>
                    <CardTitle class="text-4xl font-extrabold text-white mt-1">
                        {{ metrics.attendance_rate }}%
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-2 text-sm text-white/80">
                    <p class="text-xs">
                        {{ trans('dashboard.present_of_alt', { present: metrics.present_today, total: metrics.headcount }) }}
                    </p>
                    <!-- Progress Bar -->
                    <div class="h-1.5 w-full rounded-full bg-white/20 overflow-hidden">
                        <div
                            class="h-full rounded-full bg-white transition-all duration-500"
                            :style="{ width: `${Math.min(metrics.attendance_rate, 100)}%` }"
                        ></div>
                    </div>
                </CardContent>
            </Card>

            <!-- Card 2: Late Today -->
            <Card class="metric-card-hover border-border/80 shadow-xs">
                <CardHeader class="pb-2">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">{{ trans('dashboard.late_today') }}</p>
                        <div class="flex size-9 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <Clock3 class="size-5" />
                        </div>
                    </div>
                    <CardTitle class="text-4xl font-extrabold mt-1">
                        {{ metrics.late_today }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-xs text-muted-foreground">
                    <span class="inline-flex items-center gap-1 font-medium text-amber-600 dark:text-amber-400">
                        <Sparkles class="size-3" />
                        {{ trans('dashboard.late_month', { count: metrics.late_this_month }) }}
                    </span>
                </CardContent>
            </Card>

            <!-- Card 3: Average Work Hours -->
            <Card class="metric-card-hover border-border/80 shadow-xs">
                <CardHeader class="pb-2">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">{{ trans('dashboard.average_hours') }}</p>
                        <div class="flex size-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <Timer class="size-5" />
                        </div>
                    </div>
                    <CardTitle class="text-4xl font-extrabold mt-1">
                        {{ metrics.average_work_hours }}
                        <span class="text-sm font-normal text-muted-foreground">س</span>
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-xs text-muted-foreground">
                    {{ trans('dashboard.completed_shifts') }}
                </CardContent>
            </Card>

            <!-- Card 4: Task Completion Rate -->
            <Card class="metric-card-hover border-border/80 shadow-xs">
                <CardHeader class="pb-2">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">{{ trans('dashboard.task_completion') }}</p>
                        <div class="flex size-9 items-center justify-center rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400">
                            <Workflow class="size-5" />
                        </div>
                    </div>
                    <CardTitle class="text-4xl font-extrabold mt-1">
                        {{ metrics.task_completion_rate }}%
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-2 text-xs text-muted-foreground">
                    <p>
                        {{ trans('dashboard.tasks_completed', { completed: metrics.completed_tasks, total: metrics.total_tasks }) }}
                    </p>
                    <div class="h-1.5 w-full rounded-full bg-muted overflow-hidden">
                        <div
                            class="h-full rounded-full bg-sky-500 transition-all duration-500"
                            :style="{ width: `${Math.min(metrics.task_completion_rate, 100)}%` }"
                        ></div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Today's Floor Attendance Section -->
        <Card class="border-border/80 shadow-xs overflow-hidden">
            <CardHeader class="flex flex-col gap-3 border-b border-border/60 bg-muted/20 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <CardTitle class="text-lg font-bold">{{ trans('dashboard.floor') }}</CardTitle>
                        <span class="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-bold text-primary">
                            {{ metrics.today_attendance.length }}
                        </span>
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        {{ trans('dashboard.floor_hint') }}
                    </p>
                </div>
                <Link
                    href="/attendance"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors hover:text-primary/80 hover:underline"
                >
                    <span>{{ trans('dashboard.view_records') }}</span>
                    <ArrowUpRight class="size-4" />
                </Link>
            </CardHeader>

            <CardContent class="p-0">
                <div
                    v-if="metrics.today_attendance.length === 0"
                    class="flex flex-col items-center justify-center p-12 text-center"
                >
                    <div class="flex size-14 items-center justify-center rounded-full bg-muted/60 text-muted-foreground mb-3">
                        <Users class="size-6" />
                    </div>
                    <p class="text-sm font-medium text-foreground">{{ trans('dashboard.empty') }}</p>
                    <p class="text-xs text-muted-foreground mt-1 max-w-sm">
                        يمكن للموظفين استخدام زر "امسح الآن" أو شاشة الحضور لتسجيل الحضور اليومي.
                    </p>
                    <Link
                        href="/attendance/scan"
                        class="mt-4 inline-flex h-9 items-center gap-2 rounded-full bg-primary px-4 text-xs font-medium text-primary-foreground shadow-xs hover:bg-primary/90"
                    >
                        <ScanLine class="size-3.5" />
                        <span>تسجيل حضور جديد</span>
                    </Link>
                </div>

                <div v-else class="grid gap-3 p-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="row in metrics.today_attendance"
                        :key="row.id"
                        class="flex items-start gap-3 rounded-2xl border border-border/80 bg-muted/20 p-3.5 transition-colors hover:bg-muted/40"
                    >
                        <Avatar class="size-9 border border-border/60 shadow-xs">
                            <AvatarImage v-if="row.user?.avatar" :src="row.user.avatar" :alt="row.user.name" />
                            <AvatarFallback class="bg-primary/10 text-xs font-bold text-primary">
                                {{ getInitials(row.user?.name) }}
                            </AvatarFallback>
                        </Avatar>
                        <div class="min-w-0 flex-1 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <p class="font-medium text-foreground">
                                    {{ row.user?.name || '—' }}
                                </p>
                                <StatusBadge :value="row.status" :tone="attendanceTone(row.status)" />
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                                <span class="inline-flex items-center gap-1.5 rounded-md bg-muted/50 px-2.5 py-1">
                                    <Building2 class="size-3" />
                                    <span>{{ row.branch?.name || '—' }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1.5 font-mono">
                                    <Clock3 class="size-3.5" />
                                    <span>{{ formatTime(row.check_in) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
