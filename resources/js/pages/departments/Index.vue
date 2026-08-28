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

type DepartmentRow = {
    id: number;
    name: string;
    branch?: { id: number; name: string } | null;
    manager?: { id: number; name: string } | null;
    staff_count: number;
};

defineProps<{
    departments: { data: DepartmentRow[] };
    canCreate: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.departments', href: '/departments' },
        ],
    },
});

function destroy(id: number): void {
    router.delete(`/departments/${id}`);
}
</script>

<template>
    <Head :title="trans('departments.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('departments.eyebrow')"
            :title="trans('departments.title')"
            :description="trans('departments.description')"
        >
            <template #actions>
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/departments/create">{{ trans('departments.new') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <div
            v-if="departments.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('departments.empty') }}
        </div>
        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card
                v-for="department in departments.data"
                :key="department.id"
                class="h-full shadow-sm transition-transform hover:-translate-y-0.5"
            >
                <CardHeader>
                    <CardTitle class="text-lg">{{ department.name }}</CardTitle>
                    <CardDescription>
                        {{ department.branch?.name ?? trans('common.branch') }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <dl class="grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-muted-foreground">{{ trans('departments.manager') }}</dt>
                            <dd class="font-medium">
                                {{ department.manager?.name ?? trans('common.none') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted-foreground">{{ trans('common.staff') }}</dt>
                            <dd class="font-medium">{{ department.staff_count }}</dd>
                        </div>
                    </dl>
                </CardContent>
                <CardFooter class="mt-auto flex flex-wrap gap-2 border-t">
                    <Button variant="outline" size="sm" class="rounded-full" as-child>
                        <Link :href="`/departments/${department.id}/edit`">{{ trans('common.edit') }}</Link>
                    </Button>
                    <Button
                        v-if="canCreate"
                        variant="destructive"
                        size="sm"
                        class="rounded-full"
                        @click="destroy(department.id)"
                    >
                        {{ trans('common.delete') }}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
