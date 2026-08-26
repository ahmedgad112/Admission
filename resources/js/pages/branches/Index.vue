<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { trans } from '@/composables/useTrans';

type BranchRow = {
    id: number;
    name: string;
};

defineProps<{
    branches: { data: BranchRow[] };
    canCreate: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.branches', href: '/branches' },
        ],
    },
});

function destroy(id: number): void {
    router.delete(`/branches/${id}`);
}
</script>

<template>
    <Head :title="trans('branches.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('branches.eyebrow')"
            :title="trans('branches.title')"
            :description="trans('branches.description')"
        >
            <template #actions>
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/branches/create">{{ trans('branches.new') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <div
            v-if="branches.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('branches.empty') }}
        </div>
        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card
                v-for="branch in branches.data"
                :key="branch.id"
                class="h-full shadow-sm transition-transform hover:-translate-y-0.5"
            >
                <CardHeader>
                    <CardTitle class="text-lg">{{ branch.name }}</CardTitle>
                    <CardDescription>{{ trans('common.branch') }}</CardDescription>
                </CardHeader>
                <CardFooter class="mt-auto flex flex-wrap gap-2 border-t">
                    <Button variant="outline" size="sm" class="rounded-full" as-child>
                        <Link :href="`/branches/${branch.id}/edit`">{{ trans('common.edit') }}</Link>
                    </Button>
                    <Button
                        v-if="canCreate"
                        variant="destructive"
                        size="sm"
                        class="rounded-full"
                        @click="destroy(branch.id)"
                    >
                        {{ trans('common.delete') }}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
