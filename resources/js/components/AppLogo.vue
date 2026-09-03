<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { trans } from '@/composables/useTrans';

const name = usePage().props.name;
</script>

<template>
    <div
        class="flex aspect-square size-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary via-[hsl(174_62%_32%)] to-[hsl(192_48%_20%)] text-primary-foreground shadow-md transition-transform duration-200 group-hover:scale-105"
    >
        <AppLogoIcon class="size-5 text-white" />
    </div>
    <div class="ms-1.5 grid flex-1 text-start text-sm">
        <span
            class="truncate leading-tight font-bold tracking-tight text-foreground"
            >{{ name }}</span
        >
        <span
            class="truncate text-[11px] font-medium text-sidebar-foreground/60"
        >
            {{ trans('brand.tagline') }}
        </span>
    </div>
</template>
