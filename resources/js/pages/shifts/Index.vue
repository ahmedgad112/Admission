<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
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

        <div
            v-if="shifts.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('shifts.empty') }}
        </div>
        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card
                v-for="shift in shifts.data"
                :key="shift.id"
                class="h-full shadow-sm transition-transform hover:-translate-y-0.5"
            >
                <CardHeader>
                    <CardTitle class="text-lg">{{ shift.name }}</CardTitle>
                    <CardDescription>{{ shift.start_time }} – {{ shift.end_time }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <dl class="grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-muted-foreground">{{ trans('shifts.grace') }}</dt>
                            <dd class="font-medium">
                                {{ trans('shifts.grace_minutes', { count: shift.grace_period_minutes }) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted-foreground">{{ trans('common.staff') }}</dt>
                            <dd class="font-medium">{{ shift.staff_count }}</dd>
                        </div>
                    </dl>
                </CardContent>
                <CardFooter class="mt-auto flex flex-wrap gap-2 border-t">
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
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
