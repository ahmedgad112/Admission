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

type DayRow = {
    id: number;
    date: string;
    branch?: { id: number; name: string };
    staff?: { id: number; name: string }[];
};

defineProps<{
    days: { data: DayRow[] };
    canCreate: boolean;
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
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/attendance/days/create">{{ trans('roster.new') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <div
            v-if="days.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('roster.empty') }}
        </div>
        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card
                v-for="day in days.data"
                :key="day.id"
                class="h-full shadow-sm transition-transform hover:-translate-y-0.5"
            >
                <CardHeader>
                    <CardTitle class="text-lg">{{ day.date }}</CardTitle>
                    <CardDescription>{{ day.branch?.name }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <p class="text-sm font-medium">
                        {{ trans('common.staff') }} · {{ day.staff?.length ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs text-muted-foreground">
                        {{ staffLabel(day.staff) }}
                    </p>
                </CardContent>
                <CardFooter class="mt-auto flex flex-wrap gap-2 border-t">
                    <Button variant="outline" size="sm" class="rounded-full" as-child>
                        <Link :href="`/attendance/days/${day.id}`">{{ trans('common.view') }}</Link>
                    </Button>
                    <Button v-if="canCreate" variant="outline" size="sm" class="rounded-full" as-child>
                        <Link :href="`/attendance/days/${day.id}/edit`">{{ trans('common.edit') }}</Link>
                    </Button>
                    <Button
                        v-if="canCreate"
                        variant="destructive"
                        size="sm"
                        class="rounded-full"
                        @click="destroy(day.id)"
                    >
                        {{ trans('common.delete') }}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
