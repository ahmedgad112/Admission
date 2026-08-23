<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { trans } from '@/composables/useTrans';

type ShiftRow = {
    id: number;
    name: string;
    start_time: string;
    end_time: string;
    grace_period_minutes: number;
    staff_count: number;
};

defineProps<{
    shifts: { data: ShiftRow[] };
    canCreate: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.shifts', href: '/shifts' },
        ],
    },
});

function destroy(id: number): void {
    router.delete(`/shifts/${id}`);
}
</script>

<template>
    <Head :title="trans('shifts.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('shifts.eyebrow')"
            :title="trans('shifts.title')"
            :description="trans('shifts.description')"
        >
            <template #actions>
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/shifts/create">{{ trans('shifts.new') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <Card class="shadow-sm">
            <CardContent class="overflow-x-auto pt-6">
                <table class="w-full text-sm">
                    <thead class="text-start text-muted-foreground">
                        <tr>
                            <th class="pb-3 font-medium">{{ trans('common.shift') }}</th>
                            <th class="pb-3 font-medium">{{ trans('shifts.hours') }}</th>
                            <th class="pb-3 font-medium">{{ trans('shifts.grace') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.staff') }}</th>
                            <th class="pb-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="shifts.data.length === 0">
                            <td colspan="5" class="py-10 text-center text-muted-foreground">
                                {{ trans('shifts.empty') }}
                            </td>
                        </tr>
                        <tr v-for="shift in shifts.data" :key="shift.id" class="border-t border-border/70">
                            <td class="py-3.5 font-medium">{{ shift.name }}</td>
                            <td class="py-3.5">{{ shift.start_time }} – {{ shift.end_time }}</td>
                            <td class="py-3.5">{{ trans('shifts.grace_minutes', { count: shift.grace_period_minutes }) }}</td>
                            <td class="py-3.5">{{ shift.staff_count }}</td>
                            <td class="py-3.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <Button variant="outline" size="sm" class="rounded-full" as-child>
                                        <Link :href="`/shifts/${shift.id}/edit`">{{ trans('common.edit') }}</Link>
                                    </Button>
                                    <Button
                                        v-if="canCreate"
                                        variant="destructive"
                                        size="sm"
                                        class="rounded-full"
                                        @click="destroy(shift.id)"
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
