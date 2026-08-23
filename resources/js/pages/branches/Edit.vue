<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BranchForm from '@/components/BranchForm.vue';
import PageHeader from '@/components/PageHeader.vue';
import { trans } from '@/composables/useTrans';

type Branch = {
    id: number;
    name: string;
};

defineProps<{
    branch: Branch;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.branches', href: '/branches' },
            { title: 'common.edit', href: '/branches' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('branches.update')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('branches.eyebrow')"
            :title="trans('branches.update')"
            :description="trans('branches.description')"
        />

        <BranchForm :branch="branch" />
    </div>
</template>
