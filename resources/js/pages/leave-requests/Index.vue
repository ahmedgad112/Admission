<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
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

        <Card class="shadow-sm">
            <CardContent class="overflow-x-auto pt-6">
                <table class="w-full text-sm">
                    <thead class="text-start text-muted-foreground">
                        <tr>
                            <th class="pb-3 font-medium">{{ trans('common.staff') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.date') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.type') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.status') }}</th>
                            <th class="pb-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="leaveRequests.data.length === 0">
                            <td colspan="5" class="py-10 text-center text-muted-foreground">
                                {{ trans('leave.empty') }}
                            </td>
                        </tr>
                        <tr
                            v-for="request in leaveRequests.data"
                            :key="request.id"
                            class="border-t border-border/70"
                        >
                            <td class="py-3.5">
                                <p class="font-medium">{{ request.user?.name ?? '—' }}</p>
                                <p class="text-xs text-muted-foreground">
                                    {{ request.department?.name ?? trans('common.no_department') }}
                                </p>
                            </td>
                            <td class="py-3.5">{{ dateRange(request) }}</td>
                            <td class="py-3.5">{{ trans(`leave.type.${request.type}`) }}</td>
                            <td class="py-3.5">
                                <StatusBadge :value="request.status" :tone="leaveRequestStatusTone(request.status)" />
                            </td>
                            <td class="py-3.5 text-right">
                                <Button variant="outline" size="sm" class="rounded-full" as-child>
                                    <Link :href="`/leave-requests/${request.id}`">{{ trans('common.open') }}</Link>
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardContent>
        </Card>
    </div>
</template>
