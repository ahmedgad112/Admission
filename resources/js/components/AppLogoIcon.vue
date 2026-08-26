<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 32 32"
        fill="none"
        :class="className"
        v-bind="$attrs"
    >
        <path
            d="M7.5 16.8 13.2 22.4 24.8 9.6"
            stroke="currentColor"
            stroke-width="3.4"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
</template>
