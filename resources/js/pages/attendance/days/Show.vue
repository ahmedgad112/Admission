<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { trans } from '@/composables/useTrans';

type StaffMember = {
    id: number;
    name: string;
    department?: { id: number; name: string } | null;
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
    staff: StaffMember[];
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
                    <Link href="/attendance/days">{{ trans('common.back') }}</Link>
                </Button>
                <Button v-if="canUpdate" class="rounded-full" as-child>
                    <Link :href="`/attendance/days/${day.id}/edit`">{{ trans('common.edit') }}</Link>
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
                    <CardTitle>{{ trans('roster.staff_on_duty') }}</CardTitle>
                    <CardDescription>
                        {{ trans('common.staff') }} · {{ day.staff.length }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="pt-4 sm:pt-6">
                    <div
                        v-if="day.staff.length === 0"
                        class="rounded-2xl border border-dashed p-6 text-center text-sm text-muted-foreground"
                    >
                        {{ trans('roster.empty_staff') }}
                    </div>
                    <div v-else class="overflow-x-auto rounded-2xl border">
                        <table class="w-full min-w-[20rem] text-start text-sm">
                            <thead class="bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">{{ trans('common.name') }}</th>
                                    <th class="px-4 py-3 font-semibold">{{ trans('common.department') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="member in day.staff"
                                    :key="member.id"
                                    class="border-t"
                                >
                                    <td class="px-4 py-3 font-medium">{{ member.name }}</td>
                                    <td class="px-4 py-3 text-muted-foreground">
                                        {{ member.department?.name ?? '—' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        <p class="text-xs font-semibold tracking-wide text-muted-foreground uppercase">
                            {{ trans('roster.check_in') }}
                        </p>
                        <p class="mt-1 text-sm font-medium tabular-nums">
                            {{ windowLabel(day.check_in_starts_at, day.check_in_ends_at) }}
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
                        <p class="text-xs font-semibold tracking-wide text-muted-foreground uppercase">
                            {{ trans('roster.check_out') }}
                        </p>
                        <p class="mt-1 text-sm font-medium tabular-nums">
                            {{ windowLabel(day.check_out_starts_at, day.check_out_ends_at) }}
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
