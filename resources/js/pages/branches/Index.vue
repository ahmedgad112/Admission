<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
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

        <Card class="shadow-sm">
            <CardContent class="overflow-x-auto pt-6">
                <table class="w-full text-sm">
                    <thead class="text-start text-muted-foreground">
                        <tr>
                            <th class="pb-3 font-medium">{{ trans('common.branch') }}</th>
                            <th class="pb-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="branches.data.length === 0">
                            <td colspan="2" class="py-10 text-center text-muted-foreground">
                                {{ trans('branches.empty') }}
                            </td>
                        </tr>
                        <tr v-for="branch in branches.data" :key="branch.id" class="border-t border-border/70">
                            <td class="py-3.5 font-medium">{{ branch.name }}</td>
                            <td class="py-3.5 text-right">
                                <div class="flex justify-end gap-2">
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
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardContent>
        </Card>
    </div>
</template>
