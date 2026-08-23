<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import ShiftForm from '@/components/ShiftForm.vue';
import { trans } from '@/composables/useTrans';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.shifts', href: '/shifts' },
            { title: 'common.create', href: '/shifts/create' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('shifts.new')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('shifts.eyebrow')"
            :title="trans('shifts.new')"
            :description="trans('shifts.description')"
        />

        <ShiftForm />
    </div>
</template>
