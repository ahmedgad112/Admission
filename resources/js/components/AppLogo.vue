<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { trans } from '@/composables/useTrans';

const name = usePage().props.name;
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-xl bg-sidebar-primary text-sidebar-primary-foreground shadow-sm"
    >
        <AppLogoIcon class="size-5" />
    </div>
    <div class="ms-1 grid flex-1 text-start text-sm">
        <span class="truncate leading-tight font-semibold">{{ name }}</span>
        <span class="truncate text-[11px] text-sidebar-foreground/60">
            {{ trans('brand.tagline') }}
        </span>
    </div>
</template>
