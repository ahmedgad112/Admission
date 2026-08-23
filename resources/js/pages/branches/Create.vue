<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BranchForm from '@/components/BranchForm.vue';
import PageHeader from '@/components/PageHeader.vue';
import { trans } from '@/composables/useTrans';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.branches', href: '/branches' },
            { title: 'common.create', href: '/branches/create' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('branches.new')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('branches.eyebrow')"
            :title="trans('branches.new')"
            :description="trans('branches.description')"
        />

        <BranchForm />
    </div>
</template>
