<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DepartmentForm from '@/components/DepartmentForm.vue';
import PageHeader from '@/components/PageHeader.vue';
import { trans } from '@/composables/useTrans';

defineProps<{
    branches: { id: number; name: string }[];
    managers: { id: number; name: string }[];
    defaultBranchId?: number | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.departments', href: '/departments' },
            { title: 'common.create', href: '/departments/create' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('departments.new')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('departments.eyebrow')"
            :title="trans('departments.new')"
            :description="trans('departments.description')"
        />

        <DepartmentForm
            :branches="branches"
            :managers="managers"
            :default-branch-id="defaultBranchId"
        />
    </div>
</template>
