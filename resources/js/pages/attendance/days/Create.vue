<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AttendanceDayForm from '@/components/AttendanceDayForm.vue';
import PageHeader from '@/components/PageHeader.vue';
import { trans } from '@/composables/useTrans';

type BranchOption = { id: number; name: string };

defineProps<{
    branches: BranchOption[];
    defaultBranchId: number | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.roster', href: '/attendance/days' },
            { title: 'common.create', href: '/attendance/days/create' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('roster.new')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('roster.eyebrow')"
            :title="trans('roster.new')"
            :description="trans('roster.description')"
        />

        <AttendanceDayForm
            :branches="branches"
            :default-branch-id="defaultBranchId"
        />
    </div>
</template>
