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
type Day = {
    id: number;
    branch_id: number;
    date: string;
    staff_ids: number[];
};

defineProps<{
    day: Day;
    branches: BranchOption[];
    staff: StaffOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.roster', href: '/attendance/days' },
            { title: 'common.edit', href: '/attendance/days' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('roster.update')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('roster.eyebrow')"
            :title="trans('roster.update')"
            :description="trans('roster.description')"
        />

        <AttendanceDayForm :day="day" :branches="branches" :staff="staff" />
    </div>
</template>
