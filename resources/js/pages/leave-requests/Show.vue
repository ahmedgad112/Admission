<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
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
import { leaveRequestStatusTone } from '@/lib/status';

type Person = { id: number; name: string; email?: string };
type LeaveRequest = {
    id: number;
    start_date: string;
    end_date: string;
    type: string;
    reason: string;
    status: string;
    review_note: string | null;
    reviewed_at: string | null;
    user?: Person | null;
    branch?: { id: number; name: string } | null;
    department?: { id: number; name: string } | null;
    reviewer?: Person | null;
};

const props = defineProps<{
    leaveRequest: LeaveRequest;
    canReview: boolean;
    canCancel: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.leave', href: '/leave-requests' },
            { title: 'common.details', href: '/leave-requests' },
        ],
    },
});

const reviewForm = useForm({
    status: 'approved',
    review_note: '',
});

function dateRange(): string {
    if (props.leaveRequest.start_date === props.leaveRequest.end_date) {
        return props.leaveRequest.start_date;
    }

    return `${props.leaveRequest.start_date} – ${props.leaveRequest.end_date}`;
}

function review(status: 'approved' | 'rejected'): void {
    reviewForm.status = status;
    reviewForm.post(`/leave-requests/${props.leaveRequest.id}/review`, {
        preserveScroll: true,
    });
}

function cancel(): void {
    router.post(
        `/leave-requests/${props.leaveRequest.id}/cancel`,
        {},
        { preserveScroll: true },
    );
}
</script>

<template>
    <Head :title="trans('leave.head')" />

    <div class="page-shell">
        <div
            class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between"
        >
            <div>
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-primary uppercase"
                >
                    {{ trans('leave.eyebrow') }}
                </p>
                <h1
                    class="mt-1 text-2xl font-semibold tracking-tight md:text-3xl"
                >
                    {{ leaveRequest.user?.name ?? trans('leave.head') }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    {{ dateRange() }}
                    ·
                    {{
                        leaveRequest.department?.name ??
                        leaveRequest.branch?.name ??
                        trans('common.no_team')
                    }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <StatusBadge
                    :value="leaveRequest.status"
                    :tone="leaveRequestStatusTone(leaveRequest.status)"
                />
                <Button
                    v-if="canCancel"
                    variant="outline"
                    class="rounded-full"
                    @click="cancel"
                >
                    {{ trans('leave.cancel') }}
                </Button>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <Card>
                <CardHeader>
                    <CardTitle>{{ trans('leave.details') }}</CardTitle>
                    <CardDescription>{{
                        trans(`leave.type.${leaveRequest.type}`)
                    }}</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <p class="text-sm whitespace-pre-wrap">
                        {{ leaveRequest.reason }}
                    </p>
                    <div v-if="canReview" class="space-y-3">
                        <textarea
                            v-model="reviewForm.review_note"
                            class="field-control min-h-24 py-3"
                            :placeholder="trans('leave.note_placeholder')"
                        />
                        <p
                            v-if="reviewForm.errors.review_note"
                            class="text-sm text-destructive"
                        >
                            {{ reviewForm.errors.review_note }}
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <Button
                                class="rounded-full"
                                :disabled="reviewForm.processing"
                                @click="review('approved')"
                            >
                                {{ trans('leave.approve') }}
                            </Button>
                            <Button
                                variant="destructive"
                                class="rounded-full"
                                :disabled="reviewForm.processing"
                                @click="review('rejected')"
                            >
                                {{ trans('leave.reject') }}
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>{{ trans('leave.review') }}</CardTitle>
                </CardHeader>
                <CardContent class="space-y-3 text-sm">
                    <div>
                        <p class="text-muted-foreground">
                            {{ trans('common.staff') }}
                        </p>
                        <p class="font-medium">
                            {{ leaveRequest.user?.name ?? '—' }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            {{ leaveRequest.user?.email }}
                        </p>
                    </div>
                    <div>
                        <p class="text-muted-foreground">
                            {{ trans('leave.reviewed_by') }}
                        </p>
                        <p class="font-medium">
                            {{
                                leaveRequest.reviewer?.name ??
                                trans('leave.not_reviewed')
                            }}
                        </p>
                    </div>
                    <div v-if="leaveRequest.reviewed_at">
                        <p class="text-muted-foreground">
                            {{ trans('leave.reviewed_at') }}
                        </p>
                        <p class="font-medium">
                            {{
                                new Date(
                                    leaveRequest.reviewed_at,
                                ).toLocaleString()
                            }}
                        </p>
                    </div>
                    <div v-if="leaveRequest.review_note">
                        <p class="text-muted-foreground">
                            {{ trans('leave.note') }}
                        </p>
                        <p class="whitespace-pre-wrap">
                            {{ leaveRequest.review_note }}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
