<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import ShiftForm from '@/components/ShiftForm.vue';
import { trans } from '@/composables/useTrans';

type Shift = {
    id: number;
    name: string;
    start_time: string;
    end_time: string;
    grace_period_minutes: number;
};

defineProps<{
    shift: Shift;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.shifts', href: '/shifts' },
            { title: 'common.edit', href: '/shifts' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('shifts.update')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('shifts.eyebrow')"
            :title="trans('shifts.update')"
            :description="trans('shifts.description')"
        />

        <ShiftForm :shift="shift" />
    </div>
</template>
