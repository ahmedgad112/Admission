<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AttendanceDayForm from '@/components/AttendanceDayForm.vue';
import PageHeader from '@/components/PageHeader.vue';
import { trans } from '@/composables/useTrans';

type BranchOption = { id: number; name: string };
type StaffOption = {
    id: number;
    name: string;
    branch_id: number | null;
    role: string;
    department?: { id: number; name: string } | null;
};

defineProps<{
    branches: BranchOption[];
    staff: StaffOption[];
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
            :staff="staff"
            :default-branch-id="defaultBranchId"
        />
    </div>
</template>
