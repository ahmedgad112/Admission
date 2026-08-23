<script setup lang="ts">
defineProps<{
    eyebrow?: string;
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div class="min-w-0 space-y-1">
            <p
                v-if="eyebrow"
                class="text-xs font-semibold tracking-[0.18em] text-primary uppercase"
            >
                {{ eyebrow }}
            </p>
            <h1 class="text-xl font-semibold tracking-tight sm:text-2xl md:text-3xl">
                {{ title }}
            </h1>
            <p v-if="description" class="max-w-2xl text-xs leading-5 text-muted-foreground sm:text-sm sm:leading-6">
                {{ description }}
            </p>
        </div>
        <div v-if="$slots.actions" class="flex w-full shrink-0 flex-wrap items-end gap-2 sm:gap-3 lg:w-auto">
            <slot name="actions" />
        </div>
    </div>
</template>
