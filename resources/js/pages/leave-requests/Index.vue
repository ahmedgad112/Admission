<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { trans } from '@/composables/useTrans';
import { leaveRequestStatusTone } from '@/lib/status';

type LeaveRequestRow = {
    id: number;
    start_date: string;
    end_date: string;
    type: string;
    status: string;
    reason: string;
    user?: { id: number; name: string } | null;
    department?: { id: number; name: string } | null;
};

defineProps<{
    leaveRequests: { data: LeaveRequestRow[] };
    filters: { status: string; type: string };
    canCreate: boolean;
    canReview: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.leave', href: '/leave-requests' },
        ],
    },
});

function filter(key: 'status' | 'type', value: string): void {
    router.get(
        '/leave-requests',
        { [key]: value || undefined },
        { preserveState: true, replace: true },
    );
}

function dateRange(request: LeaveRequestRow): string {
    if (request.start_date === request.end_date) {
        return request.start_date;
    }

    return `${request.start_date} – ${request.end_date}`;
}
</script>

<template>
    <Head :title="trans('leave.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('leave.eyebrow')"
            :title="trans('leave.title')"
            :description="trans('leave.description')"
        >
            <template #actions>
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/leave-requests/create">{{ trans('leave.request') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <div class="flex flex-wrap gap-3">
            <select
                :value="filters.status"
                class="field-control max-w-48"
                @change="filter('status', ($event.target as HTMLSelectElement).value)"
            >
                <option value="">{{ trans('status.all') }}</option>
                <option value="pending">{{ trans('status.pending') }}</option>
                <option value="approved">{{ trans('status.approved') }}</option>
                <option value="rejected">{{ trans('status.rejected') }}</option>
                <option value="cancelled">{{ trans('status.cancelled') }}</option>
            </select>
            <select
                :value="filters.type"
                class="field-control max-w-48"
                @change="filter('type', ($event.target as HTMLSelectElement).value)"
            >
                <option value="">{{ trans('leave.type.all') }}</option>
                <option value="permission">{{ trans('leave.type.permission') }}</option>
                <option value="sick">{{ trans('leave.type.sick') }}</option>
                <option value="personal">{{ trans('leave.type.personal') }}</option>
            </select>
        </div>

        <div
            v-if="leaveRequests.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('leave.empty') }}
        </div>
        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card
                v-for="request in leaveRequests.data"
                :key="request.id"
                class="h-full shadow-sm transition-transform hover:-translate-y-0.5"
            >
                <CardHeader>
                    <CardTitle class="text-lg">{{ request.user?.name ?? '—' }}</CardTitle>
                    <CardDescription>
                        {{ request.department?.name ?? trans('common.no_department') }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <StatusBadge :value="request.status" :tone="leaveRequestStatusTone(request.status)" />
                    <dl class="grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-muted-foreground">{{ trans('common.date') }}</dt>
                            <dd class="font-medium">{{ dateRange(request) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted-foreground">{{ trans('common.type') }}</dt>
                            <dd class="font-medium">{{ trans(`leave.type.${request.type}`) }}</dd>
                        </div>
                    </dl>
                </CardContent>
                <CardFooter class="mt-auto border-t">
                    <Button variant="outline" size="sm" class="rounded-full" as-child>
                        <Link :href="`/leave-requests/${request.id}`">{{ trans('common.open') }}</Link>
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
