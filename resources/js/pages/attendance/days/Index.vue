<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Download } from '@lucide/vue';
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
    present_count?: number;
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
                    <Link href="/attendance/days/create">{{
                        trans('roster.new')
                    }}</Link>
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
                        {{
                            trans('roster.present_count', {
                                count: day.present_count ?? 0,
                            })
                        }}
                    </p>
                </CardContent>
                <CardFooter class="mt-auto flex flex-wrap gap-2 border-t">
                    <Button
                        variant="outline"
                        size="sm"
                        class="rounded-full"
                        as-child
                    >
                        <Link :href="`/attendance/days/${day.id}`">{{
                            trans('common.view')
                        }}</Link>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        class="rounded-full"
                        as-child
                    >
                        <a :href="`/attendance/days/${day.id}/export`">
                            <Download class="size-4" />
                            {{ trans('attendance.download') }}
                        </a>
                    </Button>
                    <Button
                        v-if="canCreate"
                        variant="outline"
                        size="sm"
                        class="rounded-full"
                        as-child
                    >
                        <Link :href="`/attendance/days/${day.id}/edit`">{{
                            trans('common.edit')
                        }}</Link>
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
