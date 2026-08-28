<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DepartmentForm from '@/components/DepartmentForm.vue';
import PageHeader from '@/components/PageHeader.vue';
import { trans } from '@/composables/useTrans';

defineProps<{
    department: {
        id: number;
        name: string;
        branch_id: number;
        manager_id: number | null;
    };
    branches: { id: number; name: string }[];
    managers: { id: number; name: string }[];
    defaultBranchId?: number | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.departments', href: '/departments' },
            { title: 'common.edit', href: '#' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('departments.update')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('departments.eyebrow')"
            :title="department.name"
            :description="trans('departments.description')"
        />

        <DepartmentForm
            :department="department"
            :branches="branches"
            :managers="managers"
            :default-branch-id="defaultBranchId"
        />
    </div>
</template>
