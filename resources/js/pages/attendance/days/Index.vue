<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { trans } from '@/composables/useTrans';

type DayRow = {
    id: number;
    date: string;
    check_in_starts_at: string;
    check_in_ends_at: string;
    check_out_starts_at: string;
    check_out_ends_at: string;
    branch?: { id: number; name: string };
    staff?: { id: number; name: string }[];
};

defineProps<{
    days: { data: DayRow[] };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.roster', href: '/attendance/days' },
        ],
    },
});

function destroy(id: number): void {
    router.delete(`/attendance/days/${id}`);
}

function time(value: string): string {
    return value.slice(0, 5);
}

function staffLabel(staff: { id: number; name: string }[] | undefined): string {
    if (!staff || staff.length === 0) {
        return trans('roster.empty_staff');
    }

    if (staff.length <= 3) {
        return staff.map((member) => member.name).join(', ');
    }

    return `${staff
        .slice(0, 2)
        .map((member) => member.name)
        .join(', ')} +${staff.length - 2}`;
}
</script>

<template>
    <Head :title="trans('roster.eyebrow')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('roster.eyebrow')"
            :title="trans('roster.title')"
            :description="trans('roster.description')"
        >
            <template #actions>
                <Button class="rounded-full" as-child>
                    <Link href="/attendance/days/create">{{ trans('roster.new') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <Card class="shadow-sm">
            <CardContent class="overflow-x-auto pt-6">
                <table class="w-full text-sm">
                    <thead class="text-start text-muted-foreground">
                        <tr>
                            <th class="pb-3 font-medium">{{ trans('common.date') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.branch') }}</th>
                            <th class="pb-3 font-medium">{{ trans('scan.check_in') }}</th>
                            <th class="pb-3 font-medium">{{ trans('scan.check_out') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.staff') }}</th>
                            <th class="pb-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="days.data.length === 0">
                            <td colspan="6" class="py-10 text-center text-muted-foreground">
                                No rosters yet. Create one and choose the staff on duty.
                            </td>
                        </tr>
                        <tr v-for="day in days.data" :key="day.id" class="border-t border-border/70">
                            <td class="py-3.5 font-medium">{{ day.date }}</td>
                            <td class="py-3.5">{{ day.branch?.name }}</td>
                            <td class="py-3.5">
                                {{ time(day.check_in_starts_at) }} – {{ time(day.check_in_ends_at) }}
                            </td>
                            <td class="py-3.5">
                                {{ time(day.check_out_starts_at) }} – {{ time(day.check_out_ends_at) }}
                            </td>
                            <td class="py-3.5">
                                <span class="font-medium">{{ day.staff?.length ?? 0 }}</span>
                                <span class="block text-xs text-muted-foreground">
                                    {{ staffLabel(day.staff) }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <Button variant="outline" size="sm" class="rounded-full" as-child>
                                        <Link :href="`/attendance/days/${day.id}/edit`">{{ trans('common.edit') }}</Link>
                                    </Button>
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        class="rounded-full"
                                        @click="destroy(day.id)"
                                    >
                                        {{ trans('common.delete') }}
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardContent>
        </Card>
    </div>
</template>
